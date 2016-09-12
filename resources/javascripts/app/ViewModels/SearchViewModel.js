class Search {
    /**
     * @type {KnockoutObservable<T>}
     */
    text = ko.observable('');

    /**
     * @type {string}
     * @private
     */
    _default = '';

    /**
     * @type {Array|Function[]}
     * @private
     */
    _callbacks = [];

    /**
     * @type {null|Number}
     * @private
     */
    _timeout = null;

    /**
     * @constructor
     */
    constructor() {
        this._default = this.text();

        this.text.subscribe(value => {
            if (this._timeout !== null) {
                clearTimeout(this._timeout);
            }

            this._timeout = setTimeout(() => {
                if (value.trim().length !== 0) {
                    history.pushState({}, value.trim(), laroute.route('home', {
                        query: encodeURIComponent(value.trim())
                    }));

                    for (var cb of this._callbacks) {
                        cb(value.trim());
                    }
                }

                this._timeout = null;
            }, 200);
        })
    }

    /**
     * @param callback
     * @returns {Search}
     */
    onChange(callback:Function) {
        this._callbacks.push(callback);
        return this;
    }

    /**
     * @returns {Search}
     */
    reset() {
        this.text(this._default);
        return this;
    }
}


export default class SearchViewModel {
    /**
     * @type {Pimple}
     */
    app = null;

    /**
     * @type {Search}
     */
    search = new Search();

    /**
     * @type {KnockoutObservableArray<User>}
     */
    found = ko.observableArray([]);

    /**
     * @type {KnockoutObservableArray<User>}
     */
    top = ko.observableArray([]);

    /**
     * @type {KnockoutObservable<T>}
     */
    searchLoading = ko.observable(false);

    /**
     * @type {KnockoutObservable<T>}
     */
    topLoading = ko.observable(false);

    /**
     * @param app
     */
    constructor(app) {
        this.app = app;

        this.search.text.subscribe(value => {
            this.found.removeAll();
        });

        this.search.onChange(value => {
            this.searchLoading(true);
            app.usersRepository.search(value)
                .then(items => {
                    this.searchLoading(false);
                    this.found(items);
                })
                .catch(e => {
                    this.searchLoading(false);
                    this.found.removeAll();
                })
        });
    }

    /**
     * @param query
     */
    onShow(query = null) {
        this.search.text(query || this.app.route.param('query'));

        this.topLoading(true);
        this.app.usersRepository.top()
            .then(users => {
                this.topLoading(false);
                this.top(users);
            })
            .catch(e => {
                this.topLoading(false);
                this.top.removeAll();
            });
    }
}