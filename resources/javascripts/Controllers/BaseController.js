import Router, {Route} from "/Lib/Router";

/**
 * Base Controller
 */
export default class BaseController {
    /**
     * @type {Function}
     */
    visible = ko.observable(false);

    /**
     * @type {{}}
     */
    args = {};

    /**
     * Boot controller
     */
    boot() {
        this.matchRouter();

        if (!this.args) {
            this.args = {};
        }

        this.visible.subscribe(state => {
            if (state && this.onShow) {
                this.onShow(this.args);
            }
        });

        if (this.visible() && this.onShow) {
            this.onShow(this.args);
        }
    }

    /**
     * Router matches and event subscribe
     */
    matchRouter() {
        Router.subscribe((route:Route) => {
            this.onRouteChange(route);
        });
    }

    /**
     * @param route
     */
    onRouteChange(route:Route) {
        this.visible(route.name === this.constructor.route);
        this.args = route.getStateArguments();
    }
}
