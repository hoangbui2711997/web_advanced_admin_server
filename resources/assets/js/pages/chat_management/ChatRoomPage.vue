<template>
  <div class="content-page w-88 m-auto w-max">
    <div class="heading-title">
      Chat Room
      <div class="is-pulled-right">
        <button class="button is-warning has-text-weight-bold" @click="controlLeaveConversationHandle">
          Leave conversation
        </button>
        <button class="button is-danger has-text-weight-bold">
          Leave all conversation
        </button>
      </div>
    </div>
    <section class="section" style="padding-top: .5rem">
      <div class="hr"></div>
      <div class="columns">
        <div class="column is-3" style="border: solid thin;">
          <div class="list-user">
            <div :class="{ 'active': user.conversation.id === conversationId }"
                 class="user"
                 v-for="user in users"
                 :key="user.id">
              <div :class="{ 'active': user.conversation.id === conversationId }"
                   class="user-name is-bold"
                   @click="handleChatWith(user.conversation.id)">
                {{ user.name }}
              </div>
            </div>
          </div>
        </div>
        <div class="column is-9">
          <div class="columns chat is-multiline">
            <div class="column is-12 chat-area wrap-chat-area">
              <div :class="{ 'has-text-primary is-bold': message.type === 'employee' }" class="columns" v-for="message in messages" :key="message.id">
                <div class="column is-3">{{ message.type }}_ {{ message.created_at }}</div>
                <div class="column">{{ message.message }}
                </div>
              </div>
              <infinite-loading v-if="!!conversationId" force-use-infinite-wrapper=".wrap-chat-area" direction="top" @infinite="loadMoreMessage"></infinite-loading>
            </div>
            <div class="column is-12 chat-input" style="padding-bottom: 0">
              <input type="text" class="input" placeholder="input content here..." v-model="message" @keyup.enter="handleSendMessage"/>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
  import SocketChat from "./SocketChat";
  import _ from 'lodash';

  export default {
    name: "ChatRoomPage",
    created() {
      // this.listenChatConversation(1);
    },
    data () {
      return {
        conversation: {},
        users: [],
        conversationId: null,
        message: '',
      }
    },
    async mounted () {
      await this.refreshUser();
    },
    computed: {
      messages () {
        return this._.get(this.conversation[this.conversationId], 'data', []);
      }
    },
    methods: {
      ...SocketChat,
      async refreshUser () {
        const { data } = await this.rf.getRequest('ChatRoomRequest').getAllUserPairing();
        this.users = data;
      },
      handleChatWith (id) {
        this.conversationId = id;
        this.listenChatConversation(id);
        // this.state.reset();
        // this.loadMessages();
      },
      async handleSendMessage () {
        if (this.conversationId === null) {
          this.showError('Please choose conversation to send message!!!');
          return;
        }
        const { data } = await this.rf.getRequest('ChatRoomRequest')
          .sendMessage({ id: this.conversationId, message: this.message });
        this.showSuccess(data);
        this.message = '';
      },
      async controlLeaveConversationHandle () {
        this.leaveChatConversation(this.conversationId);
        await this.rf.getRequest('ChatRoomRequest').unpair({ id: this.conversationId });
        await this.refreshUser();

        this.conversation[this.conversationId] = {
          page: 1,
          data: []
        };
        this.conversationId = null;
      },
      async loadMessages () {
        const id = this.conversationId;

        if (!this.conversation[id]) {
          this.$set(this.conversation, id, {
            page: 1,
            data: []
          });
        }
        const { data } = await this.rf.getRequest('ChatRoomRequest')
          .loadMessages({ id, limit: 20, page: this.conversation[id].page++ });
        this.conversation[id].data.push(...data.data);
      },
      loadMoreMessage: _.debounce(async function ($state) {
        try {
          const id = this.conversationId;
          console.log(id, "id");
          if (!this.conversation[id]) {
            this.$set(this.conversation, id, {
              page: 1,
              data: []
            });
          }

          const { data } = await this.rf.getRequest('ChatRoomRequest')
            .loadMessages({id, limit: 20, page: this.conversation[id].page++});

          this.conversation[id].data.push(...data.data);
          if (!data || data.last_page <= data.current_page) {
            $state.complete();
          } else {
            $state.loaded();
          }
        } catch (e) {
          $state.complete();
          console.log(e, "e");
        }
      }, 1000)
    }
  }
</script>

<style scoped lang="scss">
  .list-user {
    border: thin solid;
    .user {
      &:not(:last-child) {
        border-bottom: thin solid;
      }
      height: 30px;

      &:hover, .active {
        background: #1ea1f2;
        opacity: 0.8;
        cursor: pointer;
      }
      .user-name {
        &:hover, .active {
          font-weight: bold;
          cursor: pointer;
        }
        font-weight: 400;
        color: black;
        opacity: 1;
        padding: 3px 1rem;
      }
    }
  }
  .wrap-chat-area {
    height: 400px;
    overflow-y: auto;
    display: flex;
    flex-direction: column-reverse;
  }
</style>