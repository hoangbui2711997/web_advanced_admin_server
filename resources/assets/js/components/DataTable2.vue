<template>
  <div class="custom-table">
    <div class="thead" @click="onSort">
      <slot/>
    </div>
    <div class="tbody">
      <slot name="first_row"/>
      <slot v-for="(row, index) in rows" :index="index" :item="row" name="body"/>
      <template v-if="emptyData">
        <div class="no-data-icon-wrapper">
          <div class="no-data-icon">
              <span class="iconmo-nodata"><span class="path1"/><span class="path2"/><span
                class="path3"/><span class="path4"/><span class="path5"/><span class="path6"/><span
                class="path7"/><span class="path8"/><span class="path9"/><span class="path10"/><span
                class="path11"/><span class="path12"/><span class="path13"/><span
                class="path14"/><span class="path15"/><span class="path16"/></span>
            <span class="no-data-text">{{ msgEmptyData || $t('datatable_empty') }}</span>
          </div>
        </div>
      </template>
      <slot name="end_row"/>
    </div>
    <template v-if="lastPage > 1">
      <pagination
        ref="pagination"
        :chunk="chunk"
        :page-parent="page"
        :per-page="perPage"
        :records="totalRecord"
        class="text-center"
        @Pagination:page="onPageChange"/>
    </template>
  </div>
</template>

<script>
  import DataTable from './DataTable.vue';
  import Pagination from './Pagination.vue';

  export default {
    name: 'DataTable2',
    components: {
      Pagination,
    },
    extends: DataTable,
    methods: {
      getTarget (target) {
        let node = target;
        while (node.parentNode.nodeName !== 'div' && node.parentNode.className !== 'thead') {
          node = node.parentNode;
        }
        return node;
      },
    },
  };
</script>

<style lang="scss" scoped>
  .no-data-icon-wrapper {
    display: table-row;
    position: relative;
    width: 100%;
    height: 200px;

    .no-data-icon {
      position: absolute;
      font-size: 60px;
      left: 50%;
      top: 62px;
      text-align: center;
      min-width: 210px;
      line-height: 1;
      transform: translateX(-50%);
    }

    .no-data-text {
      font-size: 13px;
      vertical-align: middle;
      color: #666;
    }

    .iconmo-nodata {
      vertical-align: middle;
      opacity: 0.5;
    }
  }

  .thead {
    border-bottom: solid thin #cfcfcf;
    font-size: 0;

    [class^="td"][data-sort-field] {
      cursor: pointer;
    }

    [class^="td"][data-sort-order='desc'],
    [class^="td"][data-sort-order='asc'] {
      color: #1ea1f2 !important;
    }

    [class^="td"][data-sort-field]::after {
      font-family: 'icomoon' !important;
      margin-left: 5px;
      vertical-align: middle;
      font-size: 9px;
      position: absolute;
    }

    [class^="td"][data-sort-order='desc']::after {
      content: "\E901";
      margin-left: 5px;
      font-size: 1em;
      color: #1ea1f2;
      position: absolute;
    }

    [class^="td"][data-sort-order='asc']::after {
      content: "\E902";
      margin-left: 5px;
      font-size: 1em;
      color: #1ea1f2;
    }

    [class^="td-"] {
      padding: 7px 10px;

      &:first-child {
        padding-left: 20px;
      }

      &:last-child {
        padding-right: 20px;
        text-align: right;
      }

      font-size: 12px;
      color: #666;
      display: inline-block;
    }
  }

  .tbody {
    position: relative;
    .table-row {
      background-color: #fff;
      .tr {
        cursor: pointer;
        font-size: 0;

        [class^="td-"] {
          padding: 10px;
          display: inline-block;
          font-size: 13px;
          color: #333;
          vertical-align: top;

          &:first-child {
            padding-left: 20px;
          }

          &:last-child {
            padding-right: 20px;
            text-align: right;
          }
        }
      }
      .cancel {
        vertical-align: middle;
        display: inline-block;
        font-size: 8px;
        margin-right: 11px;
        line-height: 4px;
        .icon {
          color: #1ea1f2;
        }
        .text {
          margin-right: 5px;
          color: #1ea1f2;
          font-size: 12px;
        }
      }
      .tr-collapse {
        background-color: #fff !important;
        padding: 0 50px;
        overflow-x: auto;
        ul {
          margin: 20px 0;
          li {
            color: #666;
            text-align: left;
            margin-bottom: 10px;
            font-size: 0;
            .left {
              display: inline-block;
              margin-right: 0 !important;
              padding-right: 15px;
              color: #999999;
              width: 117px;
              font-size: 13px;
              vertical-align: top;
            }
            .right {
              display: inline-block;
              vertical-align: top;
              font-size: 13px;
              width: calc(100% - 117px);
              color: #666;
            }
          }
          li:last-child {
            margin-bottom: 0;
            .left {
              margin-right: 38px;
            }
          }
        }
      }
    }
    .table-row:nth-child(odd) {
      background-color: #f2f4f5;
    }
  }
  .no-data-table {
    text-align: center;
    padding: 30px 0;
    background-color: #fff !important;
    span {
      color: #666;
      font-size: 13px;
    }
    img {
      width: 60px;
      margin-right: 10px;
      opacity: 0.5;
    }
  }

  .sort {
    color: #1ea1f2 !important;
    cursor: pointer;
    i {
      color: #1ea1f2;
    }
  }
</style>
