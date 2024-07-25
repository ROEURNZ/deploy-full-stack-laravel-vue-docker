import { useAuthStore } from '@/stores/auth-store'

export function useAbility() {
  const store = useAuthStore()
  function checkPermissions(requiredPermissions: any) {
    if (!requiredPermissions) {
      return false
    }

    for (const permission of requiredPermissions) {
      if (!store.roles?.includes(permission) && !store.permissions?.includes(permission)) {
        return false
      }
    }

    return true
  }
  return {
    checkPermissions
  }
}
