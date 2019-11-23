<template>
  <div v-click-outside="hide" class="dropdown-container">
    <div @click="handleClick()">
      <slot/>
    </div>
    <div v-if="visible" class="dropdown-list">
      <slot name="dropdown"/>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        visible: false,
      };
    },
    methods: {
      handleClick () {
        if (this.visible) {
          this.hide();
        } else {
          this.show();
        }
      },
      show () {
        this.visible = true;
      },
      hide () {
        this.visible = false;
        this.$emit('refresh');
      },
    },
    mounted () {
      this.$on('hide-dropdown-menu', this.hide);
    },
  };
</script>

<style lang="scss" scoped>
  .dropdown-container {
    display: inline-block;
    position: relative;
  }
  .dropdown-list {
    top: 42px;
    position: absolute;
    z-index: 10;
    width: 100%;
  }
</style>
