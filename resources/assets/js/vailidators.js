import { Validator } from 'vee-validate';
import Vue from 'vue';
import _ from 'lodash';
import rf from 'RequestFactory';

// load errors from server

Vue.mixin({
  computed: {
    '_' () {
      return _;
    },
    rf () {
      return rf;
    }
  },
  data () {
    return {
      serverErrors: [],
    };
  },
  mounted () {
    this.$on('UPDATED_LOCALE', () => {
      if (!_.isEmpty(this.serverErrors)) {
        this.errors.clear();
        this._appendErrors(this.serverErrors);
      }
    });
  },
  methods: {
    resetErrors () {
      this.errors.clear();
    },
    loadErrorsFromServer (errors) {
      this.resetErrors();
      if (_.isEmpty(errors)) {
        return;
      }

      this.serverErrors = errors;
      this._appendErrors(errors);
    },

    _appendErrors (errors) {
      _.forEach(errors, (error, key) => {
        const message = window.i18n.te(`_` + _.head(error))
          ? window.i18n.t(`_` + _.head(error), { attribute: window.i18n.t(`_validation.attributes.${key}`) })
          : window.i18n.t(`_validation.${_.head(error)}`, { attribute: window.i18n.t(`_validation.attributes.${key}`) });
        this.errors.add({
          field: key,
          msg: message,
        });
      });
    },
    showSuccess (message) {
      this.$toasted.show(
        message,
        {
          position: 'bottom-right',
          duration: 3000,
          className: 'toasted-success',
          iconPack: 'custom-class',
          icon: 'iconmo-checked toasted-icon',
        },
      );
    },
    showError (message) {
      this.$toasted.show(
        message,
        {
          position: 'bottom-right',
          duration: 3000,
          className: 'toasted-fail',
          iconPack: 'custom-class',
          icon: 'iconmo-close toasted-icon',
        },
      );
    },
  },
});

Vue.directive('resetError', {
  // When the bound element is inserted into the DOM...
  inserted (el) {

  },
  // Called only once, when the directive is first bound to the element. This is where you can do one-time setup work.
  bind (el, binding, vnode) {
    el.addEventListener('focus', (event) => {
      _.set(vnode.context.$data, 'warning', []);
    });
  },
  // Called after the containing component’s VNode has updated, but possibly before its children have updated.
  // The directive’s value may or may not have changed, but you can skip unnecessary updates by comparing the binding’s current and old values (see below on hook arguments).called after the containing component’s VNode has updated, but possibly before its children have updated. The directive’s value may or may not have changed, but you can skip unnecessary updates by comparing the binding’s current and old values (see below on hook arguments).
  update () {

  },
  // called after the containing component’s VNode and the VNodes of its children have updated.
  componentUpdated () {

  },
  // called only once, when the directive is unbound from the element.
  unbind () {

  }
});

Validator.prototype._formatErrorMessage = function _formatErrorMessage (field, rule, data, targetName) {
  if (data === void 0) {
    data = {};
  }
  if (targetName === void 0) {
    targetName = null;
  }

  const name = this._getFieldDisplayName(field);
  const params = this._getLocalizedParams(rule, targetName);

  return getFieldMessage(this.locale, field.name, rule.name, [name, params, data]);
};

function hasDictionaryMessage (locale, key) {
  return !!(window.app &&
    window.app.$validator &&
    window.app.$validator.dictionary &&
    window.app.$validator.dictionary.container[locale].messages &&
    window.app.$validator.dictionary.container[locale].messages[key]
  );
}

function getFieldMessage (locale, field, rule, data) {
  const path = `_validation.custom.${field}.${rule}`;
  if (window.i18n.te(path)) {
    return window.i18n.t(path, locale, data);
  }

  return getMessage(locale, rule, data);
}

function getMessage (locale, rule, data) {
  if (hasDictionaryMessage(locale, rule)) {
    const _message = window.app.$validator.dictionary.container[locale].messages[rule];
    return typeof _message === 'function' ? _message(...data) : _message;
  }
  
  let path = `_validation.${rule}`;

  if (rule === 'lt' || rule === 'gt') {
    path = path + 'numeric';
  }

  if (!window.i18n.te(path)) {
    if (hasDictionaryMessage(locale, '_default')) {
      const _message = window.app.$validator.dictionary.container[locale].messages._default;
      return typeof _message === 'function' ? _message(...data) : _message;
    }
    return window.i18n.t(('_validation.messages._default'), locale, data);
  }

  const params = {};
  _.forEach(window.i18n.t(path).match(/{([^}]+)}/g), (field, index) => {
    params[field.replace(/[{:}]/g, '')] = data[index];
  });
  return window.i18n.t(path, locale, params);
}
