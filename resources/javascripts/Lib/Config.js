/**
 * Config
 */
export default class Config {
    /**
     * @type {*|{}}
     */
    static config = window.config || {};

    /**
     * @param key
     * @returns {*|null}
     */
    static get(key) {
        return this.config[key] || null;
    }
}
