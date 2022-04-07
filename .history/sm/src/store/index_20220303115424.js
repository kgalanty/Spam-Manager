import Vue from 'vue'
import Vuex from 'vuex'
//import axios from 'axios'
import Buefy from 'buefy'
import TemplatesStore from './templates/store'
import ServersStore from './servers/store'
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
    ServersStore: ServersStore,
  }
})
