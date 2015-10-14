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
        this.router.add('/', 'home');
        this.router.add('/{user}', 'user').where('user', '.*?');
        this.router.match();

        this.searchControllers();

        User.load();
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
            ko.applyBindings(new controller(data.node), data.node);
        });

        this.controllers = [];

        return this;
    }
}
