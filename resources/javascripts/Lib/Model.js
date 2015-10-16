import Request from "/Lib/Request";
import Collection from "/Lib/Collection";


/**
 *
 */
export default class Model {
    /**
     * @type {array}
     */
    static modelsLoaded = [];

    /**
     * @returns {*}
     */
    static get loaded() {
        if (!Model.modelsLoaded[this.name]) {
            Model.modelsLoaded[this.name] = ko.observable(false);
        }
        return Model.modelsLoaded[this.name];
    }

    /**
     * @type {array}
     */
    static modelsBooted = [];

    /**
     * @returns {*}
     */
    static get booted() {
        if (!Model.modelsBooted[this.name]) {
            Model.modelsBooted[this.name] = ko.observable(false);
        }
        return Model.modelsBooted[this.name];
    }

    /**
     * @type {Array}
     */
    static modelsCollection = [];

    /**
     * @returns {*}
     */
    static get collection() {
        if (!Model.modelsCollection[this.name]) {
            Model.modelsCollection[this.name] = new Collection([]);
        }
        return Model.modelsCollection[this.name];
    }

    /**
     * @param {Collection} collection
     */
    static set collection(collection: Collection) {
        Model.modelsCollection[this.name] = collection;
    }

    /**
     * Boot model
     */
    static boot() {
        if (!this.booted()) {
            // Переносим методы коллекции в модель
            for (var key in this.collection) {
                if (this.collection[key] instanceof Function) {
                    (method => {
                        Object.defineProperty(this, method, {
                            enumerable: false,
                            configurable: false,
                            get: () => this.collection[method]
                        });
                    })(key);
                }
            }

            this.booted(true);
        }
    }

    /**
     * @returns {Model}
     */
    static async load() {
        this.boot();

        if (!this.loaded()) {
            try {
                var items = await (new Request(this.request))
                    .get(`Загрузка данных ${this.title}`);

                items.forEach(item => {
                    var instance = new this(item);
                    this.collection.add(instance);
                });

                this.loaded(true);

            } catch (e) {
                throw new Error(e.message);
            }
        }

        return this;
    }

    /**
     * @param callback
     */
    static ready(callback) {
        this.boot();

        (callback => {
            if (this.loaded()) {
                callback(this);
            }
            var subscription = this.loaded.subscribe(state => {
                callback(this);
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
        this.constructor.boot();

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
