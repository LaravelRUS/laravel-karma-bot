if (typeof String.prototype.endsWith !== 'function') {
    String.prototype.endsWith = function(suffix) {
        return this.indexOf(suffix, this.length - suffix.length) !== -1;
    };
}

var Gitter = require('node-gitter'),
    redis  = require("redis"),
    client = redis.createClient(),
    gitter = new Gitter("TOKEN"),
    shell = require('shelljs');

client.on("error", function (err) {
    console.log("Error " + err);
});

var options = {
    roomId: "56019a060fc9f982beb17a5e", // 56019a060fc9f982beb17a5e KarmaTest , 52f9b90e5e986b0712ef6b9d LaravelRUS
    botId: "560199e90fc9f982beb17a59",
    karmaPerTime: 300000, // 300000, 5 минут
    karmaPerTimeFromUser: 3600000, // 3600000, 1 час,
    karmaPeriod: 60000 // минута
};


gitter.rooms.find(options.roomId).then(function (room) {
    var events = room.streaming().chatMessages();

    events.on('chatMessages', function (message) {
        if (message.operation == 'create') {
            if (App.isBotMessage(message.model)) {
                return true;
            }
            if (App.isThanksMessageToNoOne(message.model)) {
                room.send(MessagesBag.toWhomThanks(message.model));
                return true;
            }
            if (App.isThanksMessage(message.model)) {
                App.processThanks(message.model, room);
                return true;
            }
            if (App.isKarmaMessage(message.model)) {
                App.processKarma(message.model, room);
                return true;
            }
            if (App.isSqlMessage(message.model)) {
                App.processSql(message.model, room);
                return true;
            }
        }
    });
});


var App = (function () {
    var isThanksMessageToNoOne = function(message) {
        return !!(isThanksMessage(message) && message.mentions.length <= 0);
    };

    var isThanksMessage = function(message) {
        var text = message.text;

        text = text.replace(/@([0-9a-zA-Z\- \/_?:.,\s]+) /g, function() { return ""; }).trim().toLocaleLowerCase().replace(
            /[.,-\/#!$%\^&\*;:{}=\-_`~()]/g, ""
        );

        return text.indexOf('спасибо') === 0 || text.endsWith('спасибо');
    };

    var isBotMessage = function(message) {
        return message.fromUser.id == options.botId;
    };

    var isKarmaMessage = function(message) {
        var text = message.text.toLowerCase();

        return text === "карма";
    };

    var isSqlMessage = function(message) {
        var text = message.text.trim().toLowerCase();

        return text.indexOf('select') === 0;
    };

    var processThanks = function(message, room) {
        var mentionedUsers = message.mentions;

        mentionedUsers.forEach(function(user) {
            if (message.fromUser.id == user.userId) {
                room.send(MessagesBag.errorYourSelfThanks(message));
                return false;
            }

            if (user.userId === undefined) {
                room.send(MessagesBag.userNotExists(user));
                return false;
            }

            var now = Date.now();

            client.get(options.roomId + "::" + user.userId + "::lastThanksTime", function(err, lastThanksTime) {
                if (lastThanksTime !== null && now - lastThanksTime <= options.karmaPerTime) {
                    room.send(MessagesBag.errorTooManyToUser(user));
                    return false;
                }

                client.get(options.roomId + "::" + message.fromUser.id + "::" + user.userId + "::lastThanksTime", function(err, lastThanksTimeFromUser) {
                    if (lastThanksTimeFromUser !== null && now - lastThanksTimeFromUser <= options.karmaPerTimeFromUser) {
                        room.send(MessagesBag.errorTooManyFromUserToUser(message, user));
                        return false;
                    }

                    client.incr(options.roomId + "::" + user.userId + "::karma");
                    client.incr(options.roomId + "::" + message.fromUser.id + "::" + user.userId + "::karma");
                    client.set(options.roomId + "::" + user.userId + "::lastThanksTime", now);
                    client.set(options.roomId + "::" + message.fromUser.id + "::" + user.userId + "::lastThanksTime", now);

                    client.get(options.roomId + "::" + user.userId + "::karma", function(err, karma) {
                        room.send(MessagesBag.successThanks(user, karma));
                    });
                });
            });
        });
    };

    var processKarma = function(message, room) {
        var now = Date.now();

        client.get(options.roomId + "::" + message.fromUser.id + "::karma", function(err, karma) {
            client.get(options.roomId + "::" + message.fromUser.id + "::karma::lastTime", function(err, lastTime) {
                if (lastTime !== null && now - lastTime <= options.karmaPeriod) {
                    return false;
                }

                if (karma === null && lastTime !== null && now - lastTime <= options.karmaPeriod) {
                    room.send(MessagesBag.noKarma(message));
                    return false;
                }

                client.set(options.roomId + "::" + message.fromUser.id + "::karma::lastTime", now);

                if (karma === null) {
                    room.send(MessagesBag.noKarma(message));
                    return false;
                }

                room.send(MessagesBag.karmaMessage(message, karma));
            });
        });
    };

    var processSql = function(message, room) {
        var text = message.text.trim().replace(/"/g, "'").replace(/\\/g, '').replace(/`/g, "");
        var result = shell.exec("php artisan sql:build \"" + text + "\"", {silent:true}).output;

        room.send("```\n" + result + "\n```");
    };

    return {
        isThanksMessageToNoOne: isThanksMessageToNoOne,
        isThanksMessage: isThanksMessage,
        isBotMessage: isBotMessage,
        isKarmaMessage: isKarmaMessage,
        isSqlMessage: isSqlMessage,
        processThanks: processThanks,
        processKarma: processKarma,
        processSql: processSql
    }
})();

var MessagesBag = (function() {
    var toWhomThanks = function(message) {
        return "@" + message.fromUser.username + " *Кому спасибо-то? Укажите своё спасибо в формате «спасибо @nickname».*";
    };

    var errorYourSelfThanks = function(message) {
        return "@" + message.fromUser.username + " *Нельзя говорить спасибо самому себе!*";
    };

    var errorTooManyToUser = function(user) {
        return "*Спасибо для пользователя @" + user.screenName + " не добавилось. Слишком частое спасибо!*"
    };

    var errorTooManyFromUserToUser = function(message, user) {
        return "@" + message.fromUser.username + " *Вы слишком часто говорите пользователю @" + user.screenName + " спасибо. Слишком частое спасибо!*"
    };

    var successThanks = function(user, karma) {
        return "*Спасибо принято. Текущая карма пользователя* @" + user.screenName + " **+" + karma + "**";
    };

    var userNotExists = function(user) {
        return "*Пользователь @" + user.screenName + " не существует или не находится в комнате*";
    };

    var karmaMessage = function(message, karma) {
        return "@" + message.fromUser.username + " *Ваша карма **+" + karma + "***";
    };

    var noKarma = function(message) {
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

gitter.currentUser().then(function (user) {
    user.rooms().then(function (rooms) {
        // console.log(rooms);
    });
    console.log('You are logged in as:', user.id);
});
