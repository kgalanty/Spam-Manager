import requestHelper from "../../mixins/requestHelper"
export default {
    namespaced: true,
    state: () => ({
        templates: []
    }),
    mutations: {
        // setFilters(state, filters) {
        //   state.filters = filters
        //   //  // Vue.set(state, 'chats', chats);
        //   //state.chats = chats
        // }
        setTemplates(state, templates) {
            state.templates = templates
        }
    },
    actions:
    {
        getTemplates(context) {
            const params = requestHelper.generateParamsForRequest('Templates')
            return new Promise((resolve, reject) => {
                this.$api.get('addonmodules.php?' + params)
                    .then((resp) => {
                        if(resp.data.templates != []){
                            context.commit('setTemplates', resp.data.templates)
                            resolve()
                        }
                        else
                        {
                            reject('No templates found')
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