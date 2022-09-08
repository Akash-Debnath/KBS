<template>
  <div class="page-wrapper">
    <div v-if="isLoading">
      <Loader/>
    </div>
    <div v-else v-cloak class="min-height-wrapper">
      <main>
        <section class="inner-search-area py-20">
          <div class="container">
            <div class="search-input-wrapper d-block d-sm-flex justify-content-between align-items-center">
              <div class="breadcrumbs mt-10 mt-sm-0">
                <ul class="list-inline list-unstyled mb-0">
                  <li class="list-inline-item">
                    <router-link class="nav-item" :to="{ name: 'Home'}">
                      <i class="fa fa-home"></i>
                    </router-link>
                  </li>
                  <li class="list-inline-item">
                    contact
                  </li>
                </ul>
              </div>
              <!--                        <button @click="$router.go(-1)" class="btn d-block d-sm-inline-block mt-10 mb-sm-0 btn-primary btn-common-2 position-relative font-18 overflow-hidden ripple-btn text-left py-3 px-30 text-white order-sm-1"><i class="fa fa-angle-double-left"></i> Back</button>-->
            </div>
          </div>
        </section>
        <section class="category-page-area py-50 py-md-60">
          <div class="container">
            <h1 class="mb-3">Contact</h1>
            <h6 class="heading-thin text-theme-grey font-18">Get Touch With Us</h6>

            <div class="form-wrapper my-30">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control rounded-lg shadow-sm" placeholder="Enter Full Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control rounded-lg shadow-sm" placeholder="Enter Email Address">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Subject</label>
                    <input type="text" class="form-control rounded-lg shadow-sm" placeholder="Enter Subject">
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Message</label>
                    <textarea class="form-control rounded-lg shadow-sm" rows="5" placeholder="Enter Subject"></textarea>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <button class="btn btn-primary btn-common-2 position-relative font-18 overflow-hidden ripple-btn text-left py-3 px-30 text-white">
                  Send Message
                </button>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script>
import Loader from "../components/Loader";
export default {
  name: "Contact",
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

</style>
