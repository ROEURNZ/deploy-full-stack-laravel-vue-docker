<template>
  <userPostProduct />
</template>

<script>
import userPostProduct from '@/Components/PostProduct/UserPostProductComponent.vue'

export default {
  components:{
    userPostProduct
  }
}
</script>

<style>
.btn {
  border-radius: 30px;
  font-size: 0.9em;
  padding: 10px 20px;
  cursor: pointer; /* Added cursor pointer */
}

</style>