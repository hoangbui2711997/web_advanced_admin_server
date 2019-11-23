<template>
  <router-link tag="li" v-if="router && router.name"
    :class="{active: router.name === $route.name}" :to="router">
    <a href="#">
      <i :class="icon"></i> <span>{{ name }}</span>
      <span class="pull-right-container" v-show="badge">
        <small class="label pull-right" :class="[badge.type==='String'?'bg-green':'label-primary']">{{ badge.data }}</small>
      </span>
    </a>
  </router-link>
  <li :class="[isHeader ? 'header' : type === 'item' ? '' : 'treeview', hasActiveItem ? 'active' : '' ]" v-else>
    {{ isHeader ? name : '' }}
    <a href="#" v-if="!isHeader">
      <i :class="icon"></i> <span>{{ name }}</span>
      <span class="pull-right-container">
        <small v-if="badge && badge.data" class="label pull-right"
               :class="[badge.type==='String' ? 'bg-green': 'label-primary']">{{ badge.data }}</small>
        <i v-else class="fa fa-angle-left pull-right"></i>
      </span>
    </a>
    <ul class="treeview-menu" v-if="items.length > 0">
      <router-link tag="li" v-for="(item,index) in items" :data="item"
                   :class="{active: item.router.name === $route.name}"
                   :key="index" :to="item.router" v-if="item.router && item.router.name">
        <a>
          <i :class="item.icon"></i> {{ item.name }}
        </a>
      </router-link>
      <li v-else>
        <a>
          <i :class="item.icon"></i> {{ item.name }}
        </a>
      </li>
    </ul>
  </li>
</template>

<script>
  import _ from 'lodash';
  export default {
    props: {
      type: {
        type: String,
        default: 'item'
      },
      isHeader: {
        type: Boolean,
        default: false
      },
      icon: {
        type: String,
        default: ''
      },
      name: {
        type: String
      },
      badge: {
        type: Object,
        default () {
          return {}
        }
      },
      items: {
        type: Array,
        default () {
          return []
        }
      },
      router: {
        type: Object,
        default () {
          return {
            name: ''
          }
        }
      },
      link: {
        type: String,
        default: ''
      }
    },
    computed: {
      hasActiveItem() {
        const item = _.find(this.items, (item) => {
          return item.router.name === this.$route.name;
        });
        if (item) {
          return true;
        }
        return false;
      }
    }
  }
</script>
