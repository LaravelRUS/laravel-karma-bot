export default class AjaxRepository {
    async _get(url) {
        var headers = new Headers();
        headers.append('Accept', 'application/json');

        return await (await fetch(url, {
            method: 'GET',
            cache: false,
            headers: headers
        })).json();
    }

    async _request(url, method = 'POST', body) {
        var headers = new Headers();
        headers.append('Accept', 'application/json');
        headers.append('Content-Type', 'application/json');

        return await (await fetch(url, {
            method: method.toUpperCase(),
            cache: false,
            headers: headers,
            body: body
        })).json();
    }

    /**
     * @param url
     * @param body
     * @param method
     */
    async request(url, body = null, method = 'GET') {
        var result = [];

        if (method === 'GET' && body === null) {
            result = await this._get(url);
        } else {
            if (method === 'GET') {
                method = 'POST';
            }

            result = await this._request(url, method, body);
        }

        return result;
    }

    /**
     * @param data
     * @param entity
     * @param field
     * @returns {*|Array}
     */
    transform(data, entity, field = null) {
        return (field ? data[field] : data).map(item => new entity(item));
    }
}