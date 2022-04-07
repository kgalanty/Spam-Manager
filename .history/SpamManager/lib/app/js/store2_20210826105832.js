
const store = new Vuex.Store({
    modules: {
        // regions: RegionsStore,
        // groups: GroupsStore
      },
    state: {
        // servers: [],
        // pools: [],
         cancelTokens: [],
         supervisors: [],
         customoffers: [],
         pgroups: [],
         results:
         {
             vip:
             {
                total: 0, data: []
             },
             vps:
             {
                total: 0, data: []
             },
             shared:
             {
                total: 0, data: []
             },
             extra:
             {
                total: 0, data: []
             },

         },
         vipresults: {total: 0, data: []},
         loadings: {},
         pages: {
             vip:
             {
                page:1,
                perPage: 10
            },
            shared:
            {
               page:1,
               perPage: 10
           },
           vps:
           {
              page:1,
              perPage: 10
          },
          extra:
          {
             page:1,
             perPage: 10
         }
         }
    },
    actions: {
        refreshProductGroups(context)
        {
            axios
            .get('addonmodules.php?module=ChatManager&c=ProductGroups&json=1')
            .then(response => {
                //this.api = response.data;
                context.commit('setProductGroups', response.data.data);
            })
        },
        refreshSupervisors(context) {
            axios
                .get('addonmodules.php?module=ChatManager&c=Supervisors&json=1')
                .then(response => {
                    //this.api = response.data;
                    context.commit('setSupervisors', response.data.data);
                })
        },
        refreshCustomOffers(context) {
            axios
                .get('addonmodules.php?module=ChatManager&c=CustomOffers&json=1')
                .then(response => {
                    //this.api = response.data;
                    context.commit('setOffers', response.data.data);
                })
        },
        CANCEL_PENDING_REQUESTS(context) {

            // Cancel all request where a token exists
            context.state.cancelTokens.forEach((request, i) => {
                if(request.cancel){
                    request.cancel();
                }
            });

            // Reset the cancelTokens store
            context.commit('CLEAR_CANCEL_TOKENS');
        },
        refreshVip(context) {
            context.commit('setLoadings', {vip: true})
            const params = [
             `module=ChatManager`,
             `c=Request`,
             `json=1`,
             `page=${context.state.pages.vip.page}`,
             `perpage=${context.state.pages.vip.perPage}`,
             `type=vip`
           ].join("&");
           return axios.get(`addonmodules.php?${params}`)
             .then((response) => {
               if (response.data) {
                context.commit('setVip', response.data);
                context.commit('setLoadings', {vip: false})
                // this.total = response.data.total
               }
             });
         },
         refreshVPS(context) {
            context.commit('setLoadings', {vps: true})
            const params = [
             `module=ChatManager`,
             `c=Request`,
             `json=1`,
             `page=${context.state.pages.vps.page}`,
             `perpage=${context.state.pages.vps.perPage}`,
             `type=vps`
           ].join("&");
           return axios.get(`addonmodules.php?${params}`)
             .then((response) => {
               if (response.data) {
                context.commit('setVPS', response.data);
                context.commit('setLoadings', {vps: false})
                // this.total = response.data.total
               }
             });
         },
         refreshShared(context) {
            context.commit('setLoadings', {shared: true})
            const params = [
             `module=ChatManager`,
             `c=Request`,
             `json=1`,
             `page=${context.state.pages.shared.page}`,
             `perpage=${context.state.pages.shared.perPage}`,
             `type=shared`
           ].join("&");
           return axios.get(`addonmodules.php?${params}`)
             .then((response) => {
               if (response.data) {
                context.commit('setShared', response.data);
                context.commit('setLoadings', {shared: false})
                // this.total = response.data.total
               }
             });
         },
         refreshExtra(context) {
            context.commit('setLoadings', {extra: true})
            const params = [
             `module=ChatManager`,
             `c=Request`,
             `json=1`,
             `page=${context.state.pages.extra.page}`,
             `perpage=${context.state.pages.extra.perPage}`,
             `type=extra`
           ].join("&");
           return axios.get(`addonmodules.php?${params}`)
             .then((response) => {
               if (response.data) {
                context.commit('setExtra', response.data);
                context.commit('setLoadings', {extra: false})
                // this.total = response.data.total
               }
             });
         },

    },
    getters: {
        cancelTokens(state) {
            return state.cancelTokens;
        }
    },
    mutations: {
        setSupervisors(state, supervisors)
        {
            state.supervisors = supervisors
        },
        setProductGroups(state, pgroups)
        {
            state.pgroups = pgroups
        },
        setOffers(state, supervisors)
        {
            state.customoffers = supervisors
        },
        ADD_CANCEL_TOKEN(state, token) {
            state.cancelTokens.push(token);
        },
        CLEAR_CANCEL_TOKENS(state) {
            state.cancelTokens = [];
        },
        setVip(state, results)
        {
            state.results.vip = results
        },
        setVPS(state, results)
        {
            state.results.vps = results
        },
        setShared(state, results)
        {
            state.results.shared = results
        },
        setExtra(state, results)
        {
            state.results.extra = results
        },
        setLoadings(state, loading)
        {
            state.loadings = {...state.loadings, ...loading}
        },
        setPages(state, pages)
        {
            state.pages = {...state.pages, ...pages}
        },

        // setServers(state, servers) {
        //     state.servers = servers;
        // },
        // setPools(state, pools) {
        //     state.pools = pools
        // },
        // setPoolAsPrimary(state, pool_id) {
        //     for (let pool of state.pools) {
        //         if (pool.id === pool_id) {
        //             pool.primary = 1
        //         }
        //         else {
        //             pool.primary = 0
        //         }
        //     }
        // },
        // setPoolLogic(state, pooldata) {
        //     for (let pool of state.pools) {
        //         if (pool.id === pooldata.id) {
        //             pool.logic = pooldata.logic
        //         }

        //     }
        // }
    }
});