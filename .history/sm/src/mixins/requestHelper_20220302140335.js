export default {
    generateParamsForRequest(controller, params = []) {
        return [`module=SpamManager`, `c=${controller}`, `json=1`].concat(params).join("&");
    },
}
