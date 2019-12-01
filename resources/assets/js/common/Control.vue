<template>
  <div :name="name" v-show="havePermission">
    <slot></slot>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  export default {
    name: "Control",
    props: {
      name: {
        default: '',
      }
    },
    computed: {
      ...mapGetters(['getPermissionPageAction']),
      havePermission () {
        return _.some(this.getPermissionPageAction(this.$route.name), action => `control${action}Handle` === this.name);
      },
    }
  }
</script>

<style scoped>

</style>