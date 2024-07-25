import { createAcl, defineAclRules } from 'vue-simple-acl'
import axiosInstance from '@/plugins/axios'

let permissions = [] as any[]
try {
  const { data } = await axiosInstance.get('/me')

  permissions = data.data.permissions.map((item: any) => item.name)
} catch (error) {
  /* empty */
}

const rules = () =>
  defineAclRules((setRule) => {
    permissions.forEach((permission: string) => {
      setRule(permission, () => true)
    })
  })

const simpleAcl = createAcl({
  rules
})

export default simpleAcl
