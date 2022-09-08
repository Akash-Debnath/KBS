<template>
  <div id="app">
    <Menu/>
    <router-view/>
    <Footer/>
  </div>
</template>

<script>
  import Menu from './components/Menu'
  import Footer from './components/Footer'
  export default {
    name : 'App',
    components:{
      Menu,
      Footer
    }
  }
</script>

<style>

</style>
