import BaseRequest from '../BaseRequest'

export default class WaitingRoomRequest extends BaseRequest {
  prefix() {
    return 'chat-management/waiting/'
  }

  getAllUserActive (params) {
    return this.get('users', params);
  }

  getMessages (params) {
    return this.get('messages', params);
  }

  getPreviewMessage (params) {
    return this.get('preview-messages', params);
  }

  setPair (params) {
    return this.post('pair', params);
  }
}

