<template>
  <div @click="handleClick">
    <slot/>
  </div>
</template>

<script>
  export default {
    props: {
      params: {
        type: Object,
        default: () => ({}),
      },
    },
    methods: {
      handleClick () {
        this.$parent.$emit('menu-item-click', this.params);
      },
    },
  };
</script>
