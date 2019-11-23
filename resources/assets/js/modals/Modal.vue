<template>
  <div class="modal" :class="{ 'is-active': show }">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">{{ title }}</p>
        <button class="delete" aria-label="close" @click="$emit('close')"></button>
      </header>
      <smooth-scrollbar>
        <section class="modal-card-body">
          <slot name="body"></slot>
        </section>
      </smooth-scrollbar>
      <footer class="modal-card-foot" :class="cssFooter">
        <slot name="footer"></slot>
      </footer>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Modal",
    props: {
      show: {
        default: false,
        type: Boolean
      },
      cssFooter: {},
      title: {
        default: 'Modal title',
        type: String,
      },
    }
  }
</script>

<style scoped>
  .modal {
    justify-content: normal;
    padding-top: 40px;
  }
</style>