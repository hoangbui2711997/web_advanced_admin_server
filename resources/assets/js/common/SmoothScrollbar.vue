<template>
  <div ref="scrollArea" class="smooth-scrollbar">
    <slot/>
  </div>
</template>

<script>
  import Scrollbar from 'smooth-scrollbar'
  import SmoothScrollbar from 'smooth-scrollbar'
  import OverscrollPlugin from 'smooth-scrollbar/plugins/overscroll';

  Scrollbar.use(OverscrollPlugin);

  const options = {
    damping: 0.1,
    thumbMinSize: 20,
    renderByPixels: true,
    alwaysShowTracks: false,
    continuousScrolling: true,
    delegateTo: null,
    plugins: {
      overscroll: {
        enable: true,
        effect: "glow",
        glowColor: "#222a2d",
        damping: 0.2,
        maxOverscroll: 150,
      },
    },
  };

  export default {
    name: "SmoothScrollbar",
    props: {
      defaultOptions: {
        type: Object,
        required: false,
        default: () => options
      },
      globalOptions: {
        type: Object,
        required: false,
        default: () => ({})
      },
      options: {
        type: Object,
        required: false,
        default: () => ({})
      }
    },
    data () {
      return {

      }
    },
    mounted() {
      this.scrollbar = SmoothScrollbar.init(
        this.$refs.scrollArea,
        Object.assign({}, this.defaultOptions, this.globalOptions, this.options)
      )
    },
    destroyed() {
      this.scrollbar.destroy();
    }
  }
</script>

<style scoped>
  .smooth-scrollbar {
    width: 100%;
    max-width: 100vw;
    height: 100%;
    max-height: 100vh;
  }

  .columns {
    max-width: 100vw;
  }
</style>