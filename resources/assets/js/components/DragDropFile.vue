<template>
  <div class="wrap-drag-drop-file" ref="dragDrop">
    <div v-if="isImageOverlay" :class="{ 'is_image_overlay': isImageOverlay }" class="image_overlay">
      <div :style="`background-image: url(${urlImage})`" class="img_background"></div>
      <div class="trigger-btn" @click="removeImage">
        <i class="iconmo-close"/>
      </div>
    </div>
    <div class="box-content" v-else>
      <file
        v-model="files"
        :allow-multiple="allowMultiple"
        :strValidate="strValidate"
        :is-overlay="true"
        :name-validation="nameValidation"
        :btn-name="btnName"
        :accept="accept"
        ref="files"
      >
        <template slot="content" slot-scope="props">
          <div style="padding-top: 37px;">
            <div>
              <img src="/images/image-upload-file.png">
            </div>
            <div class="content-name">
              <template v-if="!!props.fileName">
                {{ props.fileName }}
              </template>
              <template v-else>
                {{ $t('create_campaign_page.drag_and_drop_files_to_upload') }}
              </template>
            </div>
          </div>
        </template>
        <template slot="errors" slot-scope="props">
          <div class="warning-message" style="bottom: 6px;">
            <span v-if="!!props.errors">{{ props.errors.first(nameValidation) }}</span>
          </div>
        </template>
      </file>
    </div>
  </div>
</template>

<script>
  import File from "./File";

  export default {
    name: "DragDropFile",
    components: { File },
    props: {
      value: {
        default: [],
        type: FileList | File,
      },
      isImageOverlay: {
        default: false,
        type: Boolean,
      },
      strValidate: {
        default: '',
        type: String | Object,
      },
      nameValidation: {
        default: 'file',
        type: String,
      },
      nameFile: {
        type: String,
        default: 'files'
      },
      btnName: {
        default: 'Choose a file',
        type: String,
      },
      accept: {
        default: 'image/png, image/jpeg',
        type: String,
      },
      allowMultiple: {
        default: false,
        type: Boolean,
      },
      urlImage: {
        default: '',
        type: String,
      }
    },
    watch: {
      files () {
        if (!this.allowMultiple && !_.isArray(this.files)) {
          const self = this;
          if (this.files) {
            const reader = new FileReader();
            reader.onload = function(e) {
              this.urlImage = e.target.result;
              self.$emit('base64-image', this.urlImage);
            };

            reader.readAsDataURL(this.files);
          }
        }
        return this.$emit('input', this.files);
      },
    },
    data () {
      return {
        files: [],
        isOnDrag: false,
      };
    },
    methods: {
      async validateFile () {
        if (!this.$refs || !this.$refs.files) {
          return false;
        }
        return await this.$refs.files.validateFile();
      },
      resetErrors () {
        if (!!this.$refs && !!this.$refs.files) {
          this.$refs.files.resetErrors();
        }
      },
      removeImage () {
        this.$emit('removeImage');
        this.files = [];
      },
    }
  }
</script>

<style scoped lang="scss">
  .box-content {
    height: 100%;
    width: 100%;
  }
  .wrap-drag-drop-file {
    width: 100%;
    height: 100%;
    .image_overlay {
      height: 100%;
      width: 100%;
      .img_background {
        height: 100%;
        width: 100%;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: 50% 50%;
      }
      &.is_image_overlay {
        z-index: 10;
      }
      img {
        height: 100%;
        width: 100%;
        position: absolute;
        z-index: 1;
      }
      i {
        z-index: 5;
      }
    }
    &:hover {
      /deep/ .file_input {
        z-index: 0;
      }
      .trigger-btn {
        opacity: 1;
        visibility: visible;
      }
    }
    .trigger-btn {
      position: absolute;
      visibility: hidden;
      opacity: 0;
      transition: all 0.3s ease-in-out;
      right: 15px;
      top: 15px;
      width: 30px;
      height: 30px;
      background-color: #f2f4f5;
      border-radius: 50%;
      text-align: center;
      cursor: pointer;
      z-index: 5;

      .iconmo-close {
        font-size: 11px;
        line-height: 30px;
        color: #999;
      }
    }
  }
  .btn-upload {
    color: #fff;
    font-weight: 500;
    padding: 6px 0;
    margin: auto auto 15px auto;
    text-transform: uppercase;
    background-color: #1ea1f2;
    border: solid thin transparent;
    border-radius: 5px;
    outline: none;
    text-align: center;
    letter-spacing: 0.5px;
    transition: all 0.3s ease-in-out;
    cursor: pointer;
    font-size: 12px;
    line-height: 20px;
    height: 32px;
    width: 135px;
  }
  .content-name {
    color: rgb(102, 102, 102);
    font-size: 20px !important;
    opacity: 0.5;
    text-align: center;
    font-weight: normal;
    max-width: 90%;
    margin: auto auto 20px auto;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow-x: hidden;
  }
</style>
