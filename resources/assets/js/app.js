import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import i18n from '~/plugins/i18n'
import App from '~/components/App'
import VueStripeCheckout from 'vue-stripe-checkout';
import Simplert from 'vue2-simplert-plugin'
require('vue2-simplert-plugin/dist/vue2-simplert-plugin.css')

import '~/plugins'
import '~/components'

Vue.config.productionTip = false

// base/global options
// these options can be overridden
// by the options in the .open(options)
// function.
const options = {
  key: '',
  // image: 'https://cdn.meme.am/images/100x100/15882140.jpg',
  locale: 'auto',
  currency: 'USD',
  billingAddress: false,
  panelLabel: 'Subscribe {{amount}}'
}

Vue.use(VueStripeCheckout, options);
Vue.use(Simplert)

/* eslint-disable no-new */
new Vue({
  i18n,
  store,
  router,
  ...App
})
