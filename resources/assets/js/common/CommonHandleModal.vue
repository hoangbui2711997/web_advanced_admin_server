<script>
  export default {
    name: "CommonHandleModal",
    props: {
      show: {
        default: false,
        type: Boolean,
      },
      model: {
        default: () => {},
      }
    },
    data() {
      return {
        params: {},
        warning: [],
      };
    },
    watch: {
      show () {
        this.params = { ...this.model };
      }
    },
    methods: {
      async handle(promise) {
        try {
          const {data: message } = await promise;
          this.showSuccess(message);
          this.onSuccess();
          // this.notification = data.data.message;
        } catch (e) {
          this.warning = [];
          const {data} = e.response.data;

          if (data.errors) {
            _.forEach(data.errors, (value, key) => {
              this.warning.push(_.first(value));
            })
          } else {
            this.warning.push('error from server');
          }
        }
      },
      onSuccess() {
        this.$emit('success');
      },
    }
  }
</script>

<style scoped>

</style>