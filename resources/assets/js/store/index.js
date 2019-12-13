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
    token: '',
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
    setToken (state, { token }) {
      state.token = token;
      window.axios.defaults.headers.common['Authorization'] = token;
    },
  },
  actions: {
    saveToken ({ state }, { expired_at }) {
      localStorage.setItem('token', state.token);
      localStorage.setItem('expired_at', expired_at);
    },
    async initAuth ({ dispatch, state, commit }, credential) {
      return new Promise(resolve => {
        const { access_token } = credential;
        commit('setToken', "Bearer " + access_token);
        dispatch('saveToken', credential);
        resolve(state.token);
      })
    },
    async getCurrentUserInfo (context) {
      const { data } = await rf.getRequest('AdminRequest').getUser();
      this.commit('emptyAuthorize');
      this.commit('updateAuthorize', data);
      return !!data;
    },
    async loadAuth (context) {
      // const { data } = await rf.getRequest('AdminRequest').getUser();
      const token = window.localStorage.getItem('token');
      if (!!token) {
        this.commit('setToken', { token });
      }

      return !!token;
    },
    async init (context) {
      try {
        const result = await this.dispatch('loadAuth');
        if (result) {
          return !!await this.dispatch('getCurrentUserInfo') || true;
        }

        return true;
      } catch (e) {
        this.dispatch('logout');
      }
    },
    logout ({ commit }) {
      localStorage.removeItem('token');
      localStorage.removeItem('expired_at');
      commit('setTokenEmpty');
    },
  },
});

export default store;
