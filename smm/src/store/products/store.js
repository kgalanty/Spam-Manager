import requestHelper from "../../mixins/requestHelper"
import axios from "axios";
export default {
    namespaced: true,
    state: () => ({
        products: []
    }),
    mutations: {
        setProducts(state, products) {
            state.products = products
        }
    },
    actions:
    {
        getProducts(context) {
            const params = requestHelper.generateParamsForRequest('Products')
            return new Promise((resolve, reject) => {
                axios.get('addonmodules.php?' + params)
                    .then((resp) => {
                        if (resp.data.templates != []) {
                            context.commit('setProducts', resp.data.products)
                            resolve()
                        }
                        else {
                            reject('No Products found')
                        }
                    })
            })
        }
    }
}