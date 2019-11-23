<template>
  <div class="admin-page">
    <router-view/>
  </div>
</template>
<script>
  export default {

  };
</script>

<style lang="scss">
  .admin-page {
    height: 100%;
  }

    ::-webkit-scrollbar {
      width: 8px;
  }

  ::-webkit-scrollbar-track {
      background: #f1f1f1;
  }

  ::-webkit-scrollbar-thumb {
      background: #ddd;
  }

  ::-webkit-scrollbar-thumb:hover {
      background: #ccc;
  }
</style>
