<template>
  <div class="container-fluid">
    <modal :show="show" @close="$emit('close')" :css-footer="{ 'is-justify-center': true }" :title="'Add Role Modal'">
      <template slot="body">
        <div class="wrap-login-form">
          <div class="columns">
            <div class="column has-text-centered">
              <h1 style="font-size: 20px; font-weight: bold;">Edit Role</h1>
            </div>
          </div>
          <div class="columns">
            <div class="column is-three-fifths is-offset-one-fifth">
              <div class="box">
                <div class="field">
                  <label class="label" for="name">Role Name</label>
                  <div class="control">
                    <input v-reset-error id="name" class="input" type="text" :class="{
								'is-danger': !_.isEmpty(warning)
							}" placeholder="..." v-model="params.name">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template slot="footer">
        <div class="field is-clearfix">
          <input v-reset-error type="button" class="button is-link is-pulled-right is-normal" value="Submit" @click="handle(promiseRequest())">
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
  import CommonHandleModal from "../../../common/CommonHandleModal";

  export default {
    name: "EditRole",
    extends: CommonHandleModal,
    methods: {
      async promiseRequest() {
        return await this.rf.getRequest('RoleRequest').updateRole({...this.params, id: this.model.id});
      }
    }
  }
</script>

<style scoped>

</style>