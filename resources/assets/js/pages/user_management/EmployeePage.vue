<template>
  <div class="">
    <div class="content-page w-88 m-auto w-max">
      <div class="heading-title">Employees</div>
      <div class="data-table-user-balances-01">
        <div class="is-clearfix">
          <control class="button is-info is-pulled-right pl-20 mb-20 is-small" @click.native="controlAddEmployeeHandle"
                   name="controlAddEmployeeHandle"
               :id="`${$route.name}|control_handle_del`">Add
          </control>
        </div>
        <div class="table__main-table table-01">
          <data-table
            :get-data="getData"
            :msg-empty-data="'empty'"
            :limit-rows="10"
            :showEmptyRow="true"
            ref="datatable"
          >
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created at</th>
            <th>Updated at</th>
            <th>Control</th>
            <template slot="body" slot-scope="props">
              <tr>
                <td>{{ props.index }}</td>
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
                <td>{{ getName(props.item) }}</td>
                <td>{{ props.item.created_at }}</td>
                <td>{{ props.item.updated_at }}</td>
                <td>
                  <control class="button is-warning is-small" @click.native="controlEditEmployeeHandle(props.item)"
                       name="controlEditEmployeeHandle"
                       :id="`${$route.name}|control_handle_edit`">Edit
                  </control>
                  <control class="button is-warning is-small" @click.native="controlEditRoleHandle(props.item)"
                       name="controlEditRoleHandle"
                       :id="`${$route.name}|control_handle_del`">Edit Role
                  </control>
                  <control class="button is-danger is-small" @click.native="controlDeleteEmployeeHandle(props.item.id)"
                       name="controlDeleteEmployeeHandle"
                       :id="`${$route.name}|control_handle_del`">Delete
                  </control>
                </td>
              </tr>
            </template>
          </data-table>
        </div>
        <edit-employee-modal :show="modal.edit.isShow" @close="modal.edit.isShow = false" :model="modal.edit.model"
                         @success="fetchData"></edit-employee-modal>
        <add-employee-modal :show="modal.add.isShow" :is-user="0" @close="modal.add.isShow = false" :model="{}" @success="fetchData"></add-employee-modal>
        <edit-role-modal :show="modal.edit_role.isShow" :is-user="0" @close="modal.edit_role.isShow = false" :model="modal.edit_role.model" @success="fetchData"></edit-role-modal>
        <confirm-modal
          :show="modal.del.isShow"
          :id="modal.del.id"
          @close="modal.del.isShow = false"
          :title="'Delete Employee'"
          :msg="'Do you really want to delete this employee?'"
          :handle="controlRemoveModel"
          @success="refetchData"
        ></confirm-modal>
      </div>
    </div>
  </div>
</template>

<script>
  import EditEmployeeModal from "./employee/EditEmployeeModal";
  import AddEmployeeModal from "./employee/AddEmployeeModal";
  import ConfirmModal from "../../modals/ConfirmModal";
  import EditRoleModal from "./employee/EditRoleModal";

  export default {
    name: "EmployeePage",
    components: { EditRoleModal, ConfirmModal, AddEmployeeModal, EditEmployeeModal },
    data() {
      return {
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
            id: '',
          },
          edit_role: {
            isShow: false,
            id: '',
            model: {}
          }
        },
      }
    },
    methods: {
      getName (user) {
        if (!!user && this._.isArray(user.roles)) {
          return this._.reduce(user.roles, (result, { name }) => {
            return `${result}, ${name}`;
          }, '').slice(1);
        }
        return '';
      },
      closeAllPopup() {
        this.modal.add.isShow = false;
        this.modal.del.isShow = false;
        this.modal.edit.isShow = false;
        this.modal.edit_role.isShow = false;
      },
      refetchData() {
        this.closeAllPopup();
        this.$refs.datatable.fetchData(1);
      },
      fetchData() {
        this.closeAllPopup();
        this.$refs.datatable.fetch();
      },
      controlAddEmployeeHandle() {
        this.modal.add.isShow = true;
      },
      controlEditRoleHandle (user) {
        this.modal.edit_role.model = user;
        this.modal.edit_role.isShow = true;
      },
      controlEditEmployeeHandle(user) {
        this.modal.edit.model = user;
        this.modal.edit.isShow = true;
      },
      controlDeleteEmployeeHandle(id) {
        this.modal.del.isShow = true;
        this.modal.del.id = id;
      },
      getData(params) {
        return this.rf.getRequest('EmployeeRequest').getEmployees(params);
      },
      controlRemoveModel() {
        const id = this.modal.del.id;
        return this.delUser({user_id: id});
      },
    },
  }
</script>