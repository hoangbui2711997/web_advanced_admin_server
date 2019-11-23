<template>
  <div class="">
    <div class="content-page w-88 m-auto w-max">
      <div class="heading-title">Categories</div>
      <div class="data-table-user-balances-01">
        <div class="is-clearfix">
          <div class="button is-info is-pulled-right pl-20 mb-20 is-small" @click="controlHandleAdd"
               :id="`${$route.name}|control_handle_del`">Add
          </div>
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
            <th>Created at</th>
            <th>Updated at</th>
            <th></th>
            <template slot="body" slot-scope="props">
              <tr>
                <td>{{ props.index }}</td>
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.created_at }}</td>
                <td>{{ props.item.updated_at }}</td>
                <td>
                  <div class="button is-warning is-small" @click="controlHandleEdit(props.item)"
                       :id="`${$route.name}|control_handle_edit`">Edit
                  </div>
                  <div class="button is-danger is-small" @click="controlHandleDel(props.item.id)"
                       :id="`${$route.name}|control_handle_del`">Delete
                  </div>
                </td>
              </tr>
            </template>
          </data-table>
        </div>
        <edit-category-modal
          :show="modal.edit.isShow"
          :model="modal.edit.model"
          @close="modal.edit.isShow = false"
          @success="fetchData">
        </edit-category-modal>
        <add-category-modal
          :show="modal.add.isShow"
          @close="modal.add.isShow = false"
          :model="{}"
          @success="fetchData">
        </add-category-modal>
        <confirm-modal
          :show="modal.del.isShow"
          :id="modal.del.id"
          :msg="'Do you really want to delete this category?'"
          @close="modal.del.isShow = false"
          :title="'Delete Category'"
          :handle="controlRemoveModel"
          @success="refetchData"
        ></confirm-modal>
      </div>
    </div>
  </div>
</template>

<script>
  import EditCategoryModal from "./EditCategoryModal";
  import AddCategoryModal from "./AddCategoryModal";
  import ConfirmModal from "../../../modals/ConfirmModal";

  export default {
    // middleware: 'authenticated',
    // middleware: 'auth',
    name: "CategoryPage",
    components: { EditCategoryModal, ConfirmModal, AddCategoryModal },
    data() {
      return {
        users: [],
        data: [],
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
      controlHandleAdd() {
        this.modal.add.isShow = true;
      },
      controlHandleEdit(user) {
        this.modal.edit.model = user;
        this.modal.edit.isShow = true;
      },
      controlHandleDel(id) {
        this.modal.del.isShow = true;
        this.modal.del.id = id;
      },
      getData(params) {
        return this.rf.getRequest('CategoryRequest').getCategories(params);
      },
      controlRemoveModel() {
        const id = this.modal.del.id;
        return this.delUser({user_id: id});
      },
    },
  }
</script>