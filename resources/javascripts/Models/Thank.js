import Model from "/Lib/Model";
import User from "/Models/User";

export default class Thank extends Model {
    constructor(properties = {}) {
        properties.user = User.find(u => u.id === properties.user_target_id).first();
        super(properties);
    }
}
