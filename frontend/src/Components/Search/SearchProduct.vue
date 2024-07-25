<template>
    <div class="container mt-4 mb-4">
        <h2 class="text-center mb-4">Welcome to Loukdo Store</h2>

        <div class="d-flex justify-content-center m-5">
            <div class="search">
                <input class="search_input" type="text" v-model="searchQuery" @input="onSearch" placeholder="Search here..."  />
                <a href="#" class="search_icon"><i class="fa fa-search"></i></a>
            </div>
        </div>
        <p class="text-center mb-4">Enjoy finding a million products all over the world here!</p>
        <p class="text-center mb-4">All in ONE!</p>
    </div>
</template>

<script>
export default {
    name: 'SearchProduct',
    data() {
        return {
            searchQuery: ''
        }
    },
    methods: {
        onSearch() {
            this.$emit('search', this.searchQuery);
        }
    }
}
</script>

<style>
/* Add any custom styles here */
    .input-group{
        position: relative;
    }
    .input-group .form-control{
        border-top-right-radius: 0;
         border-bottom-right-radius: 0;
    }
    .input-group .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }
</style>
