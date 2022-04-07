import Vue from 'vue'
import Vuex from 'vuex'
//import axios from 'axios'
import Buefy from 'buefy'
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
