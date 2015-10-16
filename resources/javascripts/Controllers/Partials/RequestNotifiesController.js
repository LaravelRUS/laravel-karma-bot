import Request from "/Lib/Request";

/**
 *
 */
export default class RequestNotifiesController {
    /**
     * @constructor
     */
    constructor() {
        this.messages = Request.messages;
    }
}
