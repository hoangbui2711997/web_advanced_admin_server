import BaseRequest from '../BaseRequest'

export default class PermissionRequest extends BaseRequest {
  prefix() {
    return 'role-management/permission/'
  }

  addPermission (params) {
    const url = 'add';
    return this.post(url, params);
  }

  getPermissions (params) {
    const url = 'list';
    return this.get(url, params);
  }

  updatePermission (params) {
    const url = 'edit';
    return this.put(url, params);
  }

  delPermission (params) {
    const url = 'del';
    return this.del(url, params);
  }

  getPermission (params) {
    const url = 'detail';
    return this.get(url, params);
  }

  updatePermissionControl (params) {
    const url = 'update-permission-control';
    return this.post(url, params);
  }
}

