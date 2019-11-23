import { Validator } from 'vee-validate';
import BigNumber from 'bignumber.js';

Validator.extend('half_width', {
  getMessage: () => window.i18n.t('_validation.half_width'),
  validate: (value) => {
    return !value.match(/[\u3000-\u303f\u3040-\u309f\u30a0-\u30ff\uff00-\uff9f\u4e00-\u9faf\u3400-\u4dbf]/);
  },
});

Validator.extend('gt', {
  getMessage: (field, data) => {
    if (data[1] && window.i18n.te(`_validation.attributes.${data[1]}`)) {
      return window.i18n.t('_validation.gt.numeric', { attribute: field, value: window.i18n.t(`_validation.attributes.${data[1]}`)});
    }
    return window.i18n.t('_validation.gt.numeric', { attribute: field, value: data[0] })
  },
  validate: (value, args) => {
    const number = args[0] || 0;
    if (number === undefined || number === '' || number === 'undefined' || new BigNumber(number).isNaN()) {
      return ({ valid: true });
    }
    return ({ valid: new BigNumber(value).comparedTo(number) > 0 });
  },
});

Validator.extend('lt', {
  getMessage: (field, data) => {
    if (data[1] && window.i18n.te(`_validation.attributes.${data[1]}`)) {
      return window.i18n.t('_validation.lt.numeric', { attribute: field, value: window.i18n.t(`_validation.attributes.${data[1]}`)});
    }
    return window.i18n.t('_validation.lt.numeric', { attribute: field, value: data[0] })
  },
  validate: (value, args) => {
    const number = args[0] || 0;
    if (number === undefined || number === '' || number === 'undefined' || new BigNumber(number).isNaN()) {
      return ({ valid: true });
    }
    return ({ valid: new BigNumber(value).comparedTo(number) < 0 });
  },
});

Validator.extend('check_integer', {
  getMessage: () => window.i18n.t('_validation.check_integer'),
  validate: (value) => {
    return Number.isInteger(Number(value));
  },
});
