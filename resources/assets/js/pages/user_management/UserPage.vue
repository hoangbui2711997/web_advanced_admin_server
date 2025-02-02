<template>
  <div class="">
    <div class="content-page w-88 m-auto w-max">
      <div class="heading-title">Users</div>
      <div class="data-table-user-balances-01">
        <div class="is-clearfix">
          <control class="button is-info is-pulled-right pl-20 mb-20 is-small" name="controlAddUserHandle" @click.native="controlAddUserHandle"
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
                  <control class="button is-warning is-small" name="controlEditUserHandle" @click.native="controlEditUserHandle(props.item)"
                       :id="`${$route.name}|control_handle_edit`">Edit
                  </control>
                  <control class="button is-danger is-small" name="controlDeleteUserHandle" @click.native="controlDeleteUserHandle(props.item.id)"
                       :id="`${$route.name}|control_handle_del`">Delete
                  </control>
                </td>
              </tr>
            </template>
          </data-table>
        </div>
        <edit-user-modal :show="modal.edit.isShow" @close="modal.edit.isShow = false" :model="modal.edit.model"
                         @success="fetchData"></edit-user-modal>
        <add-user-modal :show="modal.add.isShow" @close="modal.add.isShow = false" :model="{}" @success="fetchData"></add-user-modal>
        <confirm-modal
          :show="modal.del.isShow"
          :id="modal.del.id"
          @close="modal.del.isShow = false"
          :title="'Delete User'"
          :handle="controlRemoveModel"
          @success="refetchData"
        ></confirm-modal>
      </div>
    </div>
  </div>
</template>

<script>
  import EditUserModal from "../../modals/EditUserModal";
  import AddUserModal from "../../modals/AddUserModal";
  import ConfirmModal from "../../modals/ConfirmModal";

  export default {
    name: "UserPage",
    components: {ConfirmModal, AddUserModal, EditUserModal},
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
      },
      refetchData() {
        this.closeAllPopup();
        this.$refs.datatable.fetchData(1);
      },
      fetchData() {
        this.closeAllPopup();
        this.$refs.datatable.fetch();
      },
      controlAddUserHandle() {
        this.modal.add.isShow = true;
      },
      controlEditUserHandle(user) {
        this.modal.edit.model = user;
        this.modal.edit.isShow = true;
      },
      controlDeleteUserHandle(id) {
        this.modal.del.isShow = true;
        this.modal.del.id = id;
      },
      getData(params) {
        return this.rf.getRequest('UserRequest').getUsers(params);
      },
      controlRemoveModel() {
        const id = this.modal.del.id;
        return this.delUser({user_id: id});
      },
    },
  }
</script>