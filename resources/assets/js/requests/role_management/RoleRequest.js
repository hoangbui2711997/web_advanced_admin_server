import BaseRequest from '../BaseRequest'

export default class RoleRequest extends BaseRequest {
  prefix() {
    return 'role-management/role/'
  }

  addRole (params) {
    const url = 'add';
    return this.post(url, params);
  }

  getRoles () {
    const url = 'list';
    return this.get(url);
  }

  updateRole (params) {
    const url = 'edit';
    return this.put(url, params);
  }

  delRole (params) {
    const url = 'del';
    return this.post(url, params);
  }

  getRole (params) {
    const url = 'detail';
    return this.get(url, params);
  }

  updateRolePermissions (params) {
    const url = 'permissions';
    return this.post(url, params);
  }

  getRolePermissions () {
    const url = 'role-permissions';
    return this.get(url);
  }
}

