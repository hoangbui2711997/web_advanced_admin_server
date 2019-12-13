<template>
  <div class="login-page-wrap">
    <div class="login-page">
      <div class="wrap-content">
        <h2 style="color: white; margin-bottom: 40px; opacity: 0.9;">Login Page</h2>
        <div class="login-form">
          <div :class="errors.has('email') ? 'active-warning' : ''" class="form-group">
            <input
              v-validate.disable="'required|max:190'"
              id="email"
              v-model="params.email"
              :placeholder="$t('login_page.email')"
              type="text"
              name="email"
              maxlength="190"
              @focus="resetErrors"
              @keypress.enter="submit"
              class="form-input">
          </div>
          <div class="warning-message">
            <span>{{ errors.first('email') }}</span>
          </div>
          <div :class="errors.has('password') || errors.first('email') === $t('_auth.failed') ? 'active-warning' : ''" class="form-group">
            <input
              v-validate.disable="'required'"
              id="password"
              v-model="params.password"
              type="password"
              name="password"
              maxlength="190"
              @focus="resetErrors"
              @keypress.enter="submit"
              :placeholder="$t('login_page.password')"
              class="form-input">
          </div>
          <div class="warning-message">
            <span>{{ errors.first('password') }}</span>
          </div>
          <button class="btn-submit" @click="submit">
            <vue-loading v-if="isLoading" :size="{ width: '30px', height: '30px' }" type="bars" class="loading-icon" color="#fff"/>
            <span v-else>{{ $t('login_page.btn_login') }}</span>
          </button>
          <div class="wrap-lang">
            <div
              class="inline-block center"
              @click="controllerGetRegister">
              <span class="lang" style="cursor: pointer;">Register</span>
            </div>
            <div class="inline-block">
              <div class=" separate-inline"></div>
            </div>
            <div
              :class="{active: $i18n.locale === 'ja'}"
              class="inline-block center"
              @click="controllerGetForgotPassword">
              <a href="javascript:void(0)" class="lang">Forgot password</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import rf from '../requests/RequestFactory';
  import InputOnlyNumber from '../common/InputOnlyNumber.vue';

  export default {
    name: 'LoginPage',
    components: {
      InputOnlyNumber,
    },
    mounted () {
    },
    data () {
      return {
        params: {},
        isLoading: false,
      };
    },
    methods: {
      submit () {
        this.$validator.validate().then(async (result) => {
          if (!result) {
            return;
          }
          if (this.isLoading) {
            return;
          }

          this.isLoading = true;
          const data = await this.login(this.params);
          if (!!data) {
            this.$router.push({ name: 'UserPage' });
          }
        });
      },
      async login (params) {
        return rf.getRequest('AdminRequest').login(params).then(async ({ data }) => {
          // window.location.href = '/admin/user-management/users';
          console.log(data, 'data');
          return await this.$store.dispatch('initAuth', data);
        }).catch((error) => {
          this.loadErrorsFromServer(error.response.data.errors);
        }).finally(() => {
          this.isLoading = false;
        });
      },
      controllerGetRegister () {
        console.log("clicked");
        console.log(this.$router, "this.router");
        this.$router.push('/register');
      },
      controllerGetForgotPassword () {

      },
    },
  };
</script>

<style lang="scss" scoped>
  .login-page-wrap{
    min-height: 100vh;
    width: 100%;
    background-color: #0e61b5;
    .login-page{
      width: 450px;
      margin: auto;
      min-height: inherit;
      position: relative;
      text-align: center;
      .wrap-content{
        position: absolute;
        transform: translate(50%, -50%);
        top: 50%;
        right: 50%;
        width: 100%;
      }
      img{
        width: 120px;
        margin-bottom: 50px;
      }
      .login-form{
        .form-group{
          margin-bottom: 20px;
          .form-input{
            width: 100%;
            height: 50px;
            background: transparent;
            border: solid thin #6ea0d2;
            padding: 0 25px;
            color: #fff;
            transition: all 0.3s ease-in-out;
            &:focus{
              background-color: #2671bc;
              border: solid thin #fff;
              transition: all 0.3s ease-in-out;
              outline: none;
            }
            &::placeholder{
              color: #fff;
            }
          }
        }
        .btn-submit{
          width: 100%;
          height: 50px;
          text-align: center;
          background-color: #1ea1f2;
          border-radius: 5px;
          text-transform: uppercase;
          font-weight: bold;
          color: #fff;
          outline: none;
          line-height: 2.07;
          border: none;
          letter-spacing: 0.5px;
          span {
            color: #fff;
          }
        }
        .btn-submit:hover{
          background-color: #36d0ff;
        }
        .btn-submit:focus{
          background-color: #36d0ff;
        }

        .wrap-lang {
          margin-top: 16px;
          .inline-block {
            display: inline-block;
            &.center {
              transform: translateY(-4px);
              &.active {
                .lang {
                  color: #ffffff;
                }
              }
            }

            .lang {
              width: 149px;
              height: 29px;
              font-size: 14px;
              font-weight: 500;
              font-style: normal;
              font-stretch: normal;
              line-height: 2.07;
              letter-spacing: normal;
              color: #1ea1f2;
            }
          }
          .separate-inline {
            margin: 0 16px;
            width: 1px;
            height: 18px;
            border-radius: 1.5px;
            background-color: #1ea1f2;
          }
          a, a:active {
            text-decoration: none;
          }
        }
      }
      p{
        color: #cccccc;
        position: absolute;
        bottom: 0;
        -webkit-transform: translate(100%, -50%);
        transform: translate(100%, -50%);
        right: 100%;
        width: 100%;
      }
    }
    .active-warning {
      border: thin solid #fdff00 !important;
    }
    .warning-message span {
     color: #fdff00;
    }
  }
</style>
