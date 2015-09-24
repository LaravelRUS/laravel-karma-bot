var Gitter = require('node-gitter'),
    redis  = require("redis"),
    client = redis.createClient(),
    shell  = require('shelljs'),
    endsWithAny = require('ends-with-any'),
    startsWithAny = require('starts-with-any'),
    config = require('config'),
    gitter = new Gitter(config.get("token"));


client.on("error", function (err) {
    console.log("Error " + err);
});
shell.cd('..');


class Message {
    constructor(model) {
        this.model = model;
    }

    isBot() {
        return this.model.fromUser.id == config.get("botId");
    }

    isThanks() {
        var text = this.model.text;

        text = text.replace(/@([0-9a-zA-Z\- \/_?:.,\s]+) /g, function () {
            return "";
        }).trim().toLocaleLowerCase().replace(
            /[.,-\/#!$%\^&\*;:{}=\-_`~()]/g, ""
        );

        return startsWithAny(text, config.get("thanksText")) || endsWithAny(text, config.get("thanksText"));
    }

    isThanksToNoOne() {
        return !!(this.isThanks() && this.model.mentions.length <= 0);
    }

    isKarma() {
        return this.model.text.toLowerCase() === "карма";
    }

    isSql() {
        return this.model.text.trim().toLowerCase().indexOf('select') === 0;
    }
}


gitter.currentUser().then(function (user) {
    user.rooms().then(function (rooms) {
        // console.log(rooms);
    });
    console.log('You are logged in as:', user.id);
});


gitter.rooms.find(config.get("roomId")).then(function (room) {
    var events = room.streaming().chatMessages();

    events.on('chatMessages', function (message) {
        if (message.operation == 'create') {
            var messageObj = new Message(message.model);

            if (messageObj.isBot()) {
                return true;
            }
            if (messageObj.isThanksToNoOne()) {
                room.send(MessagesBag.toWhomThanks(messageObj.model));
                return true;
            }
            if (messageObj.isThanks()) {
                App.processThanks(messageObj.model, room);
                return true;
            }
            if (messageObj.isKarma()) {
                App.processKarma(messageObj.model, room);
                return true;
            }
            if (messageObj.isSql()) {
                App.processSql(messageObj.model, room);
                return true;
            }
        }
    });
});


var App = (function () {
    var processThanks = function (message, room) {
        var mentionedUsers = message.mentions;

        mentionedUsers.forEach(function (user) {
            if (message.fromUser.id == user.userId) {
                room.send(MessagesBag.errorYourSelfThanks(message));
                return false;
            }

            if (user.userId === undefined) {
                room.send(MessagesBag.userNotExists(user));
                return false;
            }

            var now = Date.now();

            client.get(config.get("roomId") + "::" + user.userId + "::lastThanksTime", function (err, lastThanksTime) {
                if (lastThanksTime !== null && now - lastThanksTime <= config.get("karmaPerTime")) {
                    room.send(MessagesBag.errorTooManyToUser(user));
                    return false;
                }

                client.get(config.get("roomId") + "::" + message.fromUser.id + "::" + user.userId + "::lastThanksTime", function (err, lastThanksTimeFromUser) {
                    if (lastThanksTimeFromUser !== null && now - lastThanksTimeFromUser <= config.get("karmaPerTimeFromUser")) {
                        room.send(MessagesBag.errorTooManyFromUserToUser(message, user));
                        return false;
                    }

                    client.incr(config.get("roomId") + "::" + user.userId + "::karma");
                    client.incr(config.get("roomId") + "::" + message.fromUser.id + "::" + user.userId + "::karma");
                    client.set(config.get("roomId") + "::" + user.userId + "::lastThanksTime", now);
                    client.set(config.get("roomId") + "::" + message.fromUser.id + "::" + user.userId + "::lastThanksTime", now);

                    client.get(config.get("roomId") + "::" + user.userId + "::karma", function (err, karma) {
                        room.send(MessagesBag.successThanks(user, karma));
                    });
                });
            });
        });
    };

    var processKarma = function (message, room) {
        var now = Date.now();

        client.get(config.get("roomId") + "::" + message.fromUser.id + "::karma", function (err, karma) {
            client.get(config.get("roomId") + "::" + message.fromUser.id + "::karma::lastTime", function (err, lastTime) {
                if (lastTime !== null && now - lastTime <= config.get("karmaPeriod")) {
                    return false;
                }

                if (karma === null && lastTime !== null && now - lastTime <= config.get("karmaPeriod")) {
                    room.send(MessagesBag.noKarma(message));
                    return false;
                }

                client.set(config.get("roomId") + "::" + message.fromUser.id + "::karma::lastTime", now);

                if (karma === null) {
                    room.send(MessagesBag.noKarma(message));
                    return false;
                }

                room.send(MessagesBag.karmaMessage(message, karma));
            });
        });
    };

    var processSql = function (message, room) {
        var text = message.text.trim().replace(/"/g, "'").replace(/\\/g, '').replace(/`/g, "");
        var result = shell.exec("php artisan sql:build \"" + text + "\"", {silent: true}).output;

        room.send("```\n" + result + "\n```");
    };

    return {
        processThanks: processThanks,
        processKarma: processKarma,
        processSql: processSql
    }
})();


var MessagesBag = (function () {
    var toWhomThanks = function (message) {
        return "@" + message.fromUser.username + " *Кому спасибо-то? Укажите своё спасибо в формате «спасибо @nickname».*";
    };

    var errorYourSelfThanks = function (message) {
        return "@" + message.fromUser.username + " *Нельзя говорить спасибо самому себе!*";
    };

    var errorTooManyToUser = function (user) {
        return "*Спасибо для пользователя @" + user.screenName + " не добавилось. Слишком частое спасибо!*"
    };

    var errorTooManyFromUserToUser = function (message, user) {
        return "@" + message.fromUser.username + " *Вы слишком часто говорите пользователю @" + user.screenName + " спасибо. Слишком частое спасибо!*"
    };

    var successThanks = function (user, karma) {
        return "*Спасибо принято. Текущая карма пользователя* @" + user.screenName + " **+" + karma + "**";
    };

    var userNotExists = function (user) {
        return "*Пользователь @" + user.screenName + " не существует или не находится в комнате*";
    };

    var karmaMessage = function (message, karma) {
        return "@" + message.fromUser.username + " *Ваша карма **+" + karma + "***";
    };

    var noKarma = function (message) {
        return "@" + message.fromUser.username + " *Вас ещё никто не благодарил*";
    };

    return {
        toWhomThanks: toWhomThanks,
        errorYourSelfThanks: errorYourSelfThanks,
        errorTooManyToUser: errorTooManyToUser,
        errorTooManyFromUserToUser: errorTooManyFromUserToUser,
        successThanks: successThanks,
        userNotExists: userNotExists,
        karmaMessage: karmaMessage,
        noKarma: noKarma
    }
})();