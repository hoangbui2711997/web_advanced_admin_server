<template>
  <div class="not-found">
    <div class="not-found-content">
      <div class="icon">
        <span class="iconmo-404"><span class="path1"/><span class="path2"/><span class="path3"/><span class="path4"/><span class="path5"/><span class="path6"/><span class="path7"/><span class="path8"/><span class="path9"/><span class="path10"/><span class="path11"/><span class="path12"/><span class="path13"/><span class="path14"/><span class="path15"/><span class="path16"/><span class="path17"/><span class="path18"/></span>
      </div>
      <div class="text">Page Not Found</div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'NotFound'
  };
</script>

<style lang="scss" scoped>
  .not-found {
    width: 100%;
    height: 100vh;
    background-color: #fff;
    position: relative;
    .not-found-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      .icon {
        display: inline-block;
        margin-right: 60px;
        opacity: 0.5;
        vertical-align: middle;
        .iconmo-404 {
          font-size: 160px;
        }
      }
      .text {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
        font-size: 25px;
        color: #1ea1f2;
        margin: 0 0 10px;
        font-weight: normal;
        opacity: 0.5;
      }
    }
  }
</style>
