class Route {
    /**
     * @type {string}
     * @private
     */
    _alias = '';

    /**
     * @type {string}
     * @private
     */
    _pattern = '';

    /**
     * @type {Array}
     * @private
     */
    _callbacks = [];

    /**
     * @param alias
     * @param pattern
     */
    constructor(alias, pattern) {
        this._alias = alias;
        this._pattern = pattern;
    }

    /**
     * @returns {string}
     */
    get alias() {
        return this._alias;
    }

    /**
     * @returns {string}
     */
    get pattern() {
        return this._pattern;
    }

    /**
     * @param callback
     * @returns {Route}
     */
    subscribe(callback:Function) {
        this._callbacks.push(callback);
        return this;
    }

    /**
     * @returns {Route}
     */
    fire() {
        for (var cb of this._callbacks) {
            cb();
        }
        return this;
    }
}

export default class Router {
    /**
     * @type {KnockoutObservable<null|Route>}
     */
    current = ko.observable(null);

    /**
     * @type {Array|Route[]}
     * @private
     */
    _routes = [];

    /**
     * @constructor
     */
    constructor() {
        this.current.subscribe(value => {
            if (value !== null) {
                value.fire();
            }
        });
    }

    /**
     * @param alias
     * @param attributes
     * @returns {Route}
     */
    add(alias, attributes = {}) {
        var pattern = laroute.route(alias, attributes);

        var instance = new Route(alias, pattern);

        this._routes.push(instance);

        return instance;
    }

    /**
     * @param alias
     * @returns {*}
     */
    get(alias) {
        for (var route of this._routes) {
            if (route.alias === alias) {
                return route;
            }
        }
        return null;
    }

    /**
     * @param alias
     * @param attributes
     */
    to(alias, attributes = {}) {
        this.current(this.get(alias));

        var addr = laroute.route(alias, attributes);

        history.pushState({
            name:  this.current().alias,
            route: this.current().pattern
        }, addr, addr);
    }

    /**
     * @returns {string}
     */
    get path() {
        return document.location.pathname;
    }

    /**
     * @returns {Router}
     */
    match() {
        for (var route of this._routes) {
            var isValid = this.path.match(new RegExp(`^${route.pattern}$`), 'g');
            if (isValid) {
                this.current(route);
                return this;
            }
        }

        return this;
    }
}