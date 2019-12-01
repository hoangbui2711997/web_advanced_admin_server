<template>
  <div>
    <modal :show="show" @close="$emit('close')" :css-footer="{ 'is-justify-center': true }" :title="'Update Role Modal'">
      <template slot="body">
        <div class="wrap-login-form">
          <div class="columns">
            <div class="column has-text-centered">
              <h1 style="font-size: 20px; font-weight: bold;">Update role</h1>
            </div>
          </div>
          <div class="columns">
            <div class="column is-three-fifths is-offset-one-fifth">
              <table>
                <thead>
                <tr>
                  <th>No</th>
                  <th>Role</th>
                  <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(role, index) in roles">
                  <td>{{ index + 1 }}</td>
                  <td>{{ role.name }}</td>
                  <td>
                    <input type="checkbox"
                           v-model="innerParams.roleChecks[role.id]">
                  </td>
                </tr>
                </tbody>
              </table>
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
  import Modal from "../../../modals/Modal";
  import CommonHandleModal from "../../../common/CommonHandleModal";

  export default {
    name: "AddUserModal",
    components: { Modal },
    props: {
      isUser: {
        default: 1,
      },
    },
    data () {
      return {
        roles: [],
        innerParams: { roleChecks: {} },
      };
    },
    watch: {
      show () {
        if (_.isEmpty(this.roles)) {
          this.getRoles();
        }
        this.innerParams = { roleChecks: {} };
        this.params = { ...this.model };
        _.forEach(this.params.roles, (role) => {
          this.innerParams.roleChecks[role.id] = true;
        });
      }
    },
    extends: CommonHandleModal,
    methods: {
      async getRoles () {
        const {data} = await this.rf.getRequest('EmployeeRequest').getRoles();
        this.roles = data;
      },
      async promiseRequest() {
        return await this.rf.getRequest('EmployeeRequest').updateRoleUser({ ...this.innerParams, id: this.params.id });
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