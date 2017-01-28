export default class ComponentRegistrar {
    /**
     * @param components
     */
    registerMany(components) {
        for (let component of components) {
            this.register(component);
        }
    }

    /**
     * @param component
     */
    register(component) {
        if (!component.$name) {
            throw new Error('Component has no name');
        }

        ko.components.register(component.$name, {
            viewModel: params => new component(params),
            template: component.$template || ''
        })
    }
}