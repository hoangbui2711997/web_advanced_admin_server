import Vue from 'vue'
import VueRouter from 'vue-router'
import NotFound from '../pages/NotFound.vue';
import LoginPage from '../pages/LoginPage';
import BasePage from '../pages/BasePage';
import WrapPage from '../pages/WrapPage';
import RegisterPage from "../pages/RegisterPage";
import UserPage from '../pages/user_management/UserPage';
import ProductPage from "../pages/product_management/ProductPage";
import CategoryPage from "../pages/single_table_management/category_management/CategoryPage";
import EmployeePage from "../pages/user_management/EmployeePage";
import AddProductPage from "../pages/product_management/AddProductPage";
import EditProductPage from "../pages/product_management/EditProductPage";
import DetailProductPage from "../pages/product_management/DetailProductPage";
import ListRoute from "../pages/route/ListRoute";
import RolePermission from "../pages/role_management/RolePermission";


Vue.use(VueRouter);

export default new VueRouter({
  mode: 'history',
  base: '/admin',
  routes: [
    {
      path: '/',
      redirect: { path: 'users' },
    },
    {
      path: '/login',
      component: LoginPage,
      name: 'login',
    },
    {
      path: '/register',
      component: RegisterPage,
      name: 'register',
    },
    {
      path: '/',
      component: BasePage,
      name: 'BasePage',
      children: [
        {
          path: 'user-management/',
          name: 'User::UserManagement',
          component: WrapPage,
          children: [
            {
              path: 'users',
              component: UserPage,
              name: 'UserPage'
            },
            {
              path: 'employees',
              component: EmployeePage,
              name: 'EmployeePage'
            },
          ],
        },

        {
          path: 'product-management/',
          name: 'List::ProductManagement',
          component: WrapPage,
          children: [
            {
              path: 'products',
              component: ProductPage,
              name: 'ProductPage'
            },
            {
              path: 'categories',
              component: CategoryPage,
              name: 'CategoryPage'
            },
            {
              path: 'add',
              component: AddProductPage,
              name: 'AddProductPage'
            },
            {
              path: 'edit',
              component: EditProductPage,
              name: 'EditProductPage:id'
            },
            {
              path: 'detail',
              component: DetailProductPage,
              name: 'DetailProductPage:id'
            },
          ]
        },
        {
          path: 'role-management/',
          name: 'List::RoleManagement',
          component: WrapPage,
          children: [
            {
              path: 'list',
              component: RolePermission,
              name: 'RolePermission'
            },
          ]
        },
        {
          path: 'route-management/',
          name: 'List::RouteManagement',
          component: WrapPage,
          children: [
            {
              path: 'list',
              component: ListRoute,
              name: 'ListRoute'
            },
          ]
        },
      ]
    },
    {
      path: '*',
      component: NotFound
    }
  ],
  linkActiveClass: 'active'
})
