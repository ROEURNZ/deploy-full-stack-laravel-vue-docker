<template>
  <table class="table table-white table-striped">
        <thead>
            <tr class="text-center">
                <th scope="col">Id</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col" style="width: 10%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <userList v-for="(user, list) in users" :key="user.id" :user="user" :list="list" class="text-center"/>
        </tbody>
    </table>
</template>

<script>
import api from "@/views/api"
import userList from "@/Components/AdminCom/users/ListUser.vue"
export default {
    components:{
        userList,
    },
    data(){
        return {
            users: []
        }
    },
    mounted(){
        this.listUsers()
    },
    methods:{
        async listUsers(){
            let response = await api.listUsers()
            this.users = response.data
            console.log(this.users)
        },
        blockUser(id){
            // Implement the logic to block the user with the given id
            // You can use the api.put method to make a PUT request to the '/users/{id}/block' endpoint
            // Example: api.put(`/users/${id}/block`)
        }
    }
}
</script>

<style>

</style>