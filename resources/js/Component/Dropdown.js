import Template from "../Decorators/Template";
import Component from "../Decorators/Component";


@Component('dropdown')
@Template(`
    <div class="dropdown {{ visible() ? 'active' : '' }}" data-bind="click: toggle">
        {{ title }}
        
        <nav data-bind="foreach: links">
            <a href="{{ href }}">{{ title }}</a>
        </nav>
    </div>
`)
export default class Dropdown {
    /**
     * @type {string}
     */
    title = 'Dropdown';

    /**
     * @type {Array}
     */
    links = [];

    /**
     * @type {KnockoutObservable<T>}
     */
    visible = ko.observable(false);

    constructor(params) {
        this.title = params.title || this.title;

        for (let link of Object.keys(params.links || {})) {
            this.links.push({ title: link, href: params.links[link] });
        }
    }

    toggle() {
        this.visible(!this.visible());
    }
}