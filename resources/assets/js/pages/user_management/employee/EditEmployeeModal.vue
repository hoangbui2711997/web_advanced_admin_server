<template>
  <div>
    <modal :show="show" @close="$emit('close')" :css-footer="{ 'is-justify-center': true }" :title="'Edit User Modal'">
      <template slot="body">
        <div class="wrap-login-form">
          <div class="columns">
            <div class="column has-text-centered">
              <h1 style="font-size: 20px; font-weight: bold;">Edit User</h1>
            </div>
          </div>
          <div class="columns">
            <div class="column is-three-fifths is-offset-one-fifth">
              <form class="box">
                <div class="field">
                  <label class="label" for="edit_name">User name</label>
                  <div class="control">
                    <input id="edit_name" class="input" type="text" placeholder="User name" v-model="params.name" v-reset-error>
                  </div>
                </div>
                <div class="field">
                  <label class="label" for="edit_email">Email</label>
                  <div class="control">
                    <input id="edit_email" class="input" type="email" :class="{
								'is-danger': !_.isEmpty(warning)
							}" placeholder="e.g. yourname@gmail.com" v-model="params.email" v-reset-error>
                  </div>
                </div>
                <div class="field">
                  <label class="label" for="edit_password">Password</label>
                  <div class="control">
                    <input id="edit_password" class="input" :class="{
								'is-danger': !_.isEmpty(warning)
							}" type="password" v-model="params.password" placeholder="Password" v-reset-error>
                  </div>
                </div>
                <div v-if="!_.isEmpty(warning)" class="notification is-danger">
                  <div v-for="(value, key) in warning" :key="key">
                    {{ value }}
                  </div>
                </div>
<!--                <div v-if="!_.isEmpty(notification)" class="notification is-success">-->
<!--                  {{ notification }}-->
<!--                </div>-->
              </form>
            </div>
          </div>
        </div>
      </template>
      <template slot="footer">
        <div class="field is-clearfix">
          <input type="submit" class="button is-link is-pulled-right is-normal" value="Submit" @click.prevent="handle(promiseRequest())">
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
  import Modal from "../../../modals/Modal";
  import CommonHandleModal from "../../../common/CommonHandleModal";

  export default {
    name: "EditUserModal",
    components: { Modal },
    extends: CommonHandleModal,
    methods: {
      async promiseRequest() {
        return await this.rf.getRequest('EmployeeRequest').editUser({ ...this.params });
      }
    }
  }
</script>

<style scoped lang="scss">
</style>