import Pimple from "/Kernel/Pimple";

/**
 * Application
 */
export default class Application extends Pimple {
    constructor() {
        super();

        // Components
        this.set('app', app => this);
        this.set('views', app => this.include('Kernel/ViewsRepository'));
        this.register(this.include('ServiceProviders/RouterServiceProvider'));


        // Repositories
        this.set('usersRepository', app => this.include('Entity/Repository/UserRepository'));
        this.set('achievementsRepository', app => this.include('Entity/Repository/AchieveRepository'));
    }

    /**
     * @returns {Application}
     */
    run() {
        ko.punches.enableAll();

        this.views.search(document);

        this.route.match();

        document.addEventListener('load', document.body.classList.add('loaded'));

        return this;
    }

    /**
     * @param module
     * @returns {*}
     */
    include(module) {
        return new (require(module).default)(this);
    }
}
