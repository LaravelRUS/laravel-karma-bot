const NODE_QUERY = 'data-controller';
const NODE_LOADED = 'data-loaded';

class View {
    /**
     * @type {Pimple}
     * @private
     */
    _container = null;

    /**
     * @type {Function}
     * @private
     */
    _controller = null;

    /**
     * @type {HTMLElement}
     * @private
     */
    _node = null;

    /**
     * @type {String}
     * @private
     */
    _name = null;

    /**
     * @type {null}
     * @private
     */
    _instance = null;

    /**
     * @param {Pimple} container
     * @param {Function} controller
     * @param {HTMLElement} node
     * @param {String} name
     */
    constructor(container, controller, node, name) {
        this._container = container;
        this._node = node;
        this._name = name;
        this._controller = controller;


        this._controller.prototype.visible = ko.observable(false);
    }

    /**
     * @returns {Function}
     */
    get class() {
        return this._controller;
    }

    /**
     * @returns {void}
     */
    build() {
        this._instance = new this._controller(this._container);
        ko.applyBindings(this._instance, this._node);
    }

    /**
     * @returns {View}
     */
    show(...args) {
        this._instance.visible(true);

        if (this._instance.onShow) {
            this._instance.onShow(...args);
        }

        return this;
    }

    /**
     * @returns {View}
     */
    hide(...args) {
        this._instance.visible(false);

        if (this._instance.onHide) {
            this._instance.onHide(...args);
        }

        return this;
    }

    /**
     * @param value
     * @param args
     * @returns {View}
     */
    setVisibility(value, ...args) {
        if (value) {
            this.show(...args);
        } else {
            this.hide(...args);
        }
        return this;
    }
}


export default class ViewsRepository {
    /**
     *
     */
    _views = {};

    /**
     * @type {Pimple}
     * @private
     */
    _container = null;

    /**
     * @type {{add: Array}}
     * @private
     */
    _events = {
        add: []
    };

    /**
     * @param container
     */
    constructor(container) {
        this._container = container;
    }

    /**
     * @param callback
     * @returns {ViewsRepository}
     */
    onAdd(callback) {
        this._events.add.push(callback);
        return this;
    }

    /**
     * @param root
     */
    search (root) {
        root.querySelectorAll(`[${NODE_QUERY}]`).forEach(node => {
            if (!node.hasAttribute(NODE_LOADED)) {
                var name = node.getAttribute(NODE_QUERY);

                var controller = null;
                try {
                    controller = require(name).default;
                } catch (e) {
                    controller = (app => {});
                    console.error(`Error while loading controller ${name}`);
                }

                try {
                    this._views[name] = new View(this._container, controller, node, name);

                    for (var cb of this._events.add) {
                        cb(this._views[name]);
                    }

                    this._views[name].build();

                    node.setAttribute(NODE_LOADED, NODE_LOADED);

                } catch (e) {
                    console.error(`Error in controller ${name}`);
                    console.error(e);
                }
            }
        });
    }

    /**
     * @param name
     * @returns {*}
     */
    get(name) {
        return this._views[name];
    }
}