import Vue from 'vue';
import moment from 'moment';
import BigNumber from 'bignumber.js';
import _ from 'lodash';
import Numeral from '../lib/numeral';
import Utils from './Utils';

Vue.filter('formatCurrencyAmount', (amount, currency, zeroValue) => Utils.formatCurrencyAmount(amount, currency, zeroValue));
Vue.filter('formatCurrencyAmountWith10Precision', (amount, currency, zeroValue) => Utils.formatCurrencyAmountWith10Precision(amount, currency, zeroValue));
Vue.filter('formatTwoDigitNumber', (number) => Utils.formatTwoDigitNumber(number));

Vue.filter('formatTimeStamp', (timestamp, format = 'YYYY-MM-DD HH:mm:ss') => {
    if (!timestamp) {
        return '';
    }
    return moment(timestamp, 'x').format(format);
});
Vue.filter('formatDate', (date, format = 'YYYY-MM-DD HH:mm:ss') => {
    if (!date) {
        return '';
    }
    return moment(date).format(format);
});

Vue.filter('formatUTCToLocalTime', (date, format = 'YYYY-MM-DD HH:mm:ss') => {
    if (!date) {
        return '';
    }
    return moment.utc(date).local().format(format);
});

Vue.filter('formatUTCToLocalTime12h', (date, format = 'YYYY-MM-DD hh:mm A') => {
    if (!date) {
        return '';
    }
    return moment.utc(date).local().format(format);
});

Vue.filter('convertDatetimeToMilliseconds', (timestamp) => {
    if (!timestamp) {
        return '';
    }
    return moment.utc(timestamp).format('x');
});

Vue.filter('uppercase', (value) => {
    if (value) { return _.toUpper(`${value || ''}`); }
    return '';
});

Vue.filter('changeCurrencyAll', (value) => {
  if (value) {
    return value === 'all' ? 'usd' : value;
  }
  return '';
});

Vue.filter('lowercase', (value) => {
    if (value) { return _.toLower(`${value || ''}`); }
    return '';
});

Vue.filter('addUsd', (value) => `$${value}`);

Vue.filter('uppercaseFirst', (value) => _.startCase(value || ''));

Vue.filter('floatToPercent', (value) => {
    if (value == 0) { return; };
    return `${Numeral(value * 100).format('0.00')}%`;
});

Vue.filter('convertToBigNumber', (number) => {
    if (!number) { return '0'; }
    return (new BigNumber(number)).toString();
});

Vue.filter('mulBigNumber', (number1, number2) => {
    if (!number1 || !number2) { return '0'; }
    return (new BigNumber(number1)).times(number2).toString();
});

Vue.filter('divBigNumber', (number1, number2) => {
    if (!number1) { return '0'; }
    if (!number2) { return '1'; }
    return (new BigNumber(number1)).div(number2).toString();
});

Vue.filter('phoneNumber', (value) => {
    if (value) {
        return value.replace(/(\d{3})(\d{4})(\d{4})/, '$1.$2.$3');
    }
});

Vue.filter('uppercase', (value) => {
    if (value) { return _.toUpper(`${value}`); }
    return '';
});

Vue.filter('upperFirst', (value) => _.upperFirst(value));

Vue.filter('uppercaseFirst', (value) => _.startCase(value));

Vue.filter('getPrecision', (value) => Numeral(value).format('.[00000000]'));


Vue.filter('to2Precision', (value) => Numeral(value).format('0.00'));
Vue.filter('changedPercent', (value) => `${Numeral(value).format('0.00')}%`);
Vue.filter('changedPercentFee', (value) => `${Numeral(value * 100).format('0.[0000]')} %`);
Vue.filter('percentNoDecimal', (value, currency = 'btc') => `${Utils.formatCurrencyAmount(Numeral(value * 100).format('0.00000000'), currency)}%`);
Vue.filter('percentWithOneDecimal', (value) => `${Numeral(value).format('0.0')}%`);

Vue.filter('toMillions', (amount) => Numeral(amount / 1000000).format('0,0'));

Vue.filter('number', (number, round = 2) => {
    if (!number || !isFinite(number)) {
        return 0;
    }
    return Number(parseFloat(number).toFixed(round));
});

Vue.filter('abs', (number) => !number === true ? '' : Math.abs(number));

Vue.filter('convertToBigNumber', (number) => {
    if (!number) { return '0'; }
    return (new BigNumber(number)).toString();
});

Vue.filter('mulBigNumber', (number1, number2) => {
    if (!number1 || !number2) { return '0'; }
    return (new BigNumber(number1)).times(number2).toString();
});

Vue.filter('divBigNumber', (number1, number2) => {
    if (!number1) { return '0'; }
    if (!number2) { return '1'; }
    return (new BigNumber(number1)).div(number2).toString();
});

Vue.filter('paddingRight', (value, length, character) => {
    const strValue = `${value}`;
    const indexDot = strValue.indexOf('.');
    if (indexDot === -1) {
        return value;
    }
    const [strNaturalPart, strDecimalPart] = strValue.split('.');
    return `${strNaturalPart}.${window._.padEnd(strDecimalPart, length, character)}`;
});

Vue.filter('toNumber', (value) => {
    const number = parseFloat(value);
    if (isNaN(number)) {
        return value;
    }
    // is e number (Ex: 1e-7)
    if (number.toString().includes('e')) {
        return Utils.trimEndZero(new BigNumber(`${value || 0}`).toFixed(20));
    }
    return number;
});

Vue.filter('checkState', (value) => {
    if (!!value === true) {
        return window.i18n.t('account_registrant.authen');
    }
    return value === undefined ? '' : window.i18n.t('account_registrant.no_authen');
});

Vue.filter('percent', (value) => {
    return new BigNumber(`${value || 0}`).times(100).toString() + '%';
});

Vue.filter('formatTel', (value, code) => {
  const firstCharacter = value.charAt(0);
  const phoneNumber = value.substr(1);
  return value.charAt(0) === '0' ? `(+${code})${phoneNumber}` : `${value}`;
});

Vue.filter('multipleValue', (value1, value2) => {
  return new BigNumber(`${value1 || 0}`).times(`${value2 || 0}`).toString();
});
