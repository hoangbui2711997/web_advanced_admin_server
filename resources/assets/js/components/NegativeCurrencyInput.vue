<template>
    <div style="position: relative;display: inline-block; width: 100%">
        <masked-input
          ref="input"
          :mask="createNumberMask"
          :placeholder="' '"
          :guide="false"
          v-model="internalValue"
          type="text"
          :class="{ 'negative': isNegative }"
          class="form-control currency-input input-negative-curreny"
          @paste.native="onPaste"
          @keypress="onKeyPress"
          @keyup.native="onKeyUp"
          @focus="onFocus"
          @blur="onBlur"/>
    </div>
</template>
<script>

  import MaskedInput from 'vue-text-mask';
  import BigNumber from 'bignumber.js';

  export default {
    components: {
      MaskedInput,
    },
    props: {
      value: '',
      focusHandler: {
        default () {
        },
      },
      precision: {},
    },
    data () {
      return {
        internalValue: '',
        internalPrecision: 0,
        isPasted: false,
        isNegative: false,
      };
    },
    watch: {
      value (val) {
        if (val === '0') {
          this.internalValue = '0';
          return;
        }
        if (val === undefined || val === '' || !val) {
          this.internalValue = undefined;
          return;
        }

        if (`${val}`.length !== new BigNumber(`${val}`).toString().length) {
          this.internalValue = new BigNumber(val).toString();
        }

        if (new BigNumber(val).comparedTo(this.internalValue) === 0) {
          return;
        }
        if (val !== '' && val !== this.internalValue) {
          this.updateValue(val);
        }
        if (new BigNumber(val).comparedTo(0) < 0 && new BigNumber(this.standardize(this.internalValue)).comparedTo(0) > 0) {
            this.internalValue = new BigNumber(this.standardize(this.internalValue)).times(-1).toString();
        }
      },
      internalValue (val) {
        if ((val === undefined || val === '' || parseInt(val) === 0) &&  val !== this.value) {
          this.$emit('input', this.handleValue(val));
          return;
        }
        const standardizedValue = this.standardize(val);
        const formattedValue = this.formatNumber(standardizedValue);

        if (val !== formattedValue) {
          this.internalValue = formattedValue;
          this.$refs.input.$el.value = this.internalValue;
          return;
        }

        if (this.standardize(val || '0') !== this.standardize(this.value || '0')) {
          this.updateValue(val);
        }
      },
    },
    created () {
      BigNumber.config({ EXPONENTIAL_AT: 21 });
      this.internalPrecision = this.precision;
      this.internalValue = this.value || '';
      if (this.internalValue !== '' && this.internalValue !== undefined ) {
        this.internalValue = new BigNumber(`${this.internalValue}`).toString();
      }
    },
    mounted () {
      this.$refs.input.$refs.input.autocomplete = 'off';
      this.$refs.input.$refs.input.maxLength = 21;
      this.createMinus();
    },
    methods: {
      createMinus () {
        window.$(this.$refs.input.$refs.input).after('<span>-</span>');
      },
      onPaste (event) {
        const value = (event.clipboardData || window.clipboardData).getData('Text');
        if (!this.isNumber(value)) {
          event.preventDefault();
        }
        this.isPasted = true;
      },
      onKeyPress (event) {
        if (this.isFullWidthChar(event.key)) {
          event.preventDefault();
        }

        const rawValue = this.standardize(this.internalValue);
        const formatedValue = this.formatNumber(rawValue);

        if (formatedValue.length > 20) {
          event.preventDefault();
        } else {
          return true;
        }
      },
      isFullWidthChar (charCode) {
        return (0xFF00 < charCode && charCode < 0xFF5F) || 0x3000 === charCode;
      },
      onKeyUp (event) {
        if (event.target.selectionStart === 0) {
          if (event.key === '-') {
            this.isNegative = !this.isNegative;
          }
          if (event.key === 'Backspace') {
            this.isNegative = false;
          }
          if (this.isNegative && new BigNumber(this.standardize(this.internalValue)).comparedTo(0)> 0) {
              const internalValue = new BigNumber(this.standardize(this.internalValue)).times(-1).toString();
              return this.$emit('input', this.handleValue(internalValue));
          }
        }

        const charCode = (event.which) ? event.which : event.keyCode;
        if (charCode === 190 && (this.internalValue === '' || this.internalValue === undefined)) {
          this.internalValue = '0.';
        }
      },
      createNumberMask (value) {
        const standardizedValue = this.standardize(value);
        let formatedValue = this.formatNumber(standardizedValue);
        if (this.isPasted) {
          formatedValue = formatedValue.length > 20 ? formatedValue.substring(0, 21) : formatedValue;
          this.isPasted = false;
        }

        const result = [];
        for (let i = 0; i < formatedValue.length; i++) {
          const char = formatedValue.charAt(i);
          if (char >= '0' && char <= '9') {
            result.push(/\d/);
          } else if (char === '.') {
            result.push(/\./);
          } else {
            result.push(char);
          }
        }
        return result;
      },

      updateValue (value) {
        if (value === undefined) {
          this.internalValue = undefined;
          return;
        }

        const stringValue = value ? this.removeExponent(value.toString()) : value;
        const standardizedValue = this.standardize(stringValue);
        const formattedValue = this.formatNumber(standardizedValue);
        this.internalValue = formattedValue;
        const newValue = standardizedValue ? new BigNumber(standardizedValue) : undefined;

        if (this.formatNumber(this.standardize(this.internalValue)).length > 21 && this.formatNumber(this.standardize(this.value)).length < 21) {
          this.$refs.input.$el.value = this.value;
          return this.internalValue = this.value;
        }

        if ((newValue === undefined && newValue !== value && value !== '') || (value && !value.isBigNumber) ||
          (newValue && !newValue.eq(value))) {
          if ((newValue === undefined && newValue !== value && value !== '' && this.internalValue === '')) {
            return this.$emit('input', this.handleValue(newValue));
          }
          this.$emit('input', this.handleValue(new BigNumber(newValue).toString()));
        } else if (this.internalValue === '' && value === '' && this.value !== this.internalValue) {
          return this.$emit('input', '');
        }
      },

      removeExponent (data) {
        const result = String(data).split(/[eE]/);
        if (result.length === 1) {
          return result[0];
        }

        let tail = '';
        const sign = this < 0 ? '-' : '';
        const str = result[0].replace('.', '');
        let mag = Number(result[1]) + 1;

        if (mag < 0) {
          tail = `${sign}0.`;
          while (mag++) {
            tail += '0';
          }
          return tail + str.replace(/^\-/, '');
        }
        mag -= str.length;
        while (mag--) {
          tail += '0';
        }
        return str + tail;
      },

      removeDotIfNeed () {
        const value = `${this.internalValue}`;
        const dotIndex = value.indexOf('.');
        if (dotIndex === (value.length - 1)) {
          this.internalValue = new BigNumber(`${this.internalValue}`).toString();
        }
      },

      onFocus () {
        this.$emit('focus');
      },

      onBlur () {
        this.removeDotIfNeed();
        this.$emit('blur');
      },

      standardize (value = '') {
        const precision = this.internalPrecision;
        let result = value.trim().replace(/[^0-9\.]/g, '');
        const dotIndex = result.indexOf('.');
        if (dotIndex === 0) {
          result = `0${result}`;
        } else if (dotIndex > 0) {
          result = result.substring(0, dotIndex + 1) + result.substring(dotIndex + 1).replace(/[\.]/g, '');
          if (precision > 0) {
            result = result.slice(0, dotIndex + precision + 1);
          } else {
            result = result.slice(0, dotIndex);
          }
        }
        result = this.removeLeadingZeros(result);
        return result;
      },

      removeLeadingZeros (value) {
        let result = value;
        while (true) {
          if (result.length < 2) {
            break;
          }
          if (result.charAt(0) === '0' && result.charAt(1) !== '.') {
            result = result.slice(1);
          } else {
            break;
          }
        }
        return result;
      },

      isNumber (value) {
        const rawValue = _.replace(value, /,/g, '') || '';
        if (rawValue.indexOf('.') === 0 || rawValue.split('.').length - 1 > 1) {
          return false;
        }
        return !isNaN(BigNumber(rawValue).toNumber());
      },

      formatNumber (value) {
        let result = `${value}`;
        const x0 = result.split('.');
        let x1 = x0[0];
        let x2 = x0.length > 1 ? `.${x0[1]}` : '';
        const rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
          x1 = x1.replace(rgx, '$1,$2');
        }
        result = x1 + x2;
        return result;
      },

      setPrecision (precision) {
        this.internalPrecision = precision;
      },

      focus () {
        this.$refs.input.$refs.input.focus();
      },

      onEnter (callback) {
        this.$refs.input.$refs.input.onkeypress = function (event) {
          if (event.keyCode === 13) { // enter
            callback();
          }
        };
      },
      handleValue (val) {
          if (this.isNegative) {
              return new BigNumber(this.standardize(val)).comparedTo(0) > 0 ? new BigNumber(this.standardize(val)).times(-1).toString() : val;
          }
          return val;
      },
    },
  };
</script>
<style scoped>
    .inline-block {
        width: 100%;
    }
    .form-control {
        border-radius: 0 !important;
    }

</style>
