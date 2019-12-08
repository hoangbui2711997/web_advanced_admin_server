<template>
  <div class="container-fluid">
    <modal :show="show" @close="$emit('close')" :css-footer="{ 'is-justify-center': true }" :title="'Add User Modal'">
      <template slot="body">
        <div class="wrap-login-form">
          <div class="columns">
            <div class="column has-text-centered">
              <h1 style="font-size: 20px; font-weight: bold;">Messages</h1>
            </div>
          </div>
          <div class="columns">
            <div class="column is-three-fifths is-offset-one-fifth">
              <div class="box">
                <div class="field">
                  <label class="label">Messages of user {{ _.get(model.user, 'name', '') }}</label>
                  <div class="columns" v-for="(message, index) in messages" :key="index">
                    <div class="column">
                      {{ message }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template slot="footer">
        <div class="field is-clearfix">
          <input v-reset-error type="button" class="button is-link is-pulled-right is-normal" value="Submit" @click="$emit('close')">
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
  import CommonHandleModal from "../../../common/CommonHandleModal";

  export default {
    name: "ViewChatRoomModal",
    extends: CommonHandleModal,
    watch: {
      'model.id' (value) {
        if (value) {
          this.messages = [];
          const { data } = this.rf.getRequest('WaitingRoomRequest').getPreviewMessage({ id: this.model.user.id });
          this.messages = data.data;
        }
      }
    },
    data () {
      return {
        messages: [],
      }
    },
    methods: {
      // async promiseRequest() {
        // return await this.rf.getRequest('WaitingRoomRequest').getMessages({...this.params, id: this.model.id});
      // }
    }
  }
</script>

<style scoped>

</style>