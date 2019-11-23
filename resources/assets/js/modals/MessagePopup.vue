<template>
  <modal name="message-popup" :click-to-close="false" width="430px" height="auto" @before-open="beforeOpened">
    <div class="message-popup">
      <div class="icon-close" @click="closeModal()">
        <i class="iconmo-close"/>
      </div>
      <div class="popup-content text-center">
        <div class="heading-line">
          <i class="iconmo-checked" />
          <h1 v-html="content"></h1>
        </div>
        <button class="btn-back" @click="closeModal()">
          {{ $t('modals.contact.ok') }}
        </button>
      </div>
    </div>
  </modal>
</template>

<script>
  export default {
    name: 'MessagePopup',
    data () {
      return {
        content: '',
      };
    },
    methods: {
      beforeOpened (event) {
        this.content = event.params || {};
        this.$emit('before-opened', event);
      },
      closeModal () {
        this.$modal.hide('message-popup');
      },
      backToHome() {
        // this.$router.push({ name: 'LandingPage'})
      }
    },
  };
</script>

<style lang="scss" scoped>
  @import '../../sass/variables';
  p {
    /deep/ .text-bold-email {
      font-weight: bold;
      color: #333333 !important;
    }
  }
  .message-popup {
    margin-top: 5px;
    text-align: center;
    padding: 15px;
    position: relative;
    .icon-close{
      width: 30px;
      height: 30px;
      background-color: #f2f4f5;
      float: right;
      text-align: center;
      border-radius: 50%;
      padding: 5px;
      cursor: pointer;
      position: absolute;
      top: 15px;
      right: 15px;
      i{
        color: #999;
        font-size: 11px;
      }
    }
    .popup-content{
      padding: 51px 35px 64px;
      .heading-line{
        i{
          font-size: 40px;
          color: $color-primary-blue;
        }
        h1{
          white-space: nowrap;
          font-size: 25px;
          color: $color-primary-blue;
          margin: 23px auto 32px 22px;
          font-weight: normal;
          white-space: normal;
          line-height: 1.5;
          text-align: center;
        }
      }
      p{
        margin-bottom: 32px;
        line-height: 1.7;
      }
      .btn-back{
        background-color: $color-primary-blue;
        color: #fff;
        width: 235px;
        height: 40px;
        text-transform: uppercase;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        outline: none;
        letter-spacing: 0.5px;
        &:hover{
          background-color: #0f8ddb;
          transition: ease-in-out 0.3s;
        }
      }
    }
  }
</style>
