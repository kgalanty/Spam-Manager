import Vue from 'vue'
import Vuex from 'vuex'
//import axios from 'axios'
import Buefy from 'buefy'

// import chatsLogStore from './chatlogs/store'
// import chatcolumnsStore from './chatcolumns/store'
// import chatsStore from './chats/store'
// import tagsStore from './tags/store'
// import OrdersStore from './orders/store'
// import StaffOnline from './staffonline/store'
 import TemplatesStore from './templates/store'

Vue.use(Vuex)
Vue.use(Buefy)

export default new Vuex.Store({
  state: {
  },
  mutations: {
  },
  actions: {
  },
  modules: {
     TemplatesStore: TemplatesStore,
  }
})
