import Vue from 'vue'
import Buefy from 'buefy'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
Vue.prototype.$api = axios
Vue.config.productionTip = false
Vue.use(Buefy,
  {
    defaultContainerElement: '#app'
  })
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
