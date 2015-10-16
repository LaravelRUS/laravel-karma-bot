import Router from "/Lib/Router";

/**
 * Header breadcrumbs
 */
export default class HeaderController {
    /**
     * Breadcrumbs title
     */
    title = ko.observable(false);

    /**
     * Dropdown visibility
     */
    dropdown = ko.observable(false);

    /**
     * @constructor
     */
    constructor() {
        var title        = document.querySelector('title');
        var defaultTitle = title.innerHTML.trim();

        this.title.subscribe(value => {
            title.innerHTML = value
                ? value
                : defaultTitle;
        });

        Router.subscribe(route => {
            switch (route.name) {
                case 'user':
                    this.title('Профиль @' + route.matches[1]);
                    break;
                case 'achievements':
                    this.title('Достижения');
                    break;
                default:
                    this.title(false);
            }
        });
    }

    /**
     * @returns {boolean}
     */
    toggle() {
        this.dropdown(!this.dropdown());
        return true;
    }

    /**
     * @returns {boolean}
     */
    achievements() {
        Router.get('achievements').move();
        return false;
    }

    /**
     * @returns {boolean}
     */
    home() {
        Router.get('home').move();
        return false;
    }
}