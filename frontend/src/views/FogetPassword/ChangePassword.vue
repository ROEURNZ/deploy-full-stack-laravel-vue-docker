<template>
  <form>
    <div class="container bg-light p-4 rounded shadow w-50" style="height: 65vh;">
      <div class="mb-3 text-center">
        <h3>Reset Password</h3>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label fw-bold" style="padding-top: 10px;">Password</label>
        <input v-model="password" type="password" class="form-control fw-bold">
      </div>
      <div class="mb-3">
        <span class="text-danger">{{ passwordLengthMessage }}</span>
      </div>
      <div class="mb-3">
        <label for="exampleInputRePassword1" class="form-label fw-bold" style="padding-top: 10px;">Re-enter Password</label>
        <input type="password" class="form-control fw-bold">
      </div>
      <div class="text-center mb-3">
        <span>Verify Code</span>
      </div>
      <div class="d-flex justify-content-center mb-3">
        <input type="text" class="form-control text-center mx-1" style="width: 50px;" maxlength="1">
        <input type="text" class="form-control text-center mx-1" style="width: 50px;" maxlength="1">
        <input type="text" class="form-control text-center mx-1" style="width: 50px;" maxlength="1">
        <input type="text" class="form-control text-center mx-1" style="width: 50px;" maxlength="1">
      </div>
      <div class="d-flex justify-content-center">
        <button class="btn btn-dark px-4 mt-1">Submit</button>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  
  data() {
    return {
      password: '',
      passwordLengthMessage: ''
    };
  },
  watch: {
    password(newValue) {
      if (newValue.length > 0 && newValue.length < 8) {
        this.passwordLengthMessage = 'Password must be 8 characters or more.';
      } else {
        this.passwordLengthMessage = '';
      }
    }
  }
};
</script>

<style>
/* Add your component-specific styles here */
</style>
