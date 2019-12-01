import BaseRequest from '../BaseRequest'

export default class CategoryRequest extends BaseRequest {
  prefix() {
    return 'product-management/category/'
  }

  addCategory (params) {
    const url = 'add';
    return this.post(url, params);
  }

  getCategories (params) {
    const url = 'list';
    return this.get(url, params);
  }

  updateCategory (params) {
    const url = 'edit';
    return this.put(url, params);
  }

  delCategory (params) {
    const url = 'del';
    return this.del(url, params);
  }

  getCategory (params) {
    const url = 'detail';
    return this.get(url, params);
  }
}

