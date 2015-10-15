import Router from "/Lib/Router";

/**
 * Header breadcrumbs
 */
export default class HeaderController {
    /**
     * @constructor
     */
    constructor() {
        this.url = ko.observable(false);

        Router.subscribe(route => {
            if (route.name === 'user') {
                this.url(route.matches[1]);
            } else {
                this.url(false);
            }
        });
    }

    /**
     * @returns {boolean}
     */
    home() {
        Router.get('home').move();
        return false;
    }
}