<template>
  <div class="summary-report-page">
    <div class="heading-title">{{ $t('summary_report.heading_title') }}</div>
    <div class="data-table-user-balances-01">
      <div class="table__control">
        <div class="table__date-ranger">
          <span class="text">{{ $t('summary_report.date_range') }}</span>
          <div class="date-list" @click="toggleDateRanger" v-click-outside="closeDateRanger">
            <p class="selected">{{ selectedOptionDate || dateList[0] }}</p>
            <span class="iconmo-arrow_4_1 down-icon"></span>

            <ul class="list" :class="{'show': isShowDateRanger}">
              <div class="calender-box">
                <li class="item" v-for="item in dateList" @click="changeTimeTo(item)">{{ item }}</li>
              </div>
              <li class="item" @click="selectOption($t('summary_report.custom'))">{{ $t('summary_report.custom') }}</li>
            </ul>
          </div>
        </div>
        <div class="table__calendar">
          <div class="from-date">
            <div class="start-date custom-date" :class="{'disable': isDisableDatePicker}">
              <date-picker
                :disabled-picker="isDisableDatePicker"
                v-model="startTime"
                class="date-picker-01 date-picker-01--02"
                wrapper-class="date-picker"
                format="yyyy-MM-dd"
                input-class="date-picker-input"/>
              <i class="iconmo-calendar" />
            </div>
          </div>
          <div class="to-date">
            <span class="desc">{{ $t('summary_report.To') }}</span>
            <div class="end-date custom-date" :class="{'disable': isDisableDatePicker}">
              <date-picker
                :disabled-picker="isDisableDatePicker"
                v-model="endTime"
                class="date-picker-01 date-picker-01--02"
                wrapper-class="date-picker"
                format="yyyy-MM-dd"
                input-class="date-picker-input"/>
              <i class="iconmo-calendar" />
            </div>
          </div>
        </div>
        <div class="btn-group">
          <button
            class="btn-item btn-search"
            style="text-transform: inherit"
            @click="updateReport">
            {{ $t('summary_report.update_report') }}
          </button>
        </div>
        <button class="btn-item btn-download" style="float: right" @click="exportCsv">{{ $t('summary_report.csv_download') }}</button>
      </div>
      <div class="report-option" style="display: flex;">
        <div class="option-all">
          <input class="pointer" type="radio" name="report_option" v-model="reportType" value="all" id="option_all">
          <label class="pointer" for="option_all">{{ $t('summary_report.all') }}</label>
        </div>
        <div class="option-affiliate margin-left-20">
          <input class="pointer" type="radio" name="report_option" v-model="reportType" value="through_affiliate" id="option_affiliate">
          <label class="pointer" for="option_affiliate">{{ $t('summary_report.affiliate') }}</label>
        </div>
        <div class="option-not-affiliate margin-left-20">
          <input class="pointer" type="radio" name="report_option" v-model="reportType" value="not_through_affiliate" id="option_not_affiliate">
          <label class="pointer" for="option_not_affiliate">{{ $t('summary_report.not_through_affiliate') }}</label>
        </div>
      </div>
      <div  v-if="!isEmpty(report)">
        <div class="summary-content">
          <div class="summary-all">
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_register') }}</span></label>
              <span>{{ report.total_register_user }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_registered') }}</span></label>
              <span>{{ report.total_registered_user }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_register_user_info') }}</span></label>
              <span>{{ report.total_register_user_info }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_approved_2FA_user') }}</span></label>
              <span>{{ report.total_approved_2FA_user }}</span>
            </div>


            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_active_user') }}</span></label>
              <span>{{ report.total_active_user }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_trading_user') }}</span></label>
              <span>{{ report.total_trading_user }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_deposited_user') }}</span></label>
              <span>{{ report.total_deposited_user }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.total_withdrawal_user') }}</span></label>
              <span>{{ report.total_withdrawal_user }}</span>
            </div>
          </div>
          <div class="summary-number">
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_trading') }}</span></label>
              <span>{{ report.trading_number }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_deposit_transactions') }}</span></label>
              <span>{{ report.deposit_number }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_withdrawal_transactions') }}</span></label>
              <span>{{ report.withdrawal_number }}</span>
            </div>
          </div>
          <div class="summary-active">
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.active_users_rate') }}</span></label>
              <span>{{ report.active_user_rate | changedPercentFee }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.trading_user_rate') }}</span></label>
              <span>{{ report.trading_user_rate | changedPercentFee }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.deposited_user_rate') }}</span></label>
              <span>{{ report.deposited_user_rate | changedPercentFee }}</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.withdrawal_user_rate') }}</span></label>
              <span>{{ report.withdrawal_user_rate | changedPercentFee }}</span>
            </div>
          </div>

          <div class="summary-active">
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_bot_n_order') }}</span></label>
              <span>0</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_matched_bot_n_order') }}</span></label>
              <span>0</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_cancelled_bot_n_order') }}</span></label>
              <span>0</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_cancelled_bot_a_order') }}</span></label>
              <span>0</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_bot_p_order') }}</span></label>
              <span>0</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_matched_bot_p_order') }}</span></label>
              <span>0</span>
            </div>
            <div class="text">
              <label class="width-272"><span class="text">{{ $t('summary_report.number_of_cancelled_bot_p_order') }}</span></label>
              <span>0</span>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_bot_n_order_volume') | uppercase }}</div>
                <data-table3 :get-data="() => getData('total_bot_n_order_volume')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.pair') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.coin | uppercase }} / {{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_bot_p_order_volume') | uppercase }}</div>
                <data-table3 :get-data="() => getData('total_bot_p_order_volume')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.pair') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.coin | uppercase }} / {{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_profit') | uppercase }}</div>
                <data-table3 :get-data="() => getData('total_profit')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.total_profit') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.total_profit | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_amount') | uppercase }}</div>
                <data-table3 :get-data="() => getData('total_amount')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.total_amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.total_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.active_users_ltv') | uppercase }}</div>
                <data-table3 :get-data="() => getData('active_users_ltv')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.total_profit | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.registered_users_ltv') | uppercase }}</div>
                <data-table3 :get-data="() => getData('registered_users_ltv')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.total_profit | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_trading_amount') | uppercase }}</div>
                <data-table3 :get-data="() => getData('trading_amount')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.pair') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.coin | uppercase }} / {{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.trading_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.coin | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_deposit_amount') | uppercase }}</div>
                <data-table3 :get-data="() => getData('deposit_amount')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.deposit_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_withdrawal_amount') | uppercase }}</div>
                <data-table3 :get-data="() => getData('withdrawal_amount')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.withdrawal_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_trading_fee_amount') | uppercase }}</div>
                <data-table3 :get-data="() => getData('trading_fee')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.trading_fee') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.trading_fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_deposit_fee_amount') | uppercase }}</div>
                <data-table3 :get-data="() => getData('deposit_fee')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.deposit_fee') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.deposit_fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.total_withdrawal_fee_amount') | uppercase }}</div>
                <data-table3 :get-data="() => getData('withdrawal_fee')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.withdrawal_fee') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.withdraw_fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.trading_amount_per_1_person') | uppercase }}</div>
                <data-table3 :get-data="() => getData('trading_amount_per_1_person')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.pair') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.coin | uppercase }} / {{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.trading_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.coin | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.trading_amount_per_1_turn') | uppercase }}</div>
                <data-table3 :get-data="() => getData('trading_amount_per_1_turn')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.pair') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.coin | uppercase }} / {{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.trading_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.coin | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.trading_fee_per_1_person') | uppercase }}</div>
                <data-table3 :get-data="() => getData('trading_fee_per_1_person')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.trading_fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.deposit_amount_per_1_person') | uppercase }}</div>
                <data-table3 :get-data="() => getData('deposit_amount_per_1_person')"  width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.deposit_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.deposit_amount_per_1_turn') | uppercase }}</div>
                <data-table3 :get-data="() => getData('deposit_amount_per_1_turn')"  width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.deposit_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.deposit_fee_per_1_person') | uppercase }}</div>
                <data-table3 :get-data="() => getData('deposit_fee_per_1_person')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.deposit_fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
        <div class="content-row">
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.withdrawal_amount_per_1_person') | uppercase }}</div>
                <data-table3 :get-data="() => getData('withdrawal_amount_per_1_person')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.withdrawal_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.withdrawal_amount_per_1_turn') | uppercase }}</div>
                <data-table3 :get-data="() => getData('withdrawal_amount_per_1_turn')"  width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.withdrawal_amount | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
          <div class="content-page">
            <div class="tab-table">
              <div>
                <div class="font-weight-500">{{ $t('summary_report.withdrawal_fee_per_1_person') | uppercase }}</div>
                <data-table3 :get-data="() => getData('withdrawal_fee_per_1_person')" width-table="320px">
                  <th style="width: 150px">{{ $t('summary_report.currency') }}</th>
                  <th style="width: 170px">{{ $t('summary_report.amount') }}</th>

                  <template slot="body" slot-scope="props">
                    <tr>
                      <td>{{ props.item.currency | uppercase }}</td>
                      <td>{{ props.item.withdraw_fee | formatCurrencyAmount(props.item.currency, '0') }} {{ props.item.currency | uppercase }}</td>
                    </tr>
                  </template>
                </data-table3>
              </div>
            </div>
          </div>
        </div>
        <hr/>
      </div>
    </div>
  </div>
</template>
<script >
  import rf from '../requests/RequestFactory';
  import DatePicker from 'vuejs-datepicker';
  import moment from 'moment';
  import Utils from '../common/Utils';

  export default {
    name: 'SummaryReport',
    components: {
      DatePicker
    },
    data() {
      return {
        startTime: moment().startOf('year').toDate(),
        endTime: moment().toDate(),
        isShowDateRanger: false,
        isDisableDatePicker: true,
        selectedOptionDate: null,
        report: {},
        reportType: 'all',
      }
    },
    computed: {
      dateList() {
        let currentYear = moment().year();
        return [ currentYear, currentYear - 1, currentYear - 2 ];
      }
    },
    watch: {
      reportType: function () {
        this.updateReport();
      },
    },
    methods: {
      exportCsv() {
        const params = {
          start_date: moment(this.startTime).startOf('day').format('x'),
          end_date: moment(this.endTime).endOf('day').format('x'),
          report_type: this.reportType,
        };
        window.location.href = `/admin/summary-report/export?timezone_offset=${Utils.getTimzoneOffset()}&${Utils.convertObjToQueryUrl(params)}`;
      },
      getData(field) {
        return new Promise(resolve => {
          return resolve(_.orderBy(this.report[field] || [], ['coin', 'currency'], ['asc', 'asc']));
        });
      },
      isEmpty(data) {
        return _.isEmpty(data);
      },
      getSummaryReport() {
        const params = {
          start_date: moment(this.startTime).startOf('day').format('x'),
          end_date: moment(this.endTime).endOf('day').format('x'),
          report_type: this.reportType,
        };
        return rf.getRequest('SummaryReportRequest').getSummaryReport(params).then((res) => {
          this.report = res.data;
        });
      },
      updateReport() {
        this.report = {};
        this.getSummaryReport();
      },
      toggleDateRanger() {
        this.isShowDateRanger = !this.isShowDateRanger;
      },
      closeDateRanger() {
       this.isShowDateRanger = false;
      },
      changeTimeTo(year) {
        this.isDisableDatePicker = true;
        this.selectedOptionDate = year;
        let firstDayOfYear = moment(year, 'YYYY').startOf('year').toDate();
        let lastDayOfYear = moment(year, 'YYYY').endOf('year').toDate();
        let now = moment().toDate();

        if ( lastDayOfYear > now ) {
          lastDayOfYear = now;
        }

        this.startTime = firstDayOfYear;
        this.endTime = lastDayOfYear;
      },
      selectOption(name) {
        this.isDisableDatePicker = false;
        this.selectedOptionDate = name;
      }
    },
    created() {
      this.getSummaryReport();
    }
  }
</script>
<style lang="scss" scoped>
  /deep/ .tableContainer {
    margin-right: 25px;
  }
  .font-weight-500 {
    font-weight: 700;
  }
  .summary-report-page{
    padding: 25px 30px;
  }
  .margin-left-20 {
    margin-left: 50px;
  }
  .width-272 {
    width: 272px;
  }
  .summary-active, .summary-number {
    margin-top: 50px;
  }
  .text {
    font-weight: normal;
    font-style: normal;
    font-stretch: normal;
    line-height: 3;
    letter-spacing: normal;
    color: #666666;
  }
  .summary-all {
    margin-top:  19px;
  }
  .content-row {
    display: flex;
    &.content-page {
      margin-left: 45px !important;
    }
  }
  .pointer {
    cursor: pointer;
  }

  .table__calendar {
    margin-top: 8px;
  }

  .btn-item.btn-search {
    margin-top: 8px;
  }

  .btn-item.btn-download {
    margin-top: 8px;
  }
  .custom-date {
    /deep/ .date-picker-input {
      width: 106px !important;
    }
  }
</style>
