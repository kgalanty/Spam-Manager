import requestHelper from "../../mixins/requestHelper"
import axios from "axios";
export default {
    namespaced: true,
    state: () => ({
        queue: [],
        loading: false,
        page: 1,
        perPage: 50,
        total: 0,
    }),
    mutations: {
        setQueue(state, queue) {
            state.queue = queue
        },
        setLoading(state, loading) {
            state.loading = loading
        }
    },
    actions:
    {
        getQueue(context) {
            const params = requestHelper.generateParamsForRequest('Queue')
            context.commit('setLoading', true)
            return new Promise((resolve, reject) => {
                axios.get('addonmodules.php?' + params)
                    .then((resp) => {
                        context.commit('setLoading', false)
                        if(resp.data.queue != []){
                            context.commit('setQueue', resp.data.queue)
                            resolve()
                        }
                        else
                        {
                            reject('No emails found')
                        }
                    })
                    .catch(error =>  reject(error));
            })
        }
    }
}