<template>
  <div>
    <div :style="{width: widthTable}" :class="{'no-data': emptyData}" class="tableContainer">
      <table>
        <thead>
        <tr @click="onSort">
          <slot/>
        </tr>
        </thead>
        <tbody>
        <slot name="first_row"/>
        <template v-if="!(fetching && orderBy === null)">
          <slot name="body" v-for="(row, index) in rows" :item="row"
                :index="(index + pageStartIndex) <= totalRecord ? (index + pageStartIndex) : undefined "/>
          <template v-if="emptyData">
            <tr>
              <td :colspan="column">
                {{ msgEmptyData || $t('datatable_empty') }}
              </td>
            </tr>
          </template>
        </template>
        <template v-else>
          <tr>
            <td :colspan="column">
              Loading...
            </td>
          </tr>
        </template>
        <slot name="end_row"/>
        </tbody>
      </table>
      <div class="wrap-footer">
        <template v-if="lastPage > 1">
          <pagination ref="pagination"
                      style="float: right"
                      :per-page="perPage"
                      :records="totalRecord"
                      :chunk="chunk"
                      @Pagination:page="onPageChange" :pageParent="page">
          </pagination>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
  import Pagination from './Pagination';
  import _ from 'lodash';
  export default {
    name: 'DataTable',
    components: {
      Pagination,
    },
    props: {
      getData: {
        type: Function,
      },
      chunk: {
        type: Number,
        default: 6,
      },
      widthTable: {
        type: String,
        default: '100%',
      },
      msgEmptyData: {
        type: String,
      },
      searchAble: {
        type: Boolean,
        default: false,
      },
      exportAbleFunction: {
        type: Function,
        default: undefined,
      },
    },
    data () {
      return {
        maxPageWidth: 10,
        totalRecord: 0,
        lastPage: 0,
        page: 1,
        perPage: 10,
        column: 0,
        fetching: false,
        rows: [],
        params: {},
        orderBy: null,
        sortedBy: null,
        limits: [25, 50],
        limit: 25,
        keySearch: '',
      };
    },
    computed: {
      emptyData () {
        if (this.fetching) {
          return false;
        }
        return window._.isEmpty(this.rows);
      },
      pageStartIndex () {
        return (this.perPage * (this.page - 1) + 1);
      },
      getMessagePage() {
        let from = (this.page - 1) * this.perPage + 1;
        let to = (this.page * this.perPage);
        to = Math.min(to, this.totalRecord);
        from = Math.min(from, this.totalRecord);
        return `${window.i18n.t('display')}　${from}${window.i18n.t('to')}${to}${window.i18n.t('of')}${this.totalRecord}${window.i18n.t('end')}`;
      }
    },
    created () {
      this.fetch();
      this.$on('DataTable:filter', (params) => {
        this.filter(params);
      });
    },
    mounted () {
      this.column = _.chain(this.$slots.default).filter((el) => el.tag === 'th').value().length;
    },
    methods: {
      onPageChange (page) {
        this.page = page;
        this.fetch();
      },
      getTarget (target) {
        let node = target;
        while (node.parentNode.nodeName !== 'TR') {
          node = node.parentNode;
        }
        return node;
      },
      getSortOrder (target) {
        let sortOrder = target.dataset.sortOrder;
        switch (sortOrder) {
          case 'asc':
            sortOrder = 'desc';
            break;
          case 'desc':
            sortOrder = '';
            break;
          default:
            sortOrder = 'asc';
        }
        return sortOrder;
      },
      setSortOrders (target, sortOrder) {
        let iterator = target.parentNode.firstChild;
        while (iterator) {
          iterator.dataset.sortOrder = '';
          iterator = iterator.nextElementSibling;
        }
        target.dataset.sortOrder = sortOrder;
      },
      onSort (event) {
        const target = this.getTarget(event.target);
        const orderBy = target.dataset.sortField;
        if (!orderBy) {
          return;
        }
        this.sortedBy = this.getSortOrder(target);
        this.orderBy = this.sortedBy ? orderBy : '';
        Object.assign(this.params, { sort: this.orderBy, sort_type: this.sortedBy });
        this.setSortOrders(target, this.sortedBy);
        this.fetch();
      },
      fetch () {
        const meta = {
          page: this.page,
          limit: this.limit,
        };
        this.fetching = true;
        this.getData(Object.assign(meta, this.params)).then((res) => {
          const data = res.data;
          this.column = _.chain(this.$slots.default).filter((el) => el.tag === 'th').value().length;
          if (!data || !data.data) {
            this.rows = res.data || res || [];
            this.totalRecord = this.rows.length;
            this.perPage = this.rows.length;
            this.$emit('DataTable:finish');
            return;
          }
          this.page = parseInt(data.current_page);
          this.totalRecord = parseInt(data.total);
          this.lastPage = parseInt(data.last_page);
          this.perPage = parseInt(data.per_page);
          this.rows = data.data;
          this.$emit('DataTable:finish');
        }).then(() => {
          setTimeout(() => {
            this.fetching = false;
          }, 300);
        });
      },
      refresh () {
        this.$nextTick(() => {
          this.page = 1;
          this.params = {};
          this.fetch();
        })
      },
      filter (params) {
        this.page = 1;
        this.params = params;
        this.fetch();
      },
      exportExcel () {
        this.exportAbleFunction();
      }
    },
  };
</script>

<style lang="scss" scoped>
  .wrap-footer {
    height: 20px;
    .select-display{
      border-radius: 5px;
      border: solid 1px #cfcfcf;
      width: 55px;
      height: 27px;
      float: left;
      margin-right: 5px;
      vertical-align: middle;
      select{
        border: none;
        outline: none;
        font-size: 13px;
        width: 100%;
        height: 100%;
        background: none;
      }
    }
    .table-footer{
      .display-result{
        font-size: 13px;
        float: left;
        margin-top: 5px;
      }
    }
  }
  th[data-sort-field] {
    cursor: pointer;
  }
  th[data-sort-order='desc'],
  th[data-sort-order='asc'] {
    color: #1ea1f2 !important;
  }
  th[data-sort-field]::after {
    font-family: 'icomoon' !important;
    margin-left: 5px;
    vertical-align: middle;
    font-size: 9px;
    position: absolute;
  }
  th[data-sort-order='desc']::after {
    content: "\E901";
    margin-left: 5px;
    font-size: 1em;
    color: #1ea1f2;
    position: absolute;
  }
  th[data-sort-order='asc']::after {
    content: "\E902";
    margin-left: 5px;
    font-size: 1em;
    color: #1ea1f2;
  }
  tr td:first-child {
    width: 52px;
    height: 34px;
  }
  .float-left{
    float: left;
  }
  .float-right{
    float: right;
  }
  .limit-show{
    overflow: auto;
    margin-bottom: 20px;
    .btn-display{
      color: #666666;
      border: none;
      background: none;
      vertical-align: middle;
      padding: 0;
      margin-top: 3px;
    }
    .btn-search{
      background: none;
      color: #666666;
      border: none;
    }
    .search-input{
      border-radius: 5px;
      border: solid 1px #cfcfcf;
      width: 215px;
      height: 27px;
      padding: 0 10px;
      transition: all 0.3s ease-in-out;
      &:focus{
        border-color: #1ea1f2;
        transition: all 0.3s ease-in-out;
      }
    }
  }

  .table-01 {
    td {
      text-overflow: ellipsis;
      overflow: hidden;
      white-space: nowrap;
    }
  }
</style>
