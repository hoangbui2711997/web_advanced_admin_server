export default {
  innerPrefix() {
    return 'App.Chat.Conservation.'
  },
  listenChatConversation(id) {
    window.Echo.private(`App.Chat.Conservation.${id}`)
      .listen('MessageSent', (data) => {
        console.log(data, "data");
        let params = {};
        params.type = !!data.employee_id ? 'employee' : 'user';
        params.created_at = data.created_at;
        params.message = data.message;

        this.conversation[id].data.unshift(params);
        console.log(this.conversation[id].data, "this.conversation[id].data");
      });
  },
  leaveChatConversation(id) {
    window.Echo.leave(`${this.innerPrefix()}${id}`);
  }
}