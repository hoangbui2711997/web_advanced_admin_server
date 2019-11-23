import { Validator } from 'vee-validate';
import Dictionary from '../dictionary';
import BigNumber from "bignumber.js";
import MasterdataUtils from "./MasterdataUtils";
import rf from '../requests/RequestFactory.js';
import moment from 'moment';

export default {
  qs: function (key) {
    key = key.replace(/[*+?^$.[]{}()|\\\/]/g, "\\$&"); // escape RegEx meta chars
    const match = location.href.match(new RegExp("[?&]"+key+"=([^&]+)(&|$)"));
    return (match && decodeURIComponent(match[1].replace(/\+/g, " ")) || null);
  },

  formatCurrencyAmount (amount, currency, zeroValue) {
    const numberOfDecimalDigits = currency === 'usd' ? 2 : 8;
    // const format = numberOfDecimalDigits === 0
    //   ? '0,0'
    //   : `0,0.[${Array(numberOfDecimalDigits + 1).join('0')}]`;
    if (window._.isNil(zeroValue)) {
      zeroValue = '';
    }

    const rawValue = new BigNumber(`${amount}`).toFixed(numberOfDecimalDigits);
    return (amount && parseFloat(amount) !== Infinity && !isNaN(amount)) ? new BigNumber(rawValue).toFormat() : zeroValue;
  },

  getTimzoneOffset() {
    let d = new Date();
    return d.getTimezoneOffset();
  },

  uuid () {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
      const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
      return v.toString(16);
    });
  },

  convertNumberToPrecision (number) {
    if (!(parseInt(number || 0) > 0)) {
      return '0';
    }
    if (parseInt(number) > 21) {
      number = 22;
    }
    return '0.' + '0'.repeat(number - 1) + 1;
  },

  convertPrecisionToNumber (precision) {
    return Math.max(Math.abs(new BigNumber(`${precision}`).e), 0);
  },

  trimEnd(value, ...chars) {
    return window._.trimEnd(`${value}`, chars);
  },

  convertObjToQueryUrl (obj) {
    this.addLocale(obj);
    return Object.keys(obj).map((k) => {
      return k + '=' + (obj[k] === null ? '' : encodeURIComponent(obj[k]));
    }).join('&');
  },

  addLocale (obj) {
    obj.locale = window.i18n.locale;
  },

  isAlphaNumeric(value) {
    return /^[0-9a-zA-Z ()]+$/.test(value);
  },

  convertTimeZone (hour, minute) {
    const utcTime = parseInt(hour * 60 + minute) + parseInt(this.getTimzoneOffset());
    let utcHour = parseInt(utcTime / 60);
    if (utcHour === 0) {
      utcHour = Math.abs(utcHour)
    }
    return {
      hour: utcHour,
      minute: utcTime - utcHour*60
    }
  },

  convertUTCToLocalTime (hour, minute) {
    let dateUTC = moment().format("YYYY-MM-DD");
    let hourUTC = hour;
    let minuteUTC = minute;
    const time = moment(dateUTC + ' ' + hourUTC + ':' + minuteUTC);
    return {
      hour:  moment.parseZone(time).local().hour(),
      minute:  moment.parseZone(time).local().minute()
    }
  },

  convertLocalTimeToUTC (hour, minute) {
    let dateLocal = moment().format("YYYY-MM-DD");
    let hourLocal = hour;
    let minuteLocal = minute;
    const time = moment(dateLocal + ' ' + hourLocal + ':' + minuteLocal);
    return {
      hour:  moment.utc(time).hour(),
      minute:  moment.utc(time).minute()
    }
  },

  formatTwoDigitNumber(number) {
    return ("0" + number).slice(-2);
  },

  getProductImagePath(name) {
    return `/public/storage/application/products/${name}`;
  }
};
