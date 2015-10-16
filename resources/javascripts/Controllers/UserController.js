import User from "/Models/User";
import Router from "/Lib/Router";
import BaseController from "/Controllers/BaseController";

/**
 *
 */
export default class UserController extends BaseController {
    /**
     * @type {string}
     */
    static route = 'user';

    /**
     * @type {User}
     */
    user = ko.observable(null);

    /**
     * @type {Function}
     */
    error = ko.observable(null);

    /**
     * @constructor
     */
    constructor() {
        super();
        this.error.subscribe(value => {
            if (value) {
                setTimeout(() => {
                    this.home();
                }, 2000);
            }
        });

        super.boot();
    }

    /**
     * Show event
     */
    onShow() {
        // Fix for window with empty args
        if (!this.args.user) {
            this.args.user = Router.current().matches[1];
        }

        User.ready(state => {
            this.error(null);
            this.user(null);

            var user = User
                .query()
                .find((user:User) => user.route === this.args.user)
                .first();

            if (!user) {
                this.error(`Страница ${this.args.user} не найдна.`);

            } else {
                try {
                    user.loadProfile()
                        .then((user:User) => this.user(user));

                } catch (e) {
                    console.log(e);
                    this.error(`Пользователь ${this.args.user} не найден.`)
                }
            }
        });
    }

    /**
     * Go to achievements page
     */
    achievements() {
        Router.get('achievements').move();
    }

    /**
     * Yankee go home!
     */
    home() {
        Router.get('home').move();
    }
}