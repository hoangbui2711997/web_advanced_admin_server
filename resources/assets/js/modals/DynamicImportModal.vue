<template>
  <div>
    <modal :show="show" @close="$emit('close')" :css-footer="{ 'is-justify-center': true }" :title="'Detail Control'">
      <template slot="body">
        <div v-for="control in innerMapControls">
          <input type="checkbox" v-model="control.checked"> {{ control.name }}
        </div>
      </template>
      <template slot="footer">
        <div class="field is-clearfix">
          <input v-reset-error type="button" class="button is-link is-pulled-right is-normal" value="Register" @click="handle(promiseRequest())">
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
  import UserPage from '../pages/user_management/UserPage';
  import ProductPage from "../pages/product_management/ProductPage";
  import CategoryPage from "../pages/single_table_management/category_management/CategoryPage";
  import EmployeePage from "../pages/user_management/EmployeePage";
  import AddProductPage from "../pages/product_management/AddProductPage";
  import EditProductPage from "../pages/product_management/EditProductPage";
  import DetailProductPage from "../pages/product_management/DetailProductPage";
  import ListRoute from "../pages/route/ListRoute";
  import CommonHandleModal from "../common/CommonHandleModal";
  import ChatRoomPage from "../pages/chat_management/ChatRoomPage";
  import WaitingRoomPage from "../pages/chat_management/waiting_chat/WaitingRoomPage";

  export default {
    name: 'DynamicImportModal',
    extends: CommonHandleModal,
    props: {
      name: {
        default: '',
      },
      mapControls: {
        default: () => [],
      },
      permissionName: {
        default: '',
      },
      roleId: {
        default: '',
      }
    },
    data () {
      const mapPermission = {
        UserPage,
        ProductPage,
        CategoryPage,
        EmployeePage,
        AddProductPage,
        EditProductPage,
        DetailProductPage,
        RolePermission: this.$parent,
        ListRoute,
        ChatRoomPage,
        WaitingRoomPage
      };
      return {
        innerMapControls: {},
        mapPermission,
      };
    },
    mounted () {
      console.log("mounted");
      this.init();
    },
    watch: {
      name () {
        this.$nextTick(() => {
          this.$set(this.$data, 'innerMapControls', {});
          this.init();
        });
      },
      roleId () {
        this.innerMapControls = {};
        this.init();
      },
    },
    methods: {
      init () {
        let method = this.name === 'RolePermission' ? this.mapPermission[this.name].$options.methods : this.mapPermission[this.name].methods;
        if (!this.mapPermission[this.name] || !method) {
          return;
        }
        console.log(this.mapPermission[this.name], "this.mapPermission[this.name]");
        const fns = _.filter(Object.keys(method), (functionName) => {
          return _.startsWith(functionName, 'control') && _.endsWith(functionName, 'Handle');
        });

        if (!_.isEmpty(fns)) {
          this.innerMapControls = _.map(fns, (control) => {
            return { checked: false, name: control.replace(/control|Handle/g, '') };
          });
          console.log(this.innerMapControls, "this.innerMapControls__");
        }
        this.innerMapControls = { ..._.keyBy(this.innerMapControls, 'name'), ..._.keyBy(this.mapControls, 'name') };
        console.log(this.innerMapControls, "this.innerMapControls");
      },
      // getControls () {
      //   console.log(this, "this");
      //   if (_.isEmpty(this.$children) || !this.$children[1]) {
      //     return;
      //   }
      //   console.log(this.$children, "this.$children");
      //   const fns = _.filter(Object.keys(this.$children[1].$options.methods), (functionName) => {
      //     return _.startsWith(functionName, 'control') && _.endsWith(functionName, 'Handle');
      //   });
      //
      //   if (!_.isEmpty(fns)) {
      //     this.mapControls = _.map(fns, (control) => {
      //       return control.replace(/control|Handle/g, '');
      //     });
      //   }
      // },
      async promiseRequest() {
        return this.rf.getRequest('PermissionRequest').updatePermissionControl({
            role_id: this.roleId,
            permission_name: this.permissionName,
            role_permission_name: this.name,
            controls: this.innerMapControls
          }
        );
      },
    }
  }
</script>

<style scoped>

</style>