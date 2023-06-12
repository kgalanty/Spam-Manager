import Vue from 'vue'
import Vuex from 'vuex'
//import axios from 'axios'
import Buefy from 'buefy'
import TemplatesStore from './templates/store'
import ServersStore from './servers/store'
import QueueStore from './queue/store'
import QueueListStore from './list/store'
import ProductsStore from './products/store'

Vue.use(Vuex)
Vue.use(Buefy)

export default new Vuex.Store({
  state: {
    baseurl: 'https://my.tmdhosting.com/admin/'
  },
  mutations: {
  },
  actions: {
  },
  modules: {
    TemplatesStore: TemplatesStore,
    ServersStore: ServersStore,
    ProductsStore: ProductsStore,
    QueueStore: QueueStore,
    QueueListStore: QueueListStore,
  }
})
