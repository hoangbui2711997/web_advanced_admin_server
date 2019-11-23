<template>
  <div>
    <modal :show="show" @close="$emit('close')" :title="title">
      <template slot="body">
        {{ msg }}
      </template>
      <template slot="footer">
        <button class="button is-info is-normal" @click="handleMethod">Confirm</button>
        <button class="button is-normal" @click="$emit('close')">Cancel</button>
      </template>
    </modal>
  </div>
</template>

<script>
  import Modal from "./Modal";
  export default {
    name: "ConfirmModal",
    components: { Modal },
    props: {
      msg: {
        default: 'Do you really want to delete this user?',
      },
      show: {
        default: false,
        type: Boolean
      },
      title: {
        default: '',
        type: String,
      },
      params: {
        default: '',
      },
      handle: {
        type: Function,
        default: () => {}
      }
    },
    methods: {
      async handleMethod () {
        try {
          const { data } = await this.handle();
          this.showSuccess(data);
          this.onSuccess();
        } catch (e) {
          console.error(e);
        }
      },
      onSuccess () {
        this.$emit('success');
      },
    }
  }
</script>

<style scoped>

</style>