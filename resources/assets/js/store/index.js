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
      console.log('data_1', payload);
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
      console.log(state.permissionMenu, 'state.permissionMenu');
      console.log(state.permissionPage, 'state.permissionMenu');

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
      return new Promise(resolve => {
        const { access_token } = credential;
        commit('setToken', "Bearer " + access_token);
        dispatch('saveToken', credential);
        resolve(state.token);
      })
    },
    async getCurrentUserInfo (context) {
      console.log('data_0');
      const { data } = await rf.getRequest('AdminRequest').getUser();
      this.commit('emptyAuthorize');
      console.log('data_0_1');
      this.commit('updateAuthorize', data);
      console.log('data_2');
      return !!data;
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
          const data = await this.dispatch('getCurrentUserInfo');
          if (!!data) {
            console.log(data, 'data_3');
            await window.app.$router.push({ name: 'UserPage' });
          }

          return true;
        }

        return true;
      } catch (e) {
        this.dispatch('logout');
      }
    },
    logout ({ commit }) {
      localStorage.removeItem('token');
      localStorage.removeItem('expired_at');
      commit('forgetToken');
      commit('emptyAuthorize');
    },
  },
});

export default store;
