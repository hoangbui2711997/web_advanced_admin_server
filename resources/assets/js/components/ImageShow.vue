<template>
  <img ref="img" v-img="getData()" :src="url"/>
</template>

<script>
  const LARGE_WINDOW_SIZE = 991;

  export default {
    name: "ImageShow",
    props: {
      src: {
        type: String,
        default: '',
      },
      group: {
        type: String,
        default: '1'
      }
    },
    computed: {
      url() {
        return this.src;
      },
    },
    methods: {
      getData () {
        const params = {
          cursor: 'pointer',
          thumbnails: true,
          sourceButton: false,
          group: this.group,
          src: this.src,
          opened: () => {
            this.$nextTick(() => {
              const element = document.querySelector('.fullscreen-v-img');
              if(!!element) {
                if(window.innerWidth > LARGE_WINDOW_SIZE) {
                  element.style.height = "calc(100% - 50px)";
                  element.style.margin = "50px 0 0 220px";
                  element.style.width = "calc(100% - 220px)";
                } else {
                  element.style.margin = "0";
                  element.style.width = "100%";
                  element.style.height = "100%";
                }
                element.style.transition = "all ease-in-out 0.3s";
              }
            });
          }
        };
        params.src = this.url;
        return params;
      },
    },
  }
</script>

<style scoped>
</style>
