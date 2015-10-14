/**
 *
 */
export class Route {
    /**
     * @type {string}
     */
    pattern = '/';

    /**
     * @type {{}}
     */
    wheres = {};

    /**
     * @type {null|string}
     */
    name = null;

    /**
     * @type {Array}
     */
    matches = [];

    /**
     * @param pattern
     * @param name
     */
    constructor(pattern, name = null) {
        this.pattern = pattern;
        this.name = name;
    }

    /**
     * @param args
     * @param group
     * @returns {string}
     */
    url(args = {}, group = false) {
        var result = this.pattern;
        Object.keys(args).forEach(key => {
            var value = group ? `(${args[key]})` : args[key];
            result = result.replace(new RegExp(`{${key}}`, 'gi'), value);
        });
        return result;
    }

    /**
     * @param key
     * @param value
     * @returns {Route}
     */
    where(key, value) {
        this.wheres[key] = value;
        return this;
    }

    /**
     * @param url
     */
    match(url = location.pathname) {
        return this.matches = url.match(new RegExp(`^${this.url(this.wheres, true)}$`, 'i'));
    }

    /**
     * @param args
     */
    move(args = {}) {
        history.pushState(args, null, this.url(args));
        Router.match();
    }

    /**
     * @returns {Object}
     */
    getStateArguments() {
        return history.state;
    }
}

/**
 *
 */
export default class Router {
    /**
     * @type {Array}
     */
    static routes = [];

    /**
     * @type {Route}
     */
    static current = ko.observable();

    /**
     * @param pattern
     * @param name
     * @returns {Route}
     */
    static add(pattern, name = null) {
        return this.addRoute(new Route(pattern, name));
    }

    /**
     * @param route
     * @returns {Route}
     */
    static addRoute(route:Route) {
        this.routes.push(route);
        return route;
    }

    /**
     * @param name
     * @returns {*}
     */
    static get(name) {
        for (var i = 0; i < this.routes.length; i++) {
            var route = this.routes[i];
            if (route.name === name) {
                return route;
            }
        }
        return null;
    }

    /**
     * @returns {*}
     */
    static match() {
        for (var i = 0; i < this.routes.length; i++) {
            var route = this.routes[i];
            if (route.match()) {
                this.current(route);
                return route;
            }
        }
        return this.current();
    }
}
