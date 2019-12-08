import BaseRequest from '../BaseRequest'

export default class ChatRoomRequest extends BaseRequest {
  prefix() {
    return 'chat-management/chat-room/'
  }

  sendMessage (params) {
    return this.post('send-message', params);
  }

  getAllUserPairing (params) {
    return this.get('all-user-pairing', params);
  }

  loadMessages (params) {
    return this.get('messages', params);
  }

  unpair (params) {
    return this.post('un-pair', params);
  }
}

