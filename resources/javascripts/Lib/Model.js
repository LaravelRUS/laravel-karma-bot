import Request from "/Lib/Request";
import Collection from "/Lib/Collection";


/**
 *
 */
export default class Model {
    /**
     * @type {boolean}
     */
    static loaded = ko.observable(false);

    /**
     * @type {Collection}
     */
    static collection = new Collection([]);

    /**
     * @returns {Collection}
     */
    static load() {

        if (!this.loaded()) {
            for (var key in this.collection) {
                if (this.collection[key] instanceof Function) {
                    (method => {
                        Object.defineProperty(this, method, {
                            enumerable:   false,
                            configurable: false,
                            get:          () => this.collection[method]
                        });
                    })(key);
                }
            }

            try {
                (new Request(this.request))
                    .get(`Загрузка данных ${this.name}`)
                    .then(items => {
                        items.forEach(item => {
                            var instance = new this(item);
                            this.collection.add(instance);
                        });

                        this.loaded(true);
                    });

            } catch (e) {
                throw new Error(e.message);
            }
        }

        return this.collection;
    }

    /**
     * @param callback
     */
    static ready(callback) {
        (callback => {
            if (this.loaded()) {
                callback();
            }
            var subscription = this.loaded.subscribe(state => {
                callback();
                subscription.dispose();
            });
        })(callback);
    }

    /**
     * @returns {*}
     */
    static [Symbol.iterator]() {
        return this.collection[Symbol.iterator]();
    }

    /**
     * @returns {Collection}
     */
    static query() {
        return this.collection;
    }

    /**
     * @type {{}}
     */
    properties = {};

    /**
     * @constructor
     * @param properties
     */
    constructor(properties = {}) {
        this.properties = properties;

        if (properties.created_at) {
            properties.created_at = new Date(properties.created_at.date);
        }

        if (properties.updated_at) {
            properties.updated_at = new Date(properties.updated_at.date);
        }

        for (var key in properties) {
            (property => {
                Object.defineProperty(this, property, {
                    enumerable:   false,
                    configurable: false,
                    get:          (() => this.get(property)),
                    set:          (value => this.set(property, value))
                });
            })(key.toString());
        }
    }

    /**
     * @param key
     * @returns {*|null}
     */
    get(key) {
        return this.properties[key] || null;
    }

    /**
     * @param key
     * @param value
     * @returns {Model}
     */
    set(key, value) {
        if (this.properties[key]) {
            this.properties[key] = value;
        }
        return this;
    }

    /**
     * @returns {*}
     */
    [Symbol.iterator]() {
        return this.constructor.collection[Symbol.iterator]();
    }

    /**
     * @returns {{}}
     */
    toObject() {
        return this.properties;
    }

    /**
     * @return {string}
     */
    toString() {
        return JSON.stringify(this.properties);
    }
}
