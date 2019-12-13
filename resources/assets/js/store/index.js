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
      return state.permissionMenu;
    },
    getPermissionPage (state) {
      return state.permissionPage;
    },
    getPermissionPageAction: (state) => (page) => {
      return state.permissionPage[page];
    },
    isAuthenticated(state) {
      return !!state.token;
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
    setToken (state, token) {
      state.token = token;
      window.axios.defaults.headers.common['Authorization'] = token;
      window.Echo.connector.options.auth.headers['Authorization'] = token;
    },
    forgetToken (state) {
      state.token = '';
      window.axios.defaults.headers.common['Authorization'] = '';
    },
  },
  actions: {
    saveToken ({ state }, { expired_at }) {
      localStorage.setItem('token', state.token);
      localStorage.setItem('expired_at', expired_at);
    },
    async initAuth ({ dispatch, state, commit }, credential) {
      const { access_token } = credential;
      commit('setToken', "Bearer " + access_token);
      dispatch('saveToken', credential);
      return this.dispatch('getCurrentUserInfo');
    },
    async getCurrentUserInfo (context) {
      const { data } = await rf.getRequest('AdminRequest').getUser();
      this.commit('emptyAuthorize');
      this.commit('updateAuthorize', data);
    },
    async loadAuth (context) {
      // const { data } = await rf.getRequest('AdminRequest').getUser();
      const token = window.localStorage.getItem('token');
      if (!!token) {
        this.commit('setToken', token);
      }

      return !!token;
    },
    async init (context) {
      try {
        const result = await this.dispatch('loadAuth');
        if (result) {
          await this.dispatch('getCurrentUserInfo');

          if (window.app.$route.name === 'login') {
            await window.app.$router.push({ name: 'UserPage' });
          }
          return true;
        }

        return true;
      } catch (e) {
        console.log(e, "e");
        this.dispatch('logout');
      }
    },
    logout ({ commit }) {
      localStorage.removeItem('token');
      localStorage.removeItem('expired_at');
      commit('forgetToken');
      commit('emptyAuthorize');
      return true;
    },
  },
});

export default store;
