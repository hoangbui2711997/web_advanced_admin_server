<template>
  <main id="content">
    <header class="header-app-01 header-custom-01" v-click-outside="hideSideBar">
      <div class="logo">
        <img src="/images/logo2-castle.png" alt="bitcastle">
      </div>
      <div class="sidebar sidebar-custom-01" :class="{'show': isShowSideBar}">
        <div class="sidebar__logo">
          <img src="/images/logo2-castle.png" alt="bitcastle" />
        </div>
        <side-bar/>
      </div>
      <div
        class="mobile__trigger-close"
        @click="toggleSideBar"
        :class="{'active': isShowSideBar}">
        <span></span>
      </div>
      <div class="user-info">
        <div class="user-email">
          <i class="iconmo-user icon-user" />
          <span class="email">{{user.email}}</span>
        </div>
        <form action="/admin/logout" method="POST">
          <input type="hidden" name="_token" :value="csrfToken"/>
          <button type="submit" class="btn-logout">
            {{ $t('logout') }}
          </button>
        </form>
      </div>
    </header>
    <div class="main-content">
      <div class="content-section">
        <router-view/>
      </div>
    </div>
    <confirm-dialog />
  </main>
</template>

<script>
  import SideBar from '../components/SideBar';
  import rf from '../requests/RequestFactory';
  import Utils from "../common/Utils";
  import ConfirmDialog from '../modals/ConfirmDialog.vue';

  export default {
    name: 'BasePage',
    components: { SideBar, ConfirmDialog },
    data () {
      return {
        isShowSideBar: false,
        user: {} ,
        csrfToken: window.csrf_token,
        maintenanceModeSetting: 0,
      }
    },
    created () {
    },
    mounted () {
     this.getDataUser();
    },
    methods : {
      toggleSideBar() {
        this.isShowSideBar = !this.isShowSideBar;
      },
      hideSideBar() {
        this.isShowSideBar = false;
      },
      getDataUser() {
        return rf.getRequest('AdminRequest').getCurrentUser().then(res=>{
           this.user = res.data;
        });
      },
    }
  };
</script>

<style lang="scss" scoped>
  @import "../../sass/variables";
  form {
    display: inline-block;
  }

  .text_maintenace_mode {
    float: left;
    line-height: 30px;
    padding-right: 10px;
  }

  .switchToggle input[type=checkbox]{height: 0; width: 0; visibility: hidden; position: absolute; }
  .switchToggle label {cursor: pointer; text-indent: -9999px; width: 55px; max-width: 55px; height: 20px; background: #d8d8d8; display: block; border-radius: 100px; position: relative; }
  .switchToggle label:after {content: ''; position: absolute; top: -1px; left: 0; width: 22px; height: 22px; background: #fff; border-radius: 90px; transition: 0.3s; box-shadow: 0px 0px 7px 0px rgba(0, 0, 0, 0.5)}
  .switchToggle input:checked + label, .switchToggle input:checked + input + label  {background: #3e98d3; }
  .switchToggle input + label:before, .switchToggle input + input + label:before {content: 'OFF';line-height: 18px; position: absolute; top: 2px; left: 24px; width: 26px; height: 16px; border-radius: 90px; transition: 0.3s; text-indent: 0; color: #333; font-size: 13px;}
  .switchToggle input:checked + label:before, .switchToggle input:checked + input + label:before {content: 'ON'; position: absolute; top: 2px; left: 8px; width: 22px; height: 22px; border-radius: 90px; transition: 0.3s; text-indent: 0; color: #fff; }
  .switchToggle input:checked + label:after, .switchToggle input:checked + input + label:after {left: 100%; transform: translateX(-100%); }
  .switchToggle label:active:after {width: 22px; }
  .switchToggle {
    float: left;
    margin-right: 20px;
    margin-top: 5px;
    &.disabled {
      cursor: not-allowed;
      .switch2 {
        cursor: not-allowed;
      }
      label {
        cursor: not-allowed !important;
      }
    }
  }

  /deep/ .vue-loading {
    display: inline-block;
    float: left;
    margin-right: 30px;
  }

  .content-section {
    margin-bottom: 50px;
  }
</style>
