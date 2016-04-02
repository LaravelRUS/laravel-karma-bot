import User from "/Models/User";
import Router from "/Lib/Router";

/**
 * Application
 */
export default class Application {
    /**
     * @type {Array}
     */
    controllers = [];

    /**
     * @type {Router}
     */
    router = Router;

    /**
     * @constructor
     */
    constructor() {
        this.bootRoutes();

        this.searchControllers();

        User.load()
            .then((model: User) => {
                User.collection = User.collection
                    .sort((user:User) => {
                        return parseInt(user.thanks_count) || 0;
                    }, -1)
                    .sort((user:User) => {
                        return parseInt(user.karma_count) || 0;
                    }, -1);
            })
    }

    bootRoutes() {
        this.router.add('/', 'home');
        this.router.add('/user/{user}', 'user').where('user', '.*?');
        this.router.add('/achievements', 'achievements');

        this.router.boot();
    }

    /**
     * @returns {Application}
     */
    searchControllers() {
        [].slice.call(document.querySelectorAll('[data-controller]'), 0).forEach(node => {
            this.addController(node, node.getAttribute('data-controller'));
        });
        return this;
    }

    /**
     * @param node
     * @param controller
     * @returns {Application}
     */
    addController(node, controller) {
        this.controllers.push({
            node: node,
            controller: controller
        });
        return this;
    }

    /**
     * Boot application
     *
     * @returns {Application}
     */
    run() {
        this.controllers.forEach(data => {
            var controller = require(data.controller.replace(/\\\/\./g, '/'));
            controller = controller.default || controller;
            ko.applyBindings(new controller(data.node), data.node);
        });

        this.controllers = [];

        return this;
    }
}
