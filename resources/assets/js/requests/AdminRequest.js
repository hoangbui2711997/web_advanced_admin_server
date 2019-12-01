import BaseRequest from './BaseRequest'

export default class AdminRequest extends BaseRequest {
  prefix() {
    return 'common/'
  }

  login (params) {
    return this.post('login', params, false);
  }

  register(params) {
    return this.post('register', params, false);
  }

  getCurrentUser () {
    const url = 'get-current-user';
    return this.get(url);
  }

  getUser () {
    const url = 'user';
    return this.get(url);
  }
}

