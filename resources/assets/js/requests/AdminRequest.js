import BaseRequest from './BaseRequest'

export default class AdminRequest extends BaseRequest {

  login (params) {
    return this.post('/admin/login', params, false);
  }

  register(params) {
    return this.post('/admin/register', params, false);
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

  getProducts (params) {
    const url = 'products';
    return this.get(url, params);
  }

  getCurrentUser () {
    const url = 'get-current-user';
    return this.get(url);
  }

  addCategory (params) {
    const url = 'category';
    return this.post(url, params);
  }

  getEmployees (params) {
    const url = 'employees';
    return this.get(url, params)
  }
}

