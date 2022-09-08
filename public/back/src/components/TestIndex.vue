<template>
  <div class="col-md-12 wrapper d-flex align-items-stretch">
    <Menu></Menu>

    <!-- Page Content  -->
    <div id="content" style="margin-left:50px; ">
      <Header></Header>

      <div class="col-md-12">
        <div v-if="success_message" class="alert alert-success" role="alert">
          {{ success_message }}
        </div>
        <div v-if="error_message" class="alert alert-danger" role="alert">
          {{ error_message }}
        </div>
      </div>

      <h2 class="mb-4">Sidebar #01</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    </div>
  </div>
</template>

<script>
import '../assets/css/style.css'
import Menu from '../layouts/common/Menu.vue'
import Header from '../layouts/common/Header.vue'

export default {
  name: "TestIndex.vue",
  data() {
    return {
      success_message : '',
      error_message   : '',
      token: ''
    }
  },
  components: {
    Menu,
    Header
  },
  created()
  {
    this.token = localStorage.getItem("authToken");
    //this.success_message = this.token;
  }


}
</script>

<style scoped>

</style>