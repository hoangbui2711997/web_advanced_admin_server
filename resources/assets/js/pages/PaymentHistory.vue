<template>
  <div class="payment-history-page">
    <div class="heading-title">{{ $t('payment_history.heading_title') }}</div>
    <div class="content-page">
      <div class="clr"></div>
      <div class="data-table-user-balances-01">
        <div class="table__control">
          <div class="table__calendar">
            <div class="from-date">
              <span class="desc">From</span>
              <div class="start-date custom-date">
                <date-picker
                  v-model="startDate"
                  class="date-picker-01 date-picker-01--02"
                  wrapper-class="date-picker"
                  format="yyyy-MM-dd"
                  input-class="date-picker-input"/>
                <i class="iconmo-calendar" />
              </div>
            </div>
            <div class="to-date">
              <span class="desc">To</span>
              <div class="end-date custom-date">
                  <date-picker
                    v-model="endDate"
                    class="date-picker-01 date-picker-01--02"
                    wrapper-class="date-picker"
                    format="yyyy-MM-dd"
                    input-class="date-picker-input"/>
                  <i class="iconmo-calendar" />
              </div>
            </div>
          </div>
          <div class="btn-group">
            <button class="btn-item btn-search" @click="search()">Search</button>
            <button class="btn-item btn-download" @click="getPaymentHistoriesExport()">CSV download</button>
          </div>

          <div class="table__input-search">
            <button class="desc">{{ $t('components.datatable.label_search') }}</button>
            <input type="text" class="search-input" :placeholder="$t('placeholder.search')" v-model="keySearch" @keyup.enter="search()">
          </div>
        </div>

        <div class="table__main-table">
          <data-table ref="datatable" :get-data="getData" :column="6" class="table-deposit table-01">
            <th data-sort-field="">{{ $t('payment_history.no') }}</th>
            <th data-sort-field="transaction_id">{{ $t('payment_history.transition_number') }}</th>
            <th data-sort-field="email">{{ $t('payment_history.user_name') }}</th>
            <th data-sort-field="currency">{{ $t('payment_history.currency') }}</th>
            <th data-sort-field="amount">{{ $t('payment_history.amount') }}</th>
            <th data-sort-field="transactions.created_at">{{ $t('payment_history.created_at') }}</th>
            <template slot="body" slot-scope="props">
              <tr>
                <td>{{ props.index }}</td>
                <td>{{ props.item.transaction_id }}</td>
                <td>{{ props.item.email }}</td>
                <td>{{ props.item.currency | uppercase }}</td>
                <td>{{ props.item.amount | abs }}</td>
                <td>{{ props.item['transactions.created_at'] | formatTimeStamp}}</td>
              </tr>
            </template>
          </data-table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import rf from '../requests/RequestFactory';
  import Utils from '../common/Utils';
  import DatePicker from 'vuejs-datepicker';
  import moment from 'moment';

  export default {
    name: 'PaymentHistory',
    components: {
      DatePicker,
    },
    data() {
      return {
        startDate: moment().subtract(1, 'week').toDate(),
        indexCollapsing: '',
        endDate: new Date(),
        keySearch: null,
      }
    },
    methods: {
      getData(params) {
        params.start_date = moment(this.startDate).startOf('day').format('x');
        params.end_date = moment(this.endDate).endOf('day').format('x');

        return rf.getRequest('TransactionRequest').getPaymentHistories(params);
      },
      toggleIndexCollapsing (index) {
        this.indexCollapsing = this.indexCollapsing === index ? '' : index;
      },
      getPaymentHistoriesExport() {
        let url = '/admin/exportPaymentHistories/export';
        window.location.href =  url + `?timezone_offset=${Utils.getTimzoneOffset()}`;
      },
      search () {
        this.$nextTick(() => {
          const params = {
            sort: this.$refs.datatable.orderBy,
            sort_type: this.$refs.datatable.sortedBy,
            key_search: this.keySearch,
          };
          this.$refs.datatable.filter(params);
        });
      },
    },
    watch: {
      keySearch: _.debounce(function () {
        this.search();
      }, 500),
    }
  };
</script>

<style lang="scss" scoped>
  .payment-history-page{
    padding: 25px 30px;
  }

  .table-deposit {
    width: 100%;

    .td-information {
      padding-right: 30px;

      i {
        font-size: 15px;
        transition: 0.3s;
        display: block;
        float: right;
        color: #1ea1f2;
      }

      .tooltip-wrapper {
        display: inline-block;
        position: relative;
        vertical-align: middle;
        margin-left: 7px;
        float: right;

        &:after {
          content: '';
          display: table;
          clear: both;
        }

        &.show {
          display: inline-block !important;

          .tr-collapse {
            opacity: 1;
            visibility: visible;
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

        .tr-collapse {
          min-width: 450px !important;
          .list-unstyled {
            padding: 17px;
            list-style: none;
          }
          &:before {
            content: '';
            width: 0;
            height: 0;
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            border-top: 8px solid transparent;
            border-left: 10px solid #fff;
            border-bottom: 8px solid transparent;
          }

          max-height: 700px !important;
          opacity: 0;
          visibility: hidden;
          position: absolute;
          top: 50%;
          right: calc(100% + 46px);
          word-break: break-all;
          transform: translateY(-50%);
          padding: 17px !important;
          overflow: unset !important;
          transition: all 0.3s ease-in-out;
          display: table;
          box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.17);
          min-width: 410px;

          ul {
            background-color: #fff;
            margin: 0 !important;
          }
        }
      }

    }
    td {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  }
</style>
