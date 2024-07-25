<template>
  <div>
    <div class="d-flex align-items-center mb-3">
      <!-- User Image and Name -->
      <img
        src="../../assets/images/Male User.png"
        alt="User Image"
        class="mr-3"
        style="height: 50px; width: 43px"
      />
      <p class="mb-0">{{ comment.user.name }}: {{ new Date(comment.created_at).toLocaleString() }}</p>
    </div>
    <div class="d-flex flex-column">
      <!-- Comment Text and Image -->
      <p>{{ comment.comment }}</p>
      <div class="d-flex">
        <img
          :src="imageCommented_url(comment.image)"
          alt="Comment Image"
          class="mr-3"
          style="width: 243px; height: 240px"
        />
      </div>
    </div>
    <div class="d-flex align-items-center mb-2" style="margin-top:10px;">
      <!-- Like, Reply, and Dropdown Buttons -->
      <button class="btn btn-primary btn-custom me-2 ml-2" @click="toggleLike">
        <span v-if="isLiked">👍 {{ likeCount }}</span>
        <span v-else>Like</span>
      </button>
      <button @click="toggleReply" class="btn btn-secondary me-2">
        <span v-if="showReplyForm">Reply</span>
        <span v-else>Reply</span>
      </button>
    </div>
    <div v-if="showReplyForm" class="reply-form">
      <!-- Reply Form -->
      <textarea
        v-model="Comment"
        placeholder="Enter your reply"
        class="form-control mb-2 w-50"
      ></textarea>
      <button @click.prevent="submitReply" class="btn btn-primary">Send</button>
    </div>
    <div class="mt-3">
      <!-- <span v-if="replyTexts.length">{{ comment.user.name }} replies: </span> -->
      <ul>
      <li v-for="reply in replyTexts" :key="reply.id" :style="'list-style-type: none;'">
        <span :style="{color:'gray'}"><b>{{ reply.user_id.name }}</b> replies:</span>  <span>{{ reply.text }}</span>
      </li>
      </ul>
    </div>
  </div>
</template>

<script>
import api from '../../views/api.js'
import { useUserStore } from '@/stores/user.js'

export default {
  props: ['comment'],
  data() {
    return {
      showReplyForm: false,
      Comment: null,
      store_user: useUserStore(),
      isLiked: false,
      likeCount: 0,
      replyTexts: []
    }
  },
  created() {
    this.loadLikeStatus()
  },
  mounted() {
    this.getReplyComment();
  },
  methods: {
    imageCommented_url(filename) {
      return api.imageComment(filename)
    },

    // code for creating reply comment
    async submitReply() {
      try {
        if (this.Comment !== null && this.Comment.trim() !== '') {
          const formData = new FormData()
          formData.append('text', this.Comment)
          formData.append('user_id', this.store_user.accountUser.id)
          formData.append('comment_id', this.comment.id)

          const token = localStorage.getItem('authToken')
          const response = await api.replycomment(formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
              Authorization: `Bearer ${this.store_user.tokenUser}`
            }
          })
          console.log('reply created:', response.data)
          this.showReplyForm = false
          this.Comment = null
          this.getReplyComment()  // refresh the reply comments
        } else {
          console.log('Cannot submit an empty reply')
        }
      } catch (error) {
        console.error('Error submitting reply:', error)
      }
    },
    // code for liking and unliking comments
    toggleLike() {
      this.isLiked = !this.isLiked
      if (this.isLiked) {
        this.likeCount++
      } else {
        this.likeCount--
      }
      this.saveLikeStatus()
    },
    saveLikeStatus() {
      const likeKey = `comment-${this.comment.id}-like`
      localStorage.setItem(
        likeKey,
        JSON.stringify({
          isLiked: this.isLiked,
          likeCount: this.likeCount
        })
      )
    },
    loadLikeStatus() {
      const likeKey = `comment-${this.comment.id}-like`
      const likeData = JSON.parse(localStorage.getItem(likeKey))
      if (likeData) {
        this.isLiked = likeData.isLiked
        this.likeCount = likeData.likeCount
      }
    },
    toggleReply() {
      this.showReplyForm = !this.showReplyForm
    },

    // code for displaying the reply comments
    async getReplyComment() {
      try {
        const response = await api.getReplyComment();
        if (response.data.data) {
          // Filter replies for the current comment and map to texts
          this.replyTexts = response.data.data
            .filter(reply => reply.comment_id === this.comment.id)
            .map(reply => ({
              user_id: reply.user_id,
              id: reply.id,
              text: reply.text
            }));
          console.log(this.replyTexts);
          // console.log(response.data.data);
        } else {
          console.error('Error getting category list:', response.data.message);
        }
      } catch (error) {
        console.error('Error getting category list:', error);
      }
    }
  }
}
</script>

<style>
.reply-form {
  margin-top: 1rem;
}
</style>
