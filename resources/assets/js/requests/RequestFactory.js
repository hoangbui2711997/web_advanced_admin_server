import AdminRequest from './AdminRequest';
import CategoryRequest from './CategoryRequest';
import ProductRequest from './ProductRequest';
import RoleRequest from "./RoleRequest";
import PermissionRequest from "./PermissionRequest";


const requestMap = {
  AdminRequest,
  CategoryRequest,
  ProductRequest,
  RoleRequest,
  PermissionRequest,
};

const instances = {};

export default class RequestFactory {

  static getRequest(classname) {
    let RequestClass = requestMap[classname];
    if (!RequestClass) {
      throw new Error('Invalid request class name: ' + classname);
    }

    let requestInstance = instances[classname];
    if (!requestInstance) {
        requestInstance = new RequestClass();
        instances[classname] = requestInstance;
    }

    return requestInstance;
  }

}
