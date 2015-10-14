import Model from "/Lib/Model";
import User from "/Models/User";

export default class Karma extends Model {
    constructor(properties = {}) {
        properties.user = User.find(u => u.id === properties.user_id).first();
        super(properties);
    }
}
