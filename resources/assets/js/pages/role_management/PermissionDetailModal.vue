<template>
  <div class="container-fluid">
    <modal :show="show" @close="$emit('close')" :css-footer="{ 'is-justify-center': true }" :title="'Add Role Modal'">
      <template slot="body">
        <div class="wrap-login-form">
          <div class="columns">
            <div class="column has-text-centered">
              <h1 style="font-size: 20px; font-weight: bold;">Detail Permission</h1>
            </div>
          </div>
          <div class="columns">
            <div class="column is-three-fifths is-offset-one-fifth">
              <div class="box">

              </div>
            </div>
          </div>
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
  import CommonHandleModal from "../../common/CommonHandleModal";

  export default {
    name: "PermissionDetail",
    extends: CommonHandleModal,
    methods: {
      async promiseRequest() {
        return await this.rf.getRequest('RoleRequest').addRole(this.params);
      }
    }
  }
</script>

<style scoped>

</style>
