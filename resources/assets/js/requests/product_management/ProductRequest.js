import BaseRequest from '../BaseRequest'

export default class ProductRequest extends BaseRequest {
  prefix() {
    return 'product-management/product/'
  }

  getProducts (params) {
    const url = 'list';
    return this.get(url, params);
  }

  getProduct (params) {
    const url = 'detail';
    return this.get(url, params);
  }

  addProduct (params) {
    const url = 'add';
    return this.post(url, params);
  }

  updateProduct (params) {
    const url = 'edit';
    return this.post(url, params);
  }

  delProduct (params) {
    const url = 'del';
    return this.post(url, params);
  }

  getDiscounts (params) {
    const url = 'discounts';
    return this.get(url, params);
  }
}

