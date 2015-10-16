import Achieve from "/Models/Achieve";
import BaseController from "/Controllers/BaseController";

/**
 *
 */
export default class AchievementsController extends BaseController {
    /**
     * @type {string}
     */
    static route = 'achievements';

    /**
     * @type {*}
     */
    achievements = ko.observableArray([]);

    /**
     * @constructor
     */
    constructor() {
        super();
        super.boot();
    }

    /**
     * Show
     */
    onShow() {
        Achieve.load().then((model: Achieve) => {
            this.achievements(model.toArray());
        });
    }
}