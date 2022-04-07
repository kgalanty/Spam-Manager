export default {
    namespaced: true,
    state: () => ({

    }),
    mutations: {
        // setFilters(state, filters) {
        //   state.filters = filters
        //   //  // Vue.set(state, 'chats', chats);
        //   //state.chats = chats
        // }
    },
    actions:
    {
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