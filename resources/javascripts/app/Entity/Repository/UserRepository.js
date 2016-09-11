import User from "/Entity/User";
import AjaxRepository from "/Entity/Repository/AjaxRepository";

export default class UserRepository extends AjaxRepository {
    async search(query) {
        var result = await this.request(laroute.route('api.users.search', {'query': query}));
        return this.transform(result, User, 'data');

    }

    async top() {
        var result = await this.request(laroute.route('api.top'));
        return this.transform(result, User, 'data');
    }
}
