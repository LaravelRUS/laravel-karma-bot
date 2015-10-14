export default class Serialize {
    /**
     * @param value
     * @returns {string}
     */
    static objectToString(value) {
        if (value instanceof Array) {
            return Serialize.arrayToString(value);
        }

        if (value instanceof Function) {
            return value.name;
        }

        if (typeof value === 'object') {
            var name = value.constructor.name;
            if (name !== 'Object') {
                return name;
            }

            var result = [];
            Object.keys(value).forEach(function (key) {
                result.push(key + ': ' + value[key].toString());
            });
            return '{' + result.join(', ') + '}';

        }
        return value;
    }

    /**
     * @param result
     * @param before
     * @param after
     * @returns {string}
     */
    static arrayToString(result = [], before = '', after = '') {
        return result
            .map(function (item) {
                return before + Serialize.objectToString(item) + after;
            })
            .join(', ');
    }
}

