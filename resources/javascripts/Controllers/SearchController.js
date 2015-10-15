import User from "/Models/User";
import Config from "/Lib/Config";
import Router from "/Lib/Router";
import Request from "/Lib/Request";
import BaseController from "/Controllers/BaseController";

/**
 *
 */
export default class SearchController extends BaseController {
    /**
     * @type {string}
     */
    static route = 'home';

    /**
     *
     */
    constructor() {
        super();

        this.query  = ko.observable('');
        this.users = ko.observableArray([]);

        this.query.subscribe(query => {
            query = query.toString().trim();

            if (query.length) {
                var users = User.search(query)
                    .each((user:User) => {
                        user.doHighlight(query);
                    })
                    .toArray();


                this.users(users);
            } else {
                this.users([]);
            }
        });

        super.boot();
    }

    /**
     * @param user
     */
    load(user: User) {
        user.profile();
        return true;
    }
}