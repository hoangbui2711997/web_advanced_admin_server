import BaseRequest from "../BaseRequest";

export default class EmployeeRequest extends BaseRequest {
  prefix() {
    return 'user-management/employee/'
  }

  getEmployees (params) {
    const url = 'employees';
    return this.get(url, params)
  }

  updateRoleUser (params) {
    const url = 'update-user-role';
    return this.post(url, params);
  }

  getRoles () {
    const url = 'list';
    return this.get(url);
  }

  editUser (params) {
    const url = 'user';
    return this.put(url, params);
  }

  delUser (params) {
    const url = 'user';
    return this.del(url, params);
  }

  addUser (params) {
    const url = 'user';
    return this.post(url, params);
  }
}