<template>
  <div>
    <transition name="modal" v-if="show">
      <div>
        <div class="modal-mask" v-if="configs.mask === true">
        </div>
        <div class="modal show" tabindex="-1" role="dialog">
          <div class="modal-dialog" v-bind:class="configs.position" role="document"
               v-bind:style="{ width: width + 'px'}">
            <div class="modal-content">
              <div class="modal-header" v-if="title">
                <button type="button" class="close" @click="hideModal()" v-if="enableClose"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" v-bind:style="cssTitle" v-html="title"></h4>
              </div>
              <div class="modal-body">
                <slot name="body"></slot>
              </div>
              <div class="modal-footer">
                <table>
                  <tbody>
                  <tr ref="buttons">
                    <td v-for="button in configs.buttons">
                      <button type="button" class="btn-cancel btn btn-default" v-bind:style="button.style"
                              v-bind:class="button.class" @click="button.callback">{{ button.label }}
                      </button>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
      </div>
    </transition>
  </div>
</template>

<script>
  window.CommonModal = {
    show: function(modalName, configs) {
      window.app.$broadcast('showCommonModal', modalName, configs);
    },
    hide: function(modalName) {
      window.app.$broadcast('hideCommonModal', modalName);
    }
  };
  export default {
    props: {
      name: {
        default: 'defaultModal',
        type: String
      },
      title: {
        default: '',
        type: String
      },
      cssTitle: {
        default: () => {
        },
        type: Object
      },
      enableClose: {
        default: true,
        type: Boolean
      },
      width: {
        default: '',
        type: String
      }
    },
    data () {
      return {
        show: false,
        configs: {
          mask: true,
          buttons: [],
          close: null,
        }
      }
    },
    methods: {
      hideModal () {
        window.app.$broadcast('hideCommonModal', this.name);
      },

      focusButton () {
        if (!this.$refs.buttons) {
          return;
        }
        let buttons = this.$refs.buttons.getElementsByTagName('button');
        let index = window._.findIndex(this.configs.buttons, (button) => {
          return button.focused;
        });
        if (index >= 0) {
          buttons[index].focus();
        }
      }
    },
    created () {
      let self = this;
      this.$on('showCommonModal', (modalName, userConfigs) => {
        if (modalName === self.name) {
          self.show = true;
          self.configs = Object.assign(self.configs, userConfigs);
          if (self.configs.onShown) {
            window.setTimeout(function() {
              self.configs.onShown();
            }, 0);
          }

          this.$nextTick(() => {
            this.focusButton();
          });
        }
      });
      this.$on('hideCommonModal', (modalName) => {
        if (modalName === self.name) {
          self.show = false;

          if (self.configs.close) {
            self.configs.close();
          }
        }
      });
    }
  }
</script>

<style lang="scss" scoped>
  .modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .3);
    display: table;
    transition: opacity .3s ease;
  }

  .modal {
    text-align: center;
    padding: 0 !important;
    z-index: 10000;
    pointer-events: none;

    &:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
      margin-right: -4px;
    }

    .modal-dialog {
      vertical-align: middle;
      display: inline-block;
      text-align: left;

      &.bottom-left {
        position: absolute;
        left: 15px;
        bottom: 15px;
        margin: 0;
      }

      &.bottom-right {
        position: absolute;
        right: 15px;
        bottom: 15px;
        margin: 0;
      }

      .modal-content {
        border-radius: 5px;
        pointer-events: auto;

        .modal-header {
          padding: 16px 16px 16px 45px;
          position: relative;

          .close {
            position: absolute;
            right: 14px;
            top: 7px;
            font-size: 27px;
            font-weight: 700;
            color: black;
            text-shadow: none;
            opacity: 0.35;
            &:hover {
              opacity: 0.4;
            }
          }
        }
        .modal-title {
          color: #404040;
          line-height: 3;
          font-size: 14px;
        }

        .modal-body {
          padding: 15px 15px 11px 15px;
        }

        .modal-footer {
          .btn {
            border-radius: 4px;
            padding: 9px 30px;
            color: white;
            border: none;
            font-size: 13px;
          }
          border-top: 0px;
          padding-top: 8px;
          padding-bottom: 40px;
          padding-left: 20px;
          padding-right: 20px;

          table {
            width: 100%;
          }

          td {
            padding-left: 10px;
            padding-right: 10px;
          }

          button {
            width: 100%;
          }
        }
        .btn-cancel {
          background: #808080;
          &:hover {
            background: #8d8d8d;
          }
        }
        .btn-confirm {
          background: #0070C0;
          transition: all ease-out 0.3s;

          &:hover {
            background: #005EA4;
          }
        }
      }
    }
  }
</style>
