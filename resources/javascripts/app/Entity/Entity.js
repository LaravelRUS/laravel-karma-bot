export default class Entity {
    constructor(attributes = {}) {
        for (let key of Object.keys(attributes)) {
            Object.defineProperty(this, key, {
                get: () => attributes[key]
            });
        }
    }
}