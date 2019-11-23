<template>
  <div>
    <!--POPUP CONFIRM-->
    <modal
      :click-to-close="false"
      name="warning-dialog"
      width="440px"
      height="auto"
      class="warning-login-modal"
      transition="easeInOut"
      @before-open="beforeOpened"
      @before-close="beforeClosed"
      @opened="$emit('opened', $event)"
      @closed="$emit('closed', $event)">
      <div class="warning-login-modal-content">
        <div class="middle">
          <p class="title">{{ message }}</p>
        </div>
        <div class="bottom">
          <div class="button-group">
            <span
              class="button-item button-01 button-01--02"
              @click.stop="closeModal()">
              {{ $t('modals.warning_dialog.close') }}
            </span>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
  export default {
    name: 'WarningDialog',
    data () {
      return {
        message: '',
      };
    },
    methods: {
      beforeOpened (event) {
        this.message = event.params;
        this.$emit('before-opened', event);
      },
      beforeClosed (event) {
        this.$emit('before-closed', event);
      },
      closeModal () {
        this.$modal.hide('warning-dialog');
      },
    },
  };
</script>

<style lang="scss" scoped>
  .warning-login-modal-content {
    padding: 70px 60px;
    box-shadow: 0 2px 25px 0 rgba(0, 0, 0, 0.31);
  }

  .v--modal {
    overflow: visible !important;
    box-shadow: none !important;
  }

  .trigger-btn {
    position: absolute;
    right: 15px;
    top: 15px;
    width: 30px;
    height: 30px;
    background-color: #f2f4f5;
    border-radius: 50%;
    text-align: center;
    cursor: pointer;

    .iconmo-close {
      font-size: 11px;
      line-height: 30px;
      color: #999;
    }
  }

  .middle {
    margin-bottom: 25px;
    .title {
      text-align: center;
      font-size: 20px;
      color: #333333;
    }
  }

  .button-group {
    text-align: center;

    .button-item {
      margin: 0 5px;
      vertical-align: middle;
    }
  }

  .button-01 {
    display: inline-block;
    width: 130px;
    font-size: 14px;
    padding: 9px 0;
    letter-spacing: 0.5px;
    text-align: center;
    color: #1ea1f2;
    border-radius: 5px;
    border: 1px solid #1ea1f2;
    cursor: pointer;
    transition: all 0.3s ease-in-out;

    &:hover {
      color: #fff;
      background-color: #1ea1f2;
    }

    &.button-01--02 {
      background-color: #1ea1f2;
      color: #fff;

      &:hover {
        background-color: #0f8ddb;
      }
    }
  }
</style>
