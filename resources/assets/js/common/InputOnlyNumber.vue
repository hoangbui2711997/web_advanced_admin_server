<template>
  <input type="text"
         v-number-only
         @paste="onPaste"
         v-model="model"
         :placeholder="placeholder"
         autocomplete="off"
         :maxlength="maxLength"
         :disabled="isDisabled"
         :readonly="isReadonly"
         @keypress="eventKeyPress"/>
</template>

<script>
  export default {
    data () {
      return {
        model: ''
      }
    },
    props: {
      value: '',
      maxLength: {
        default: 18,
      },
      placeholder: {
        default: '',
        type: String,
      },
      isDisabled: {
        default: false,
        type: Boolean,
      },
      isReadonly: {
        default: false,
        type: Boolean,
      }
    },
    watch: {
      value () {
        this.init();
      },
      model (newValue) {
        this.$emit('input', newValue);
      }
    },
    methods: {
      onPaste() {
        this.$nextTick(() => {
          this.model = _.replace(insertNumber(this.model), '.', '');
        });
      },
      eventKeyPress (event) {
        let stringValue = '' + this.value;
        let charCode = (event.which) ? event.which : event.keyCode;
        if ((stringValue.length >= 18) || (charCode === 46) ||
          ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46)) {
          event.preventDefault();
        } else {
          return true;
        }
      },

      init () {
        if (this.value === '' || this.value === undefined || this.value === null) {
          this.model = '';
          return;
        }
        this.model = this.value;
      },
    },
    directives: {
      "number-only": {
        bind (el, binding) {
          el.value = insertNumber(el.value);
          binding.value = el.value;
        },

        inserted (el, bind) {
          el.value = insertNumber(el.value);
          bind.value = el.value;
        },

        update (el, bind) {
          el.value = insertNumber(el.value);
          bind.value = el.value;
        },
      }
    },

    mounted () {
      this.init();
    }
  }

  function insertNumber (newValue) {
    newValue = "" + newValue;
    newValue = newValue.match(/(\d)+(\.)?(\d)?/gi) ? newValue.match(/(\d)+(\.)?(\d)?/gi).join('') : "";
    return newValue;
  }
</script>

<style scoped>

</style>
