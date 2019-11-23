<template>
  <div>
    <slot/>
  </div>
</template>

<script>
  export default {
    methods: {
      handleMenuItemClick (params) {
        this.$parent.$emit('hide-dropdown-menu', params);
      },
    },
    mounted () {
      this.$on('menu-item-click', this.handleMenuItemClick);
    },
  };
</script>

<style scoped>

</style>
