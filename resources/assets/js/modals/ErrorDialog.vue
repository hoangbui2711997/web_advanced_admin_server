<template>
  <div>
    <modal name="error-dialog"
           width="550px"
           height="auto"
           :class="params.class"
           @before-open="beforeOpened"
           @before-close="beforeClosed"
           @opened="$emit('opened', $event)"
           @closed="$emit('closed', $event)">
      <div class="trigger-btn" @click="closeModal">
        <i class="iconmo-close"/>
      </div>
      <div class="confirm-modal-content">
        <div class="top">
          <span class="iconmo-information"/>
          <p class="text" v-html="params.title || ''"/>
        </div>
        <div class="middle">
          <p class="title" v-html="params.text || ''"/>
        </div>
        <div class="bottom">
          <div v-if="buttons" class="button-group">
            <span
              v-for="(button, i) in buttons"
              :class="button.class || ''"
              :style="button.style ? button.style : ''"
              class="button-item button-01"
              @click.stop="click(i, $event)"
              v-html="button.title">
              {{ button.title }}
            </span>
          </div>
        </div>
      </div>
    </modal>
  </div>
</template>


<script>
  export default {
    name: "ErrorDialog",
    data () {
      return {
        params: {},
      };
    },
    computed: {
      buttons () {
        return this.params.buttons || [];
      },
    },
    methods: {
      beforeOpened (event) {
        window.addEventListener('keyup', this.onKeyUp);
        this.params = event.params || {};
        this.$emit('before-opened', event);
      },
      beforeClosed (event) {
        window.removeEventListener('keyup', this.onKeyUp);
        // this.params = {};
        this.$emit('before-closed', event);
      },
      click (i, event, source = 'click') {
        const button = this.buttons[i];
        if (button && typeof button.handler === 'function') {
          button.handler(i, event, { source });
        } else {
          this.$modal.hide('error-dialog');
        }
      },
      closeModal () {
        this.$modal.hide('error-dialog');
      },
    },
  }
</script>

<style lang="scss" scoped>
  .confirm-modal-content {
    padding: 60px;
    box-shadow: 0 2px 25px 0 rgba(0, 0, 0, 0.31);
  }

  .modify-02 {
    .iconmo-information {
      display: none !important;
    }

    .top .text {
      margin-bottom: 20px;
    }

    .middle .title {
      font-size: 16px;
      font-weight: 700;
      line-height: 1.88;
      color: #333333;
    }
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

  .top {
    text-align: center;
    color: #1ea1f2;

    .iconmo-information {
      display: inline-block;
      margin-bottom: 16px;
      font-size: 40px;
      color:  #d0021b;
    }
    .text {
      font-size: 25px;
      line-height: 1.16;
      color: #1ea1f2;
      margin-bottom: 4px;
    }
  }

  .middle {
    margin-bottom: 25px;
    .title {
      text-align: center;
      font-size: 13px;
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

    &.button-01--02 {
      background-color: #1ea1f2;
      color: #fff;
    }
  }
</style>
