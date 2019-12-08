<template>
  <div class="">
    <div class="content-page w-88 m-auto w-max">
      <div class="heading-title">Waiting Room</div>
      <div class="data-table-user-balances-01">
        <div class="table__main-table table-01">
          <data-table
            :get-data="getData"
            :msg-empty-data="'empty'"
            :limit-rows="10"
            :showEmptyRow="true"
            ref="datatable"
          >
            <th>No</th>
            <th>Conversation Id</th>
            <th>Email</th>
            <th>Name</th>
            <th>Balance</th>
            <th></th>
            <template slot="body" slot-scope="props">
              <tr>
                <td>{{ props.index }}</td>
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.user.email }}</td>
                <td>{{ props.item.user.name }}</td>
                <td>{{ props.item.user.balance }}</td>
                <td>
                  <control class="button is-warning is-small" @click.native="controlViewChatRoomHandle(props.item)"
                           name="controlViewChatRoomHandle"
                           :id="`${$route.name}|control_handle_edit`">View
                  </control>
                  <control class="button is-danger is-small" @click.native="controlStartChatRoomHandle(props.item.id)"
                           name="controlStartChatRoomHandle"
                           :id="`${$route.name}|control_handle_del`">Start
                  </control>
                </td>
              </tr>
            </template>
          </data-table>
        </div>
        <view-chat-room-modal
          :show="modal.view.isShow"
          :model="modal.view.model"
          @close="modal.view.isShow = false"
        >
        </view-chat-room-modal>
        <confirm-modal
          :show="modal.start.isShow"
          :id="modal.start.id"
          :msg="'Do you really want to start this conversation?'"
          @close="modal.start.isShow = false"
          :title="'Conversation Confirm'"
          :handle="controlStartModel"
          @success="refetchData"
        ></confirm-modal>
      </div>
    </div>
  </div>
</template>

<script>
  import ViewChatRoomModal from "./ViewChatRoomModal";
  import ConfirmModal from "../../../modals/ConfirmModal";

  export default {
    // middleware: 'authenticated',
    // middleware: 'auth',
    name: "WaitingRoomPage",
    components: { ViewChatRoomModal, ConfirmModal },
    data() {
      return {
        users: [],
        data: [],
        modal: {
          view: {
            isShow: false,
            model: {}
          },
          start: {
            isShow: false,
            id: '',
          }
        },
      }
    },
    methods: {
      closeAllPopup() {
        this.modal.view.isShow = false;
        this.modal.start.isShow = false;
      },
      refetchData() {
        this.closeAllPopup();
        this.$refs.datatable.refresh();
      },
      controlStartModel () {
        return this.rf.getRequest('WaitingRoomRequest').setPair({ id: this.modal.start.id });
      },
      controlViewChatRoomHandle(user) {
        this.modal.view.model = user;
        this.modal.view.isShow = true;
      },
      controlStartChatRoomHandle(id) {
        this.modal.start.isShow = true;
        this.modal.start.id = id;
      },
      getData(params) {
        return this.rf.getRequest('WaitingRoomRequest').getAllUserActive(params);
      },
    },
  }
</script>

<style scoped>

</style>