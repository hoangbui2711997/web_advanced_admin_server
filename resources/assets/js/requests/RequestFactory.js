import AdminRequest from './AdminRequest';
import CategoryRequest from './product_management/CategoryRequest';
import ProductRequest from './product_management/ProductRequest';
import RoleRequest from "./role_management/RoleRequest";
import PermissionRequest from "./role_management/PermissionRequest";
import EmployeeRequest from "./user_management/EmployeeRequest";
import UserRequest from "./user_management/UserRequest";


const requestMap = {
  AdminRequest,
  CategoryRequest,
  ProductRequest,
  RoleRequest,
  PermissionRequest,
  EmployeeRequest,
  UserRequest,
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
