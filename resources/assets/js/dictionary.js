import ja from 'vee-validate/dist/locale/ja';
import en from 'vee-validate/dist/locale/en';

export default {
  en: {
    messages: en.messages,
    attributes: window.i18n.t('_validation.attributes', 'en'),
  },
  ja: {
    messages: { ...ja.messages, ...{ 
        lt: (attribute, data) => window.i18n.t('_validation.lt.numeric', { attribute, value: window.i18n.t(`_validation.attributes.${data[1]}`) }),
        gt: (attribute, data) => window.i18n.t('_validation.gt.numeric', { attribute, value: window.i18n.t(`_validation.attributes.${data[1]}`) })
      }, 
    },
    attributes: window.i18n.t('_validation.attributes', 'ja'),
  },
};

