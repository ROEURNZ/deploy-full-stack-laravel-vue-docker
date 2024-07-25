import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const user = ref()
  const isAuthenticated = ref()
  const permissions = ref()
  const roles = ref()

  return {
    user,
    roles,
    permissions,
    isAuthenticated
  }
})
