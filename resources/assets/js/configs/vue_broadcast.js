import Vue from 'vue';

const BroadcastHub = new Vue();

const _on = Vue.prototype.$on;
function $on (event, callback) {
  _on.call(BroadcastHub, event, callback);
  _on.call(this, event, callback);
  return this;
}

const _off = Vue.prototype.$off;
function $off (event, callback) {
  _off.call(BroadcastHub, event, callback);
  _off.call(this, event, callback);
  return this;
}

const _emit = Vue.prototype.$emit;
function $broadcast (event, ...agrs) {
  _emit.call(BroadcastHub, event, ...agrs);
  return this;
}

const Vue_broadcast = {
  install (Vue) {
    Vue.prototype.$on = $on;
    Vue.prototype.$off = $off;
    Vue.prototype.$broadcast = $broadcast;
  },
  $on,
  $off,
  $broadcast,
};

export default Vue_broadcast;
