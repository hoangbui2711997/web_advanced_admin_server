import BaseRequest from '../BaseRequest'

export default class UserRequest extends BaseRequest {
  prefix() {
    return 'user-management/user/';
  }

  getUsers (params) {
    const url = 'users';
    return this.get(url, params);
  }

  addUser (params) {
    const url = 'user';
    return this.post(url, params);
  }

  editUser (params) {
    const url = 'user';
    return this.put(url, params);
  }

  delUser (params) {
    const url = 'user';
    return this.del(url, params);
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

