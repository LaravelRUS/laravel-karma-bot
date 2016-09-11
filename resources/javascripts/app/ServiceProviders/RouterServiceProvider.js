import Router from "/Kernel/Router";
import {ServiceProvider} from "/Kernel/Pimple";


export default class RouterServiceProvider extends ServiceProvider {
    /**
     * @type {{search: string, achievements: string}}
     */
    pages = {
        'search': 'ViewModels/SearchViewModel',
        'achievements': 'ViewModels/AchievementsViewModel',
        'user': null,
    };

    /**
     * @type {Pimple}
     */
    app = null;

    /**
     * @param {Pimple} app
     */
    register(app) {
        this.app = app;

        var router = new Router;

        router.add('home')
            .subscribe(route => this.show('search'));

        router.add('user', {'user': '.*?'})
            .subscribe(route => this.show('user'));

        router.add('achievements')
            .subscribe(route => this.show('achievements'));


        app.views.onAdd(view => {
            view.class.prototype.route = (action, args) => {
                router.to(action, args);
                return false;
            };
        });

        app.set('route', app => router);
    }

    /**
     * @param name
     * @returns {RouterServiceProvider}
     */
    show(name) {
        for (var alias of Object.keys(this.pages)) {
            if (this.pages[alias] === null) {
                continue;
            }

            this.app.views.get(this.pages[alias]).setVisibility(alias === name);
        }
        return this;
    }
}