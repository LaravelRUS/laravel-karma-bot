import Achieve from "/Entity/Achieve";
import AjaxRepository from "/Entity/Repository/AjaxRepository";

export default class AchieveRepository extends AjaxRepository {
    /**
     * @returns {*|Array}
     */
    async all() {
        var result = await this.request(laroute.route('api.achievements'));
        return this.transform(result, Achieve, 'data');
    }

    /**
     * @param name
     * @returns {*|Array}
     */
    async find(name) {
        var result = await this.request(laroute.route('api.achieve.users', {name: name}));
        return this.transform(result, Achieve, 'data');
    }
}