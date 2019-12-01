/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap';
import Vue from 'vue';
import router from './configs/routes';
import VeeValidate, { Validator } from 'vee-validate';
import DataTable from './components/DataTable';
import DataTable2 from './components/DataTable2';
import DataTable3 from './components/DataTable3';
import Toasted from 'vue-toasted';
import VModal from 'vue-js-modal';
import ImageShow from './components/ImageShow.vue';
import VueImg from 'v-img';
import vClickOutside from 'v-click-outside';
import VueLoading from 'vue-loading-template';
// import GlobalSocket from './GlobalSocket';
import CurrencyInput from './components/CurrencyInput.vue';
import VueBroadcast from './configs/vue_broadcast';
import App from './pages/App.vue';
import store from './store';
import NegativeCurrencyInput from './components/NegativeCurrencyInput';
import PercentInput from './components/PercentInput';

import './common/Filters.js';
import './vailidators';
import './custom_rules';
import Dictionary from './dictionary';

import Dropdown from './components/dropdown/Dropdown';
import DropdownMenu from './components/dropdown/DropdownMenu';
import DropdownItem from './components/dropdown/DropdownItem';
import SmoothScrollbar from "./common/SmoothScrollbar";
import Modal from "./modals/Modal";
import CommonHandleModal from "./common/CommonHandleModal";
import InputOnlyNumber from "./common/InputOnlyNumber";
import InfiniteLoading from 'vue-infinite-loading';
import Control from "./common/Control";

Vue.use(VeeValidate);
Vue.use(Toasted);
Vue.use(VModal, { dialog: true, dynamic: true, injectModalsContainer: true });
Vue.use(vClickOutside);
Vue.use(VueImg);
Vue.use(VueBroadcast);
Vue.use(VueLoading);

Vue.component('modal', Modal);
Vue.component('common-handle-modal', CommonHandleModal);
Vue.component('smooth-scrollbar', SmoothScrollbar);
Vue.component('img-show', ImageShow);
Vue.component('data-table', DataTable);
Vue.component('data-table2', DataTable2);
Vue.component('data-table3', DataTable3);
Vue.component('currency-input', CurrencyInput);
Vue.component('negative-currency-input', NegativeCurrencyInput);
Vue.component('percent-input', PercentInput);
Vue.component('input-only-number', InputOnlyNumber);
Vue.component('control', Control);
Vue.use(InfiniteLoading, {
  props: {
    spinner: 'default',
    /* other props need to configure */
  },
  system: {
    throttleLimit: 300,
    /* other settings need to configure */
  },
  slots: {
    noMore: 'No more data',
  }
});

Vue.component('dropdown', Dropdown);
Vue.component('dropdown-menu', DropdownMenu);
Vue.component('dropdown-item', DropdownItem);

const i18n = window.i18n;
Vue.use(VeeValidate, { i18n });
Validator.localize(i18n.locale, Dictionary[i18n.locale]);

Vue.mixin({
});

window.store = store;
// window.GlobalSocket = new GlobalSocket();

// router.beforeEach((to, from, next) => {
//
// })

window.app = new Vue({
  name: 'ROOT',
  i18n,
  store,
  router,
  created () {
    // this.$store.dispatch('init');
  },
  render (createElement) {
    return createElement(App);
  },
}).$mount('#admin-app');
if (window.isAuthenticated) {
  window.app.$store.dispatch('init');
}