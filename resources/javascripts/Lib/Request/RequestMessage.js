/**
 * Message
 */
export default class RequestMessage {
    /**
     * @type {number}
     */
    static id = 0;

    /**
     * @type {string}
     */
    text = '';

    /**
     * @type {bool}
     */
    visible = ko.observable(false);

    /**
     * @type {Array}
     */
    events = [];

    /**
     * @constructor
     * @param text
     */
    constructor(text) {
        this.id = RequestMessage.id++;
        this.text = text;
        this.visible(false);

        setTimeout(() => {
            this.visible(true);
        }, 200);

        this.visible.subscribe(state => {
            if (!state) { this.events.forEach((e) => e(this)); }
        });
    }

    /**
     * @param callable
     * @returns {RequestMessage}
     */
    onHide(callable) {
        this.events.push(callable);
        return this;
    }
}