<template>
  <div class="container-fluid p-0" style="height: 80vh;">
    <div class="row g-0" style="height: 100%">
      <!-- Sidebar -->
      <div class="col-lg-3 col-md-4 sidebar">
        <div class="d-flex flex-column h-100 bg-dark text-light">
          <!-- Chat List -->
          <div class="chat-container flex-grow-1 overflow-auto p-3">
            <div v-for="(room, index) in chatRoomsData" :key="index" class="mb-3 room">
              <div
                v-if="room && room.user_id && room.sender"
                @click="getRoom(room.id)"
                :class="{
                  'chat-item': true,
                  'd-flex': true,
                  'align-items-center': true,
                  'p-1': true,
                  'shadow-sm': true,
                  'rounded': true,
                  'bg-white': true,
                  'active-chat': room.id === activeChatRoomId
                }"
              >
                <img
                  :src="profileimage(room.user_id == room.sender.id ? room.receiver.profile : room.sender.profile) || '../../../assets/images/Male User.png'"
                  class="profile-img rounded-circle mr-3"
                  alt="Avatar"
                >
                <div class="chat-info">
                  <h6 class="mb-0 font-weight-bold text-primary">{{ room.user_id == room.sender.id ? room.receiver.name : room.sender.name }}</h6>
                  <p v-if="room.newmessage" class="text-dark">{{ truncateMessage(room.newmessage.message) }}</p>
                  <p v-else class="text-dark">New...</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Chat Area -->
      <div class="col-lg-9 col-md-8 chat-area">
        <div class="chat-messages flex-grow-1 overflow-auto p-3">
          <div v-for="(message, index) in eachRoom" :key="index" :class="{
            'message-item': true,
            'message-right': message.user_id === store_user.accountUser.id,
            'message-left': message.user_id !== store_user.accountUser.id
          }">
            <div class="message-content" :class="{'myMessage': message.user_id === store_user.accountUser.id}">
              <p>{{ message.message }}</p>
            </div>
          </div>
        </div>
        <div class="chat-input p-3 d-flex">
          <form @submit.prevent="sendMessage" class="d-flex w-100">
            <input
              type="text"
              class="form-control me-2"
              v-model="newMessage"
              placeholder="Type your message"
              required
            />
            <button type="submit" class="btn btn-primary">Send</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useUserStore } from '@/stores/user';
import api from '@/views/api';

export default {
  data() {
    return {
      store_user: useUserStore(),
      chatRoomsData: [],  // Variable to store the chat rooms data
      eachRoom: [], // Variable to store the messages
      newMessage: '',
      selectedRoomId: null, // Variable to store the selected room ID
      selectedRoomName: '', // Variable to store the selected room name
      messageInterval: null, // Interval for polling new messages
      activeChatRoomId: null // Variable to store the active chat room ID
    };
  },
  async mounted() {
    // Delay function to wait for 2 seconds
    const delay = (ms) => new Promise(resolve => setTimeout(resolve, ms));
    
    // Wait for 2 seconds before calling getChatRooms
    await delay(2000);
    await this.getChatRooms();
    
    // Immediately call createChatRoom after getChatRooms
    await this.createChatRoom();
  },

  methods: {
    async createChatRoom() {
      const user_id = this.store_user.user_id; // Ensure store_user is defined and accessible
      const auth = this.store_user.tokenUser; // Ensure tokenUser contains the correct token
      const header = {
        'Authorization': `Bearer ${auth}`,
        'Content-Type': 'application/json',
      };
      try {
        const response = await api.createChatRoom(user_id, header);
        console.log(response.data);
        await this.getChatRooms(); // Refresh chat rooms list
      } catch (error) {
        console.error('Error creating chat room:', error);
      }
    },
    async getChatRooms() {
      try {
        const user_token = this.store_user.tokenUser;
        const headers = {
          'Authorization': `Bearer ${user_token}`,
          'Content-Type': 'application/json',
        };
        const response = await api.chatrooms(headers);
        this.chatRoomsData = response.data.data;
        console.log(this.chatRoomsData)
      } catch (error) {
        console.error('Error fetching chat rooms:', error);
      }
    },
    async getRoom(id) {
      this.selectedRoomId = id; // Set the selected room ID
      this.activeChatRoomId = id; // Set the active chat room ID
      const user_token = this.store_user.tokenUser;
      const headers = {
        'Authorization': `Bearer ${user_token}`,
        'Content-Type': 'application/json',
      };
      try {
        const response = await api.getMessage(id, headers);
        this.eachRoom = response.data;
        // Optionally set selected room name based on response

        // Start polling for new messages
        this.startPolling();
      } catch (error) {
        console.error('Error fetching messages:', error);
      }
    },
    async sendMessage() {
      if (!this.selectedRoomId) {
        console.warn('No room selected.');
        return;
      }
      const message = this.newMessage.trim();
      if (message) {
        const user_token = this.store_user.tokenUser;
        const headers = {
          'Authorization': `Bearer ${user_token}`,
          'Content-Type': 'application/json',
        };
        try {
          await api.sendMessage(this.selectedRoomId, message, headers);
          this.newMessage = ''; // Clear the input field
          await this.getRoom(this.selectedRoomId); // Refresh messages
          await this.getChatRooms();
          this.moveChatRoomToTop(this.selectedRoomId); // Move the chat room to the top
        } catch (error) {
          console.error('Error sending message:', error);
        }
      } else {
        console.warn('Message is empty.');
      }
    },
    truncateMessage(message, length = 20) {
      return message.length > length ? message.substring(0, length) + '...' : message;
    },
    profileimage(filename) {
      return api.profile(filename);
    },
    async startPolling() {
      if (this.messageInterval) {
        clearInterval(this.messageInterval);
      }
      this.messageInterval = setInterval(async () => {
        if (this.selectedRoomId) {
          await this.getRoom(this.selectedRoomId);
        }
      }, 5000); // Poll every 5 seconds
    },
    moveChatRoomToTop(roomId) {
      const index = this.chatRoomsData.findIndex(room => room.id === roomId);
      if (index > -1) {
        const [room] = this.chatRoomsData.splice(index, 1);
        this.chatRoomsData.unshift(room);
      }
    }
  },
};
</script>

<style scoped>
.circular {
  width: 50px; /* Adjust as needed */
  height: 50px; /* Adjust as needed */
  border-radius: 50%;
  overflow: hidden;
}

.chat-item {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 10px;
}

.chat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}

.profile-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 50%;
    border: 2px solid #007bff;
}

.chat-info h6 {
    margin-bottom: 5px;
    font-size: 1rem;
    color: #333;
}

.chat-info p {
    margin-bottom: 0;
    font-size: 0.9rem;
    color: #6c757d;
}

.text-primary {
    color: #007bff !important;
}

.text-muted {
    color: #6c757d !important;
}

.flex-grow-1 {
    flex-grow: 1;
}

.overflow-auto {
    overflow: auto;
}

.p-4 {
    padding: 1.5rem !important;
}

.mb-4 {
    margin-bottom: 1.5rem !important;
}

.mr-3 {
    margin-right: 1rem !important;
}

.chat-area {
  display: flex;
  flex-direction: column;
  height: 80vh;
}

.chat-header {
  background-color: #343a40;
  color: white;
}

.chat-messages {
  flex-grow: 1;
  overflow-y: auto;
  padding: 1rem;
  background-color: #f8f9fa;
}

.message-item {
  display: flex;
  margin-bottom: 1rem;
}

.message-right {
  justify-content: flex-end;
}

.message-left {
  justify-content: flex-start;
}

/* --------------------- */
.message-content {
  max-width: 60%;
  padding: 0.75rem 1rem;
  border-radius: 20px;
}

.message-right .message-content {
  background-color: #007bff;
  color: white;
  border-top-right-radius: 0;
}

.message-left .message-content {
  background-color: #e9ecef;
  color: #212529;
  border-top-left-radius: 0;
}

/* ------------------- */
.myMessage {
  background-color: #007bff !important;
  color: white !important;
  border-top-right-radius: 0 !important;
  border-top-left-radius: 20px !important;
}

.chat-input {
  border-top: 1px solid #dee2e6;
  background-color: #ffffff;
}

.active-chat {
  width: 290px;
  background-color: #e0f7fa; /* Change to your desired active background color */
  /* border: 2px solid #007bff; Change to your desired active border color */
}

.room:hover {
  color: #333; /* Darker text color */
  cursor: pointer; /* Change cursor to pointer */
}
</style>
