<template>
  <div class="">
    <div class="content-page w-88 m-auto w-max">
      <div class="heading-title">Role Permission</div>
      <div class="field is-horizontal">
        <div class="field-label is-normal">
          <label class="label">Role: </label>
        </div>
        <div class="field-body">
          <div class="field">
            <div class="select-groups-container field-item">
              <div class="select">
                <dropdown style="width: 100%">
                  <p class="active-selected capitalize" @click="resetErrors()" v-if="!!role">
                    {{ `${_.get(role, 'name', '')}` }}
                  </p>
                  <dropdown-menu slot="dropdown" class="box-popup" style="height: 200px; overflow-y: auto;">
                    <dropdown-item
                      class="dropdown-item capitalize"
                      v-for="(role, index) in roles"
                      :key="index"
                      @click.native="setRole(role)"
                      :title="role.name"
                    >
                      {{ `${_.get(role, 'name', '')}` }}
                    </dropdown-item>
                  </dropdown-menu>
                </dropdown>
                <input hidden name="role" data-vv-validate-on="none" v-model="role" v-validate="'required'"/>
                <div class="warning-message" v-if="errors.has('role')">
                  <span>{{ errors.first('role') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style="margin-top: 5px;">
          <control class="button is-info is-small" @click.native="controlAddRoleHandle()"
               name="controlAddRoleHandle"
               :id="`${$route.name}|control_add_role`">Add
          </control>
          <control class="button is-warning is-small" @click.native="controlEditRoleHandle()"
               name="controlEditRoleHandle"
               :id="`${$route.name}|control_edit_role`">Edit
          </control>
          <control class="button is-danger is-small" @click.native="controlRemoveRoleHandle()"
               name="controlRemoveRoleHandle"
               :id="`${$route.name}|control_del_role`">Delete
          </control>
        </div>
      </div>

      <div class="select-groups-container field-item">

      </div>
      <div v-if="!_.isEmpty(permissions) && !!params.checkRolePermissions[role.name]" class="table__main-table table-01"
           v-for="permission in permissions">
        <div class="is-pulled-left is-4" style="font-weight: 450; opacity: 0.9; margin: 20px 0 12px;">
          Permission - {{ _.last(`${permission.name}`.split('::')) }}
        </div>
        <table>
          <thead>
          <tr>
            <th>No</th>
            <th>Name Route</th>
            <th>Path</th>
            <th style="width: 180px;"></th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(childPermission, index) in getChildPermission(permission)">
            <td>{{ index + 1 }}</td>
            <td>{{ childPermission.name }}</td>
            <td>{{ childPermission.path }}</td>
            <td>
              <input type="checkbox"
                     style="margin: 7px 15px;"
                     :id="childPermission.name"
                     v-model="params.checkRolePermissions[role.name][_.last(`${permission.name}`.split('::'))][childPermission.name.split(':')[0]]['checked']">
              <control class="button is-primary is-small"
                       @click.native="controlDetailPermissionHandle(_.last(`${permission.name}`.split('::')), childPermission.name.split(':')[0])"
                       name="controlDetailPermissionHandle"
                       :id="`${$route.name}|control_add_role`">Detail
              </control>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="button is-info is-pulled-right pl-20 mb-20 is-small" style="margin-right: 80px"
         @click="controlSaveRolePermission"
         :id="`${$route.name}|control_handle_del`">Save
    </div>
    <edit-role-modal
      :show="modal.edit.isShow"
      :model="modal.edit.model"
      @close="modal.edit.isShow = false"
      @success="fetchData()">
    </edit-role-modal>
    <add-role-modal
      :show="modal.add.isShow"
      @close="modal.add.isShow = false"
      :model="{}"
      @success="customFetchData()">
    </add-role-modal>
    <confirm-modal
      :show="modal.del.isShow"
      :msg="'Do you really want to delete this role?'"
      @close="modal.del.isShow = false"
      :title="'Delete Role'"
      :handle="handleRemoveModel"
      @success="fetchData()"
    ></confirm-modal>
    <dynamic-import-modal
      v-if="!!params.checkRolePermissions[role.name] &&
      !!params.checkRolePermissions[role.name][modal.detailPermission.permissionName] &&
      !!params.checkRolePermissions[role.name][modal.detailPermission.permissionName][modal.detailPermission.name]"
      :show="modal.detailPermission.isShow"
      :name="modal.detailPermission.name"
      @close="modal.detailPermission.isShow = false"
      :role-id="role.id"
      :permission-name="modal.detailPermission.permissionName"
      :map-controls="params.checkRolePermissions[role.name][modal.detailPermission.permissionName][modal.detailPermission.name].controls"
      @success="handleSubmitControl()"
    >
    </dynamic-import-modal>
  </div>
</template>

<script>
  import EditRoleModal from "../single_table_management/role_management/EditRoleModal";
  import AddRoleModal from "../single_table_management/role_management/AddRoleModal";
  import ConfirmModal from "../../modals/ConfirmModal";
  import DynamicImportModal from "../../modals/DynamicImportModal";

  export default {
    name: "RolePermission",
    components: {
      DynamicImportModal,
      EditRoleModal,
      AddRoleModal,
      ConfirmModal,
    },
    data() {
      return {
        roles: [],
        role: {},
        permissions: [],
        params: {checkRolePermissions: {}},
        modal: {
          edit: {
            isShow: false,
            model: {}
          },
          add: {
            isShow: false,
          },
          del: {
            isShow: false,
          },
          detailPermission: {
            isShow: false,
            name: '',
            permissionName: '',
          },
        },
        defaultCheckPermissions: {},
      }
    },
    async mounted() {
      this.getRoles();
      if (await this.init()) {
        this.fetchAllRolePermission();
      }
      this.permissions = this.getAllPermission(this.$router.options.routes, []);
    },
    methods: {
      async handleSubmitControl() {
        const data = await this.fetchData();
        if (!!data) {
          return await this.$store.dispatch('getCurrentUserInfo');
        }
      },
      controlDetailPermissionHandle (permissionName, name) {
        this.modal.detailPermission.isShow = true;
        this.modal.detailPermission.name = name;
        this.modal.detailPermission.permissionName = permissionName;
      },
      async fetchAllRolePermission () {
        let { data } = await this.getAllRolePermissions();
        console.log(data, "data");
        _.forEach(Object.keys(data), (role) => {
          console.log(role, "role");
          this.params.checkRolePermissions[role] = data[role];
        });
      },
      async customFetchData () {
        const data = await this.fetchData();
        if (data) {
          this.params.checkRolePermissions[_.last(this.roles).name] = this.defaultCheckPermissions;
        }
      },
      async fetchData () {
        this.closeAll();
        const data = await this.getRoles();
        this.fetchAllRolePermission();
        return !!data;
      },
      getAllRolePermissions() {
        return this.rf.getRequest('RoleRequest').getRolePermissions();
      },
      controlAddRoleHandle() {
        this.modal.add.isShow = true;
        this.modal.add.model = {};
      },
      controlEditRoleHandle() {
        this.modal.edit.isShow = true;
        this.modal.edit.model = this.role;
      },
      async handleRemoveModel () {
        return await this.rf.getRequest('RoleRequest').delRole({ id: this.role.id});
      },
      async controlRemoveRoleHandle() {
        this.modal.del.isShow = true;
      },
      async init() {
        let menus = this.getAllPermission(this.$router.options.routes, []);
        let checkPermissions = {};
        menus.forEach((menu) => {
          const menuName = _.last(`${menu.name}`.split('::'));
          _.set(checkPermissions, menuName, {});
          let childMenus = this.getChildPermission(menu);
          childMenus.forEach((item) => {
            const pageName = item.name.split(':')[0];
            console.log(item, "item");
            _.set(checkPermissions[menuName], pageName, {});
            _.set(checkPermissions[menuName][pageName], 'checked', false);
            _.set(checkPermissions[menuName][pageName], 'path', item.path);
            _.set(checkPermissions[menuName][pageName], 'controls', []);
          });
        });

        const checkRolePermissions = {};
        const roles = await this.getRoles();
        _.forEach(roles, (role) => {
          checkRolePermissions[role.name] = JSON.parse(JSON.stringify(checkPermissions));
        });
        this.$set(this.params, 'checkRolePermissions', checkRolePermissions);
        this.defaultCheckPermissions = checkPermissions;
        return !!roles;
      },
      async controlSaveRolePermission() {
        const params = {
          'role_id': this.role.id,
          'permissions': this.params.checkRolePermissions[this.role.name],
        };

        const { data } = await this.rf.getRequest('RoleRequest').updateRolePermissions(params);
        this.showSuccess(data);
        if (!!data) {
          this.$broadcast('updateSideBar');
        }
      },
      setRole(role) {
        this.role = role;
      },
      async getRoles() {
        const {data} = await this.rf.getRequest('RoleRequest').getRoles();
        this.roles = data;
        this.role = _.first(this.roles);

        return data;
      },
      getAllPermission(routes, result, prefix = '') {
        routes.forEach(route => {
          if (`${route.name}`.includes('::')) {
            result.push(route);
          }
          if (_.isArray(route.children)) {
            this.getAllPermission(route.children, result, `${prefix}${route.path}`);
          }
        });
        return result;
      },
      getChildPermission(permission) {
        let result = [];
        const prefix = permission.path;
        permission.children.forEach(item => {
          result.push({
            name: item.name,
            path: `${prefix}${item.path}`
          })
        });

        return result;
      },
      closeAll() {
        this.modal.edit.isShow = false;
        this.modal.add.isShow = false;
        this.modal.del.isShow = false;
        this.modal.detailPermission.isShow = false;
      }
    }
  }
</script>

<style scoped>
  .field.is-horizontal {
    width: 56%;
    margin: auto;
  }


</style>