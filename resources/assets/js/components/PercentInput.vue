<template>
  <div v-if="allowNegative" class="inline-block">
    <negative-currency-input ref="input" :precision="precision"
                             @focus="focus" v-model="internalValue"/>
  </div>
  <div v-else class="inline-block">
    <currency-input :precision="precision"
                    ref="input"
                    :disable="disable"
                    class="inline-block"
                    @focus="focus" v-model="internalValue"></currency-input>
  </div>
</template>

<script>
  import BigNumber from 'bignumber.js';

  export default {
    name: "percent-input",
    props: {
      value: {},
      precision: { type: Number, default: 3 },
      allowNegative: {},
      disable: { default: false }
    },
    data() {
      return {
        internalValue: '',
      }
    },
    watch: {
      value: {
        handler(newVal) {
          if (newVal === '0') {
            return this.internalValue = '0';
          }
          if (!newVal) {
            return this.internalValue = '';
          }

          if (new BigNumber(newVal || 0).comparedTo(this.internalValue || 0) === 0) {
            return;
          }
          const value = new BigNumber(newVal).times(100).toFixed(this.precision);
          this.internalValue = new BigNumber(value).toString() || '';
          if (this.$refs.input && new BigNumber(this.value).comparedTo(0) < 0) {
            this.$refs.input.isNegative = this.allowNegative;
          }
        },
        immediate: true,
      },
      disable: {
        handler() {
          this.$nextTick(() => {
            if (this.$refs.input && this.disable) {
              this.$refs.input.$refs.input.$el.disabled = true;
            } else {
              this.$refs.input.$refs.input.$el.disabled = false;
            }
          })
        },
        immediate: true,
      },
      internalValue: _.debounce(function () {
        if (!this.internalValue) {
          return this.$emit('input', '');
        }
        const value = new BigNumber(this.internalValue).toFixed(this.precision + 2);
        if (value) {
          return this.$emit('input', new BigNumber(value).toString());
        }
        this.$emit('input', '');
      }, 100),
    },
    methods: {
      focus() {
        this.$emit('focus');
      }
    },
    mounted() {
      if (this.$refs.input && new BigNumber(this.value).comparedTo(0) < 0) {
        this.$refs.input.isNegative = this.allowNegative;
      }
    }
  }
</script>

<style lang="scss" scoped>
  .inline-block {
    display: inline-block;
  }
</style>
