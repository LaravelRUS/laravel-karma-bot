"use strict";

/** Declaration types */
type ServiceDeclaration  = Function|Object;
type ProviderDeclaration = Function|ServiceProvider;

/**
 * Reserved names of properties
 * @type {string[]}
 */
const reservedProperties = [
    'get', 'set', 'factory', 'raw',
    'protect', 'share', 'toString', 'constructor',
    'prototype'
];


/**
 * Service provider class for service injecting in Pimple container
 */
export class ServiceProvider {
    /**
     * @param {Pimple} container
     * @return {*}
     */
    register(container: Pimple): any {
        throw new TypeError('Method register is abstract and must be implement in child class.');
    }
}


/**
 * Pimple dependency injection container
 *
 * @copyright 2011 M.PARAISO <mparaiso@online.fr>
 * @copyright 2016 SerafimArts <nesk@xakep.ru>
 * @license LGPL
 * @version 3.0.0
 */
export default class Pimple {
    /**
     * @type {string}
     */
    static get VERSION() {
        return '3.0.0';
    }

    /**
     * @type {{}}
     * @private
     */
    _definitions = {};

    /**
     * @type {{}}
     * @private
     */
    _raw = {};

    /**
     * Constructor
     * @param {Object} services
     */
    constructor(services = {}) {
        Object.keys(services).forEach(function (service) {
            this.set(service, services[service]);
        }, this);
    }

    /**
     * Define a service
     *
     * @param {string} name
     * @param {Object|Function} service
     * @return {Pimple}
     */
    set(name: String, service: ServiceDeclaration): Pimple {
        this._raw[name] = service;

        this._definitions[name] = service instanceof Function ?
            (function () {
                var cached;
                return pimple => {
                    if (cached === undefined) {
                        cached = service(pimple);
                    }
                    return cached;
                };
            }()) : service;

        try {
            if (reservedProperties.indexOf(name) === -1) {
                Object.defineProperty(this, name, {
                    get: function () {
                        return this.get(name);
                    }
                });
            }
        } catch (e) {
            console.error(e);
        }
        return this;
    }

    /**
     * Register a factory
     *
     * @param {string} name
     * @param {Function} callback
     * @return {Pimple}
     */
    factory(name: String, callback: Function): Pimple {
        this._raw[name] = callback;
        this._definitions[name] = callback;

        try {
            if (reservedProperties.indexOf(name) === -1) {
                Object.defineProperty(this, name, {
                    get: function () {
                        return this.get(name);
                    }
                });
            }
        } catch (e) {
            console.error(e);
        }

        return this;
    }

    /**
     * Get a service instance
     * @param {string} name
     * @return {*}
     */
    get(name: String): any {
        if (this._definitions[name] instanceof Function) {
            return this._definitions[name](this);
        }
        return this._definitions[name];
    }

    /**
     * Register a protected function
     * @param {Function} service
     * @returns {Function}
     */
    protect(service: Function): Function {
        return function () {
            return service;
        };
    }

    /**
     * Extend a service
     * @param {string} serviceName
     * @param {Function} service
     * @returns {Function}
     */
    extend(serviceName: String, service: Function): Function {
        if (!this._definitions[serviceName]) {
            throw new RangeError(`Definition with "${serviceName}" not defined in container.`);
        }

        var def = this._definitions[serviceName];

        return function (container) {
            if (def instanceof Function) {
                def = def(container);
            }
            return service(def, container);
        };
    }

    /**
     * Get a service raw definition
     * @param {string} name
     * @return {Function}
     */
    raw(name: String): Function {
        return this._raw[name];
    }

    /**
     * Register a service provider
     * @param {Function|ServiceProvider} provider
     * @returns {Pimple}
     */
    register(provider: ProviderDeclaration): Pimple {
        switch (true) {
            case provider instanceof ServiceProvider || provider.register instanceof Function:
                provider.register(this);
                break;

            case provider instanceof Function:
                provider(this);
                break;
        }

        return this;
    }
}