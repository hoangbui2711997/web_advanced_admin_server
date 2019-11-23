<template>
  <div class="">
    <div class="content-page w-88 m-auto w-max">
      <div class="heading-title">Products</div>
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
            <th>Rate</th>
            <th>Rate Amount</th>
            <th>Price</th>
            <th>Price Base</th>
            <th>Amount In Stock</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Control</th>
            <template slot="body" slot-scope="props">
              <tr>
                <td>{{ props.index }}</td>
                <td>{{ _.get(props.item, 'name') }}</td>
                <td>{{ _.get(props.item, 'rate') }}</td>
                <td>{{ _.get(props.item, 'rate_amount') }}</td>
                <td>{{ _.get(props.item, 'price') }}</td>
                <td>{{ _.get(props.item, 'price_base') }}</td>
                <td>{{ _.get(props.item, 'amount_in_stock') }}</td>
                <td>{{ _.get(props.item, 'updated_at') }}</td>
                <td>{{ _.get(props.item, 'created_at') }}</td>
                <td>
                  <div class="button is-primary is-small" @click="controlShowProduct(props.item)" :id="`${$route.name}|control_show_product`">Detail</div>
                  <div class="button is-warning is-small" @click="controlHandleEdit(props.item)" :id="`${$route.name}|control_handle_edit`">Edit</div>
                  <div class="button is-danger is-small" @click="controlHandleDel(props.item.id)" :id="`${$route.name}|control_handle_del`">Delete</div>
                </td>
              </tr>
            </template>
          </data-table>
        </div>
      </div>
    </div>
    <confirm-modal
      :show="modal.del.isShow"
      :id="modal.del.id"
      @close="modal.del.isShow = false"
      :msg="'Do you really want to delete this product?'"
      :title="'Delete Product'"
      :handle="controlRemoveProduct"
      @success="fetchData"
    ></confirm-modal>
  </div>
</template>

<script>
  import rf from '../../requests/RequestFactory';
  import ConfirmModal from "../../modals/ConfirmModal";

  export default {
    name: "ProductPage",
    components: { ConfirmModal },
    data () {
      return {
        modal: {
          del: {
            isShow: false,
            id: '',
          },
        },
      }
    },
    methods: {
      controlShowProduct (item) {
        // this.$router.push({name: 'products-slug', params: { slug: item.id }})
        this.$router.push({ name: 'DetailProductPage:id', params: { id: item.id } });
      },
      controlHandleAdd () {
        this.$router.push({ name: 'AddProductPage' });
      },
      controlHandleEdit (item) {
        this.$router.push({ name: 'EditProductPage:id', params: { id: item.id } });
      },
      controlHandleDel (id) {
        this.modal.del.isShow = true;
        this.modal.del.id = id;
      },
      closeAllPopup () {
        // this.modal.add.isShow = false;
        this.modal.del.isShow = false;
        // this.modal.edit.isShow = false;
      },
      controlRemoveProduct () {
        return this.rf.getRequest('ProductRequest').delProduct({ id: this.modal.del.id });
      },
      fetchData () {
        this.closeAllPopup();
        this.$refs.datatable.fetch();
      },
      getData (params) {
        return rf.getRequest('ProductRequest').getProducts(params);
      },
    }
  }
</script>

<style scoped>

</style>