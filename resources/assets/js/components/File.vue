<template>
  <div class="wrap_file" ref="wrap">
    <input type="file"
           :id="nameFile"
           ref="files"
           class="file_input"
           data-vv-validate-on="none"
           v-validate="strValidate"
           :name="nameValidation"
           :style="customStyle"
           :class="isOverlay ? 'input_overlay' : ''"
           :accept="accept"
           :multiple="allowMultiple"
           @click="resetErrors"
           @change="onChangeFile($refs.files)">
    <div :class="{
             'is_overlay': isOverlay,
             'active-warning': errors.has(nameValidation)
         }">
      <div class="wrap-file_content">
        <slot name="content" :file-name="filesName">
        </slot>
        <label :for="nameFile" id="inner_label" ref="innerLabel" class="btn btn_upload">
          {{ btnName }}
        </label>
        <slot name="file-name" :file-name="filesName">
          <div v-if="isShowFileName" class="wrap-file_content-name">{{ filesName }}</div>
        </slot>
        <slot name="errors" :errors="errors">
          <div v-if="isShowError" class="warning-message">
            <span>{{ errors.first(nameValidation) }}</span>
          </div>
        </slot>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "File",
    props: {
      value: {
        type: FileList | File,
      },
      allowMultiple: {
        default: false,
        type: Boolean,
      },
      accept: {
        default: 'image/png, image/jpeg',
        type: String,
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
        default: 'files',
        type: String,
      },
      isShowError: {
        default: false,
        type: Boolean,
      },
      isShowFileName: {
        default: false,
        type: Boolean,
      },
      customStyle: {
        default: '',
        type: String,
      },
      btnName: {
        default: 'Choose file',
        type: String,
      },
      isOverlay: {
        default: false,
        type: Boolean,
      },
      isImageOverlay: {
        default: false,
        type: Boolean,
      },
    },
    watch: {
      files (value) {
        if (_.isEmpty(value)) {
          this.removeImage();
        }
      },
    },
    computed: {
      filesName () {
        if (!this.allowMultiple) {
          return this.value.name;
        }
        return _.map(this.value, f => f.name).join(', ');
      },
    },
    methods: {
      removeImage () {
        this.$refs.files.value = '';
      },
      onChangeFile ({ files }) {
        if (!this.allowMultiple) {
          this.$emit('input', _.first(files) || files);
        } else {
          this.$emit('input', files);
        }
      },
      async validateFile () {
        return await this.$validator.validateAll();
      },
      resetErrors () {
        this.errors.clear();
      },
    },
  }
</script>

<style scoped lang="scss">
  .file_input {
    display: none;
  }
  .btn_upload {
    color: #fff;
    font-weight: 500;
    padding: 6px 10px;
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
    min-width: 135px;

    &:hover {
      background-color: #049af7;
    }

    position: relative;
    z-index: 2;
  }
  .wrap_file {
    position: relative;
    width: 100%;
    height: 100%;
    .wrap-file_content {
      position: relative;
      text-align: center;
      height: 100%;
      width: 100%;
    }
  }
  .warning-message span {
    top: 8px;
    font-weight: normal;
  }

  .is_overlay {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
  }
  .input_overlay {
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;
    display: block;
    z-index: 5;
    opacity: 0;
  }
</style>
