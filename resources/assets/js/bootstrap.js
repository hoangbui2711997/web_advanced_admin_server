import "babel-polyfill";
import Echo from 'laravel-echo'
import VueI18n from 'vue-i18n';
import Vue from 'vue';
import messages from './lang/vue-i18n';
if ((~window.navigator.userAgent.indexOf('MSIE')) ||
  (~window.navigator.userAgent.indexOf('Trident/')) ||
  (~window.navigator.userAgent.indexOf('Edge/'))) {
  window.Promise = require('es6-promise').Promise;
  require('es6-object-assign').polyfill();
}

window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.interceptors.request.use(config => {
  // NProgress.start();
  return config
});

window.axios.interceptors.response.use(response => {
  // NProgress.done();
  return response
})

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.csrf_token = token.content;
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */
Vue.use(VueI18n);

// window.Echo = new Echo({
//   broadcaster: 'socket.io',
//   host: SOCKET_URL
// });

const locale = document.documentElement.lang;
const i18n = new VueI18n({
  locale,
  messages,
});
window.i18n = i18n;
