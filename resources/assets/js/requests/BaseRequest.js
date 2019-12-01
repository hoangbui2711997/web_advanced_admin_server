export default class BaseRequest {

  prefix () {
    return '';
  }

  async get(url, params = {}, useApi = true) {
    try {
      params.locale = window.i18n.locale || document.documentElement.lang || 'en';
      const response = await window.axios.get(`${useApi ? '/api/' : ''}${this.prefix()}${url}`, { params });
      return this._responseHandler(response);
    } catch (error) {
      this._errorHandler(error);
    }
  }

  async put (url, data = {}, useApi = true) {
    try {
      data.locale = window.i18n.locale || document.documentElement.lang || 'en';
      const response = await window.axios.put(`${useApi ? '/api/' : ''}${this.prefix()}${url}`, data);
      return this._responseHandler(response);
    } catch (error) {
      this._errorHandler(error);
    }
  }

  async post(url, data = {}, useApi = true) {
    try {
      data.locale = window.i18n.locale || document.documentElement.lang || 'en';
      const response = await window.axios.post(`${useApi ? '/api/' : ''}${this.prefix()}${url}`, data);
      return this._responseHandler(response);
    } catch (error) {
      this._errorHandler(error);
    }
  }

  async del(url, params = {}, useApi = true) {
    try {
      params.locale = window.i18n.locale || document.documentElement.lang || 'en';
      const response = await window.axios.delete(`${useApi ? '/api/' : ''}${this.prefix()}${url}`, params);
      return this._responseHandler(response);
    } catch (error) {
      this._errorHandler(error);
    }
  }

  async _responseHandler (response) {
    return response.data;
  }

  _errorHandler(err) {
    window.app.showError(err.response.data.error);
    if (err.response && err.response.status === 401) { // Unauthorized (session timeout)
      window.location.href = '/admin/login';
    }
    throw err;
  }

}
