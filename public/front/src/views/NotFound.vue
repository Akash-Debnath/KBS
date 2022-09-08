<template>
  <div class="page-wrapper">
    <div v-if="isLoading">
      <Loader/>
    </div>
    <div v-else v-cloak class="min-height-wrapper">

      <section class="category-page-area pt-50 pb-50 pt-md-60 pb-md-100">
        <div class="container pb-0 pb-md-140">
          <h1 class="font-size-250 section-title bottom-bar text-center mb-20 pb-10">404</h1>
          <h6 class="heading-thin text-theme-grey text-center font-18">Page Not Found</h6>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
  import Loader from "../components/Loader";

  export default {
    name: "NotFound",
    components:{
      Loader,
    },
    data(){
      return{
        isLoading: true,
      }
    },
    methods:{
      loadingRemove(){
        setTimeout(()=>{
          this.isLoading = false;
        },250);
      }
    },
    created() {
      this.loadingRemove();
    }
  }
</script>

<style scoped>
  .font-size-250{
    padding: 150px 0px;
    font-size: 250px;
  }
</style>
