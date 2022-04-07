const routes = [
  { path: '/', component: Home },
  { path: '/completed', component: Completed },
  {
    path: '/stats', component: Stats,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/statsbyagent', component: StatsByAgent,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/personalstats', component: PersonalStats
  },
  {
    path: '/statsextraserv', component: StatsExtra,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/statsbyagent/:id', component: StatsAgent,
    meta: {
      requiresAuth: true
    }
  },
  {
    name: "Refunds", path: '/refunds', component: Refunds,
    meta: {
      requiresAuth: true
    }
  }
]
// const params = [
//   `module=ChatManager`,
//   `c=Auth`,
//   `json=1`,
// ].join("&");
// axios.get(`addonmodules.php?${params}`)
//   .then((response) => {
//     if(response.data.stats === 1)
//     {
//       routes.push( { path:'/stats', component: Stats},
//       { path:'/statsbyagent', component: StatsByAgent },
//       { path:'/statsextraserv', component: StatsExtra })
//     }
//   });

const router = new VueRouter({
  routes
})

router.beforeEach(async (to, from, next) => {

  if (to.matched.some(r => r.meta.requiresAuth)) {
    var result = await store.dispatch('checkPerms')

    if (result == 1)
      isAuthenticated = true;
    else
      isAuthenticated = false;
    if (isAuthenticated) {
      next(); // allow to enter route
    }
    else {
      next('/'); // go to '/login';
    }
  }
  else {
    store.dispatch('CANCEL_PENDING_REQUESTS');
    next();
  }
  // }
})