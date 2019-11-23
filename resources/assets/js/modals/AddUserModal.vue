<template>
  <div>
    <modal :show="show" @close="$emit('close')" :css-footer="{ 'is-justify-center': true }" :title="'Add User Modal'">
      <template slot="body">
        <div class="wrap-login-form">
          <div class="columns">
            <div class="column has-text-centered">
              <h1 style="font-size: 20px; font-weight: bold;">Add User</h1>
            </div>
          </div>
          <div class="columns">
            <div class="column is-three-fifths is-offset-one-fifth">
              <div class="box">
                <div class="field">
                  <label class="label" for="name">User name</label>
                  <div class="control">
                    <input v-reset-error id="name" class="input" type="text" :class="{
								'is-danger': !_.isEmpty(warning)
							}" placeholder="..." v-model="params.name">
                  </div>
                </div>
                <div class="field">
                  <label class="label" for="add_email">Email</label>
                  <div class="control">
                    <input v-reset-error id="add_email" class="input" type="email" :class="{
								'is-danger': !_.isEmpty(warning)
							}" placeholder="e.g. yourname@gmail.com" v-model="params.email">
                  </div>
                </div>
                <div class="field">
                  <label class="label" for="add_password">Password</label>
                  <div class="control">
                    <input v-reset-error id="add_password" class="input" :class="{
								'is-danger': !_.isEmpty(warning)
							}" type="password" v-model="params.password">
                  </div>
                </div>
                <div class="field">
                  <label class="label" for="add_repeat_password">Repeat Password</label>
                  <div class="control">
                    <input v-reset-error id="add_repeat_password" class="input" :class="{
								'is-danger': !_.isEmpty(warning)
							}" type="password" v-model="params.password_confirmation">
                  </div>
                </div>
                <div v-if="!_.isEmpty(warning)" class="notification is-danger">
                  <div v-for="(value, key) in warning" :key="key">
                    {{ value }}
                  </div>
                </div>
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
  import Modal from "./Modal";
  import CommonHandleModal from "../common/CommonHandleModal";

  export default {
    name: "AddUserModal",
    components: { Modal },
    props: {
      isUser: {
        default: 1,
      },
    },
    extends: CommonHandleModal,
    methods: {
      async promiseRequest() {
        console.log( ...this.params, ...{ is_user: this.isUser }, " ...this.params, ...{ is_user: this.isUser }");
        return await this.rf.getRequest('AdminRequest').addUser({ ...this.params, ...{ is_user: this.isUser }});
      }
    }
  }
</script>

<style scoped lang="scss">
  .label {
    color: black !important;
    opacity: 0.7;
    padding-left: 0;
    text-align: left;
  }
</style>