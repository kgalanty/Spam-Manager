import requestHelper from "../../mixins/requestHelper"
import axios from "axios";
export default {
    namespaced: true,
    state: () => ({
        list: [],
        loading: false,
        page: 1,
        perPage: 20,
        total: 0,
    }),
    mutations: {
        setQueue(state, queue) {
            state.list = queue
        },
        setLoading(state, loading) {
            state.loading = loading
        },
        setPage(state, page) {
            state.page = page
        },
        setPerPage(state, perPage) {
            state.perPage = perPage
        },
        setTotal(state, total) {
            state.total = total
        },
    },
    actions:
    {
        getQueue(context) {
            const params = requestHelper.generateParamsForRequest('QueuesList', [`page=${context.state.page}`,
            `perpage=${context.state.perPage}`])
            context.commit('setLoading', true)
            return new Promise((resolve, reject) => {
                axios.get('addonmodules.php?' + params)
                    .then((resp) => {
                        context.commit('setLoading', false)
                        if (resp.data.queue != []) {
                            context.commit('setQueue', resp.data.queueslist)
                            context.commit('setTotal', resp.data.total)
                            resolve()
                        }
                        else {
                            reject('Queue is empty')
                        }
                    })
                    .catch(error => reject(error));
            })
        }
    }
}