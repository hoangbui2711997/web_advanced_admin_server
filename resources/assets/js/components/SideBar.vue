<template>
  <div>
    <ul class="list-menu">
      <li v-for="(item, index) in listMenuClone" :key="index"
          :class="{'active': (item.activeMenu || item.openLink) }"
          class="menu-item">
        <!-- <div class="inline-link"> -->
        <router-link :to="item.link" v-if="item.link" class="main-link">
          {{ $t(item.text) }}
        </router-link>
        <a href="javascript:void(0)" v-else @click="toggle(item)" class="main-link">{{ $t(item.text) }}</a>
        <!-- </div> -->
        <template v-if="item.sub_list">
          <i class="iconmo-arrow_4_1 icon-arrow" />
          <div class="sub-menu-wrapper">
            <ul class="sub-menu sub-menu-01">
              <li class="item sub-menu__item" v-for="subItem in item.sub_list">
                <a href="javascript:void(0)" v-if="!subItem.link || subItem.link === 'javascript:void(0)'" class="link">
                  {{ $t(subItem.text) }}
                </a>
                <router-link v-else :to="subItem.link"
                             class="link"
                             :class="{active: subItem.link.includes($route.meta.sub)}">
                  {{ $t(subItem.text) }}
                </router-link>
              </li>
            </ul>
          </div>
        </template>
      </li>
    </ul>
  </div>
</template>

<script>
  import _ from 'lodash';
  import { mapGetters } from 'vuex';

  export default {
    name: 'SideBar',
    data() {
      return {
        supportedLocales: ['en', 'ja'],
        isShowLanguageSelect: false,
        listMenu: [
          {
            text: 'side_bar.user_management',
            route_name: 'UserManagement',
            openLink: false,
            activeMenu: false,
            sub_list: [
              {
                text: 'side_bar.user',
                link: '/user-management/users',
                name: 'UserPage'
              },
              {
                text: 'side_bar.employees',
                link: '/user-management/employees',
                name: 'EmployeePage'
              },
            ]
          },
          {
            text: 'side_bar.product_management',
            route_name: 'ProductManagement',
            openLink: false,
            activeMenu: false,
            sub_list: [
              {
                text: 'side_bar.product',
                link: '/product-management/products',
                name: 'ProductPage'
              },
              {
                text: 'side_bar.categories',
                link: '/product-management/categories',
                name: 'CategoryPage'
              },
            ],
          },
          {
            text: 'side_bar.role_management',
            route_name: 'RoleManagement',
            openLink: false,
            activeMenu: false,
            sub_list: [
              {
                text: 'Role Permissions',
                link: '/role-management/list',
                name: 'RolePermission'
              },
            ],
          },
          {
            text: 'side_bar.route_management',
            route_name: 'RouteManagement',
            openLink: false,
            activeMenu: false,
            sub_list: [
              {
                text: 'Routes',
                link: '/route-management/list',
                name: 'ListRoute'
              },
            ],
          },
        ],
        listMenuClone: [],
      }
    },
    computed: {
      ...mapGetters(['getPermissionMenu', 'getPermissionPage', 'getPermissionPageAction'])
    },
    created() {
      this.initMenu(this.$route.path);
    },
    watch: {
      '$route'(to) {
        this.initMenu(to.path);
      },
      getPermissionMenu () {
        this.updateSideBar();
      },
    },
    mounted() {
      this.$on('updateSideBar', async () => {
        const data = await this.$store.dispatch('getCurrentUserInfo');
        if (!!data) {
          this.initMenu(this.$route.path);
          this.updateSideBar();
        }
      });
    },
    methods: {
      updateSideBar () {
        this.$nextTick(() => {
          this.listMenuClone = _.filter(JSON.parse(JSON.stringify(this.listMenu)), (menu) => {
            if(_.some(this.getPermissionMenu, (permissionMenu) => permissionMenu === menu.route_name)) {
              menu.sub_list = _.filter(menu.sub_list, ({ name }) => {
                return _.some(this.getPermissionPage, (e) => e === name)
              });
              return true;
            }
            return false;
          });
        });
      },
      initMenu (link) {
        _.forEach(this.listMenu, (menu, key) => {
          this.$set(this.listMenu[key], 'activeMenu', false);
          this.$set(this.listMenu[key], 'openLink', false);

          if
          (_.find
            (
              menu.sub_list,
              (item) => this.getPrefixPath(item.link) === this.getPrefixPath(link)
            )
          )
          {
            menu.activeMenu = true;
          }
        });
      },
      toggle (item) {
        if (item.activeMenu) {
          return;
        }

        item.openLink = !item.openLink;
        _.forEach(this.listMenu, (menu) => {
          if (menu.text !== item.text) {
            this.$set(menu, 'openLink', false)
          }
        });
      },
      getPrefixPath (path) {
        // Pattern path: /deposit/payment-history
        if (!!path) {
          return path.split('/')[1];
        }
      },
    },
  };
</script>

<style lang="scss" scoped>
  @import "../../sass/variables";

  .select-language {
    cursor: pointer;
    width: 115px;
    padding-left: 30px;
    margin-top: 20px;
    box-sizing: content-box;

    .language-active {
      color: #fff;
      font-size: 13px;
      margin-bottom: 0;
      border: solid thin #424950;
      padding: 6px 12px;
      position: relative;
      border-radius: 5px;

      &:after {
        content: "\E906";
        font-family: 'icomoon' !important;
        speak: none;
        font-size: 10px;
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;

        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
      }
    }
    .display-language {
      float: left;
      color: #cccccc;
      margin: 0 6px;
    }
    i {
      color: #cccccc;
      font-size: 9px;
    }
    .language-popup {
      text-align: left;
      right: 0px;
      width: 100%;
      transition: all 0.3s ease-in-out;
      opacity: 0;
      visibility: hidden;
      line-height: 20px;
      box-shadow: 0px 2px 10px 0px rgba(0, 0, 0, 0.17);

      &.show {
        opacity: 1;
        visibility: visible;
      }
    }

    .language-popup {
      border: solid thin #424950;
      background-color: #2d353d;
      border-radius: 5px;
      margin-top: 20px;

      ul {
        padding: 10px 0;
        margin-bottom: 0;
        li {
          padding: 5px;

          &.active a {
            color: #1ea1f2;
          }

          a {
            text-decoration: none;
            transition: all 0.3s ease-in-out;
            padding: 0 12px;
            display: block;
            font-size: 14px;
            color: #cccccc;
            font-size: 14px;
          }
        }
      }
    }
  }
</style>
