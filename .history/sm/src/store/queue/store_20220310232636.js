import requestHelper from "../../mixins/requestHelper"
import axios from "axios";
export default {
    namespaced: true,
    state: () => ({
        queue: []
    }),
    mutations: {
        setQueue(state, queue) {
            state.queue = queue
        }
    },
    actions:
    {
        getQeueue(context) {
            const params = requestHelper.generateParamsForRequest('Queue')
            return new Promise((resolve, reject) => {
                axios.get('addonmodules.php?' + params)
                    .then((resp) => {
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