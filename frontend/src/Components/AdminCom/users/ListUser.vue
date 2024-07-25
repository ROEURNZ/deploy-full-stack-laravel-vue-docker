<template>
    <tr>
        <th  v-if="user.id !==1" scope="row">{{ list }}</th>
        <td  v-if="user.id !==1">{{user.name}}</td>
        <td  v-if="user.id !==1">{{ user.email }}</td>
        <td  v-if="user.id !==1" class="">
            <button class="btn btn-danger mb-2">Block</button>
        </td>
    </tr>
</template>

<script>
export default {
    props: ['user', 'list'],
    computed: {
        isBlocked() {
            return this.user.id === 1;
        }
    },
    methods: {
        blockUser() {
            // Block user logic goes here
        }
    }

}
</script>

<style>

</style>