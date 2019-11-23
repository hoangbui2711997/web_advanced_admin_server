<template>
  <div>
    <div class="container">
      <h2 class="is-size-2">Route</h2>
      <div style="width: 600px; margin: auto">
        <div class="is-clearfix">
          <div class="is-pulled-left has-text-weight-bold">Route name</div>
          <div class="is-pulled-right has-text-weight-bold">Path route</div>
        </div>
        <div v-for="item in items" :key="item.name" class="is-clearfix">
          <div class="is-pulled-left">{{ item.name }}</div>
          <div class="is-pulled-right">{{ item.path }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "RouteList",
    data () {
      return {
        items: [],
        mapControls: {},
      };
    },
    mounted() {
      this.items = this.getAllRoutes(this.$router.options.routes, [], 'http://localhost:8013/admin');
    },
    methods: {
      getAllRoutes (routes, result, prefix = '') {
        routes.forEach(route => {
          if (!!route.name) {
            result.push({
              name: route.name,
              path: `${prefix}${route.path}`,
            });
          }
          if (_.isArray(route.children)) {
            this.getAllRoutes(route.children, result, `${prefix}${route.path}`);
          }
        });
        return result;
      },
    }
  }
</script>

<style scoped>

</style>