import Model from "/Lib/Model";
import Collection from "/Lib/Collection";

/**
 * Achieve
 */
export default class Achieve extends Model {
    /**
     * @type {string}
     */
    static title   = 'Достижения';

    /**
     * @type {string}
     */
    static request = '/api/achievements.json';
}
