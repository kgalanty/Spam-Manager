import requestHelper from "../../mixins/requestHelper"
import axios from "axios";
export default {
    namespaced: true,
    state: () => ({
        servers: []
    }),
    mutations: {
        // setFilters(state, filters) {
        //   state.filters = filters
        //   //  // Vue.set(state, 'chats', chats);
        //   //state.chats = chats
        // }
        setServers(state, servers) {
            state.servers = servers
        }
    },
    actions:
    {
        getServers(context) {
            const params = requestHelper.generateParamsForRequest('Servers')
            return new Promise((resolve, reject) => {
               axios.get('addonmodules.php?' + params)
                    .then((resp) => {
                        if(resp.data.templates != []){
                            context.commit('setServers', resp.data.servers)
                            resolve()
                        }
                        else
                        {
                            reject('No Servers found')
                        }
                    })
            })
        }
        // switchStyle(context, payload)
        // {
        //   window.localStorage.setItem('darktheme', payload)
        //   context.commit('setDarkstyle', payload)
        // },
        // getPermissions(context) {
        //   return new Promise((resolve, reject) => {
        //     if(context.state.groupMember > 0 )
        //     {
        //       resolve()
        //       return
        //     }
        //    const params = requestsMixin.methods.generateParamsForRequest('Auth', [`a=readPermissions`])

        //     axios
        //       .get('addonmodules.php?' + params)
        //       .then((response) => {
        //         if (response) {

        //           context.commit('setGroupMember', response.data)
        //           resolve();
        //           return

        //         }
        //         reject('No response received')
        //       }
        //       )
        //   });
        // },

    }
}