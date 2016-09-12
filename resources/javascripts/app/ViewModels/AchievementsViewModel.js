export default class AchievementsViewModel {
    /**
     * @type {KnockoutObservableArray<T>}
     */
    achievements = ko.observableArray([]);

    /**
     * @type {Pimple}
     */
    app = null;

    /**
     * @type {KnockoutObservable<T>}
     */
    loading = ko.observable(false);

    /**
     * @param {Pimple} app
     */
    constructor(app) {
        this.app = app;
    }

    /**
     * @returns void
     */
    onShow() {
        this.loading(true);
        this.app.achievementsRepository.all().then(list => {
            this.achievements(list);
            this.loading(false);
        })
    }
}