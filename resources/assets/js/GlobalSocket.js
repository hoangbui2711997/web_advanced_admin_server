import Vue from 'vue';

export default class GlobalSocket {

  constructor () {
    // public channels
    this.subscribePublicChannels();

    Vue.mixin({
      mounted () {
        if (this.getSocketEventHandlers) {
          window._.forIn(this.getSocketEventHandlers(), (handler, eventName) => {
            this.$on(eventName, handler);
          });
        }
      },
      beforeDestroy () {
        if (this.getSocketEventHandlers) {
          window._.forIn(this.getSocketEventHandlers(), (handler, eventName) => {
            this.$off(eventName, handler);
          });
        }
      },
    });
  }

  subscribePublicChannels() {
  }
}
