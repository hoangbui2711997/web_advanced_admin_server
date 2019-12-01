import Vue from 'vue';
import Vuex from 'vuex';
import rf from "../requests/RequestFactory";
Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    authorize: {},
    permissionMenu: {},
    permissionPage: {},
    permissionPageAction: {},
  },
  getters: {
    getPermissionMenu (state) {
      return Object.keys(state.permissionMenu);
    },
    getPermissionPage (state) {
      return Object.keys(state.permissionPage);
    },
    getPermissionPageAction: (state) => (page) => {
      return state.permissionPage[page];
    },
  },
  mutations: {
    updateAuthorize (state, payload) {
      state.authorize = payload;
      state.permissionMenu = _.reduce(payload, (result, permission) => {
        if (_.isEmpty(result[permission.menu])) {
          result[permission.menu] = 1;
        }
        if (_.isEmpty(state.permissionPage[permission.page])) {
          state.permissionPage[permission.page] = [];
        }
        if (permission.control !== null) {
          state.permissionPage[permission.page].push(permission.control);
        }

        return result;
      }, {});
    },
    emptyAuthorize (state) {
      state.authorize = {};
      state.permissionMenu = {};
      state.permissionPage = {};
      state.permissionPageAction = {};
    },
  },
  actions: {
    async getCurrentUserInfo (context) {
      const { data } = await rf.getRequest('AdminRequest').getUser();
      this.commit('emptyAuthorize');
      this.commit('updateAuthorize', data);
      return !!data;
    },
    async init (context) {
      return !!await this.dispatch('getCurrentUserInfo') || true;
    },
  },
});

export default store;
