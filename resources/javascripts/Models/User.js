import Model from "/Lib/Model";
import Router from "/Lib/Router";
import Karma from "/Models/Karma";
import Thank from "/Models/Thank";
import Request from "/Lib/Request";
import Achieve from "/Models/Achieve";
import Collection from "/Lib/Collection";

/**
 * User
 */
export default class User extends Model {
    /**
     * @type {string}
     */
    static request = '/api/users.json';

    /**
     * User load state
     */
    loaded = ko.observable(false);

    /**
     * @param query
     * @param count
     * @returns {Collection}
     */
    static search(query, count = 10) {
        var escape = query => query.toLowerCase();

        query = escape(query);

        return this.query()
            .find((user:User) => {
                user.resetHighlight();

                return escape(user.name).search(query) >= 0 ||
                    escape(user.login).search(query) >= 0;
            })
            .take(count);
    }

    /**
     * @type {{login: *, name: *}}
     */
    highlight = {
        login: ko.observable(''),
        name:  ko.observable('')
    };

    /**
     * @param properties
     */
    constructor(properties = {}) {
        properties.route = properties.url.substr(1);
        super(properties);
        this.resetHighlight();
    }

    /**
     * Load additional data
     */
    load(callback = function(){}) {
        var url = `/api/user/${this.gitter_id}.json`;
        (new Request(url))
            .get(`Загрузка пользователя ${this.login}`)
            .then(result => {
                this.properties['achievements'] = new Collection(result.achievements).of(Achieve);
                this.properties['karma']        = new Collection(result.karma).of(Karma);
                this.properties['thanks']       = new Collection(result.thanks).of(Thank);

                this.loaded(true);

                callback(this);
            });

        return this;
    }

    /**
     * @returns {User}
     */
    profile() {
        Router.get('home').move();
        Router.get('user').move({user: this.route});
        return this;
    }

    /**
     * @returns {Achieve[]}
     */
    get achievements() {
        return this.get('achievements');
    }

    /**
     * @returns {Karma[]}
     */
    get karma() {
        return this.get('karma');
    }

    /**
     * @returns {Thank[]}
     */
    get thanks() {
        return this.get('thanks');
    }

    /**
     * @returns {User}
     */
    resetHighlight() {
        this.highlight.login(this.login);
        this.highlight.name(this.name);
        return this;
    }

    /**
     * @param query
     * @returns {User}
     */
    doHighlight(query) {
        this.resetHighlight();

        var highlight = (property) => {
            var regexp = new RegExp(query, 'i');

            var pattern = (q) => `<span class="highlight">${q}</span>`;

            var matches = [];
            if (matches = this[property].match(regexp)) {
                var escapedQuery = matches[0]
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;");

                this.highlight[property](
                    this[property].replace(regexp, pattern(escapedQuery))
                );
            }
        };

        highlight('name');
        highlight('login');

        return this;
    }
}