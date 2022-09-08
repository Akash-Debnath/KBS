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
              <div class="d-flex align-items-center justify-content-end order-sm-2">
                <div class="input-group w-100 d-flex align-items-center bg-white">
                  <input type="text" class="form-control pr-2" v-on:keyup.enter="faq_query ? searchData() : ''" v-model="faq_query" placeholder="Search FAQ Here" aria-label="Search Here" aria-describedby="searchBtn">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" id="searchBtn" type="button" @click="faq_query ? searchData() : ''">
                      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                        <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                      </svg>
                    </button>
                  </div>
                </div>


                <button class="btn btn-outline-primary py-15 px-15 px-sm-25 rounded-pill d-flex align-items-center ml-1 text-primary" @click="clearFilter()"><i class="fa fa-refresh"></i> <span class="pl-2">Reload</span></button>
              </div>


              <div class="breadcrumbs mt-10 mt-sm-0">
                <ul class="list-inline list-unstyled mb-0">
                  <li class="list-inline-item">
                    <router-link class="nav-item" :to="{ name: 'Home'}">
                      <i class="fa fa-home"></i>
                    </router-link>
                  </li>
                  <li class="list-inline-item">
                    FAQ
                  </li>
                  <li v-if="faq_query" class="list-inline-item">
                    {{faq_query}}
                  </li>
                </ul>
              </div>

              <!--                        <button @click="$router.go(-1)" class="btn d-block d-sm-inline-block mt-10 mb-sm-0 btn-primary btn-common-2 position-relative font-18 overflow-hidden ripple-btn text-left py-3 px-30 text-white order-sm-1"><i class="fa fa-angle-double-left"></i> Back</button>-->
            </div>
          </div>
        </section>


        <section class="faq-page-area py-50 py-md-60">
          <div class="container text-left">
            <h1 class="mb-3">FAQ</h1>
            <h6 class="heading-thin text-theme-grey font-18 pb-20">Frequently Asked Questions</h6>

            <div style="min-height: 350px">
              <div class="custom-accordion" v-for="a_faq in all_Faqs" :key="a_faq.id">

                <div class="heading">{{a_faq.en_title}}</div>

                <div class="contents overflow-hidden">
                  <div v-if="userInformation != ''">
                    <p class="font-14 pb-10" v-if="a_faq.bn_title != 'n/a'">{{a_faq.bn_title}}</p>
                    <div v-for="a_content in a_faq.contents" :key="a_content.id">
                      <div v-if="a_content.en_body != '' && a_content.en_body != 'n/a' && a_content.role_id.includes(userInformation.roles[0].id)">
                        <ul class="nav nav-tabs" :id="'myTab_'+a_content.id" v-if="a_faq.bn_title != 'n/a' && a_content.role_id.includes(userInformation.roles[0].id)">

                          <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" :href="'#tabEnglish_'+a_content.id">English</a>
                          </li>
                          <li class="nav-item" v-if="a_content.bn_body !='n/a'">
                            <a class="nav-link" data-toggle="tab" :href="'#tabBangla_'+a_content.id">Bangla</a>
                          </li>
                        </ul>

                        <div class="tab-content pt-3" :id="'myTabContent_'+a_content.id">
                          <div class="tab-pane fade active show" :id="'tabEnglish_'+a_content.id" v-if="a_content.en_body != '' && a_content.en_body != 'n/a' && a_content.role_id.includes(userInformation.roles[0].id)">
                            <div v-html="a_content.en_body"></div>
                            <!--                            <div v-if="a_content.en_body != '' && !a_content.role_id.includes(userInformation.roles[0].id)">No Access!</div>-->
                          </div>

                          <div class="tab-pane fade" :id="'tabBangla_'+a_content.id" v-if="a_faq.bn_title != 'n/a' && a_content.bn_body != '' && a_content.bn_body != 'n/a' && a_content.role_id.includes(userInformation.roles[0].id)">
                            <div v-html="a_content.bn_body"></div>
                            <!--                            <div v-if="!a_content.role_id.includes(userInformation.roles[0].id)">No Access!</div>-->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div v-else-if="userInformation == ''">
                    <p class="font-14 pb-10" v-if="a_faq.bn_title != 'n/a'">{{a_faq.bn_title}}</p>
                    <div v-for="a_content in a_faq.contents" :key="a_content.id">
                      <ul class="nav nav-tabs" :id="'myTab_'+a_content.id" v-if="a_faq.bn_title != 'n/a' && a_content.role_id.includes(0)">

                        <li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" :href="'#tabEnglish_'+a_content.id">English</a>
                        </li>
                        <li class="nav-item" v-if="a_content.bn_body !='n/a'">
                          <a class="nav-link" data-toggle="tab" :href="'#tabBangla_'+a_content.id">Bangla</a>
                        </li>
                      </ul>

                      <div class="tab-content pt-3" :id="'myTabContent_'+a_content.id">
                        <div class="tab-pane fade active show" :id="'tabEnglish_'+a_content.id" v-if="a_content.en_body != '' && a_content.en_body != 'n/a' && a_content.role_id.includes(0)">
                          <div v-html="a_content.en_body"></div>
                        </div>

                        <div class="tab-pane fade" :id="'tabBangla_'+a_content.id" v-if="a_faq.bn_title != 'n/a' && a_content.bn_body != '' && a_content.bn_body != 'n/a' && a_content.role_id.includes(0)">
                          <div v-html="a_content.bn_body"></div>
                        </div>
                      </div>
                    </div>
<!--                    <div><u class="text-primary">Login to read this faq.</u></div>-->
                  </div>

<!--                  <div v-else><u class="text-primary">Login to read this faq.</u></div>-->
                </div>
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
  import axios from 'axios'

  // import $ from 'jquery'
  // $(document).on('click','.custom-accordion .heading', function () {
  //   $(this).toggleClass("active").next().slideToggle();
  //   $(".contents").not($(this).next()).slideUp(200);
  //   $(this).siblings().removeClass("active");
  // });


  export default {
    name: "Faq",
    components:{
      Loader
    },
    data(){
      return{
        isLoading: true,
        all_Faqs:'',
        faq_query : '',
        userInformation : ''
      }
    },
    methods:{
      clearFilter(){

        localStorage.clear('faq_query');

        let _that = this;
        _that.faq_query = '';
        axios.get('faq-list')
                .then(function (response) {
                  _that.isLoading= false;
                  _that.all_Faqs = response.data.faq_list.data;
                })

      },
      searchData(pageUrl){
        let _that = this;
        pageUrl = pageUrl == undefined ? 'faq/search/'+_that.faq_query+'?page=1' : pageUrl;
        axios.get(pageUrl).then((response)=>{
          _that.all_Faqs = response.data.faq_list.data;
          _that.pagination  = response.data.faq_list;
        })
      },
      allFaqs(){
        let _that = this;
        axios.get('faq-list')
                .then(function (response) {
                  console.log(response.data.faq_list.data);
                  _that.isLoading= false;
                  _that.all_Faqs = response.data.faq_list.data;
                })
      },
    },
    created() {
      if (sessionStorage.userInformation){
        this.userInformation = JSON.parse(sessionStorage.userInformation);
        // console.log(this.userInformation)
      }else{
        this.userInformation = '';
      }
      this.allFaqs();
    }
  }
</script>

<style scoped>
  .inner-search-area .search-input-wrapper .input-group,
  .inner-search-area .search-input-wrapper .input-group.focused,
  .inner-search-area .search-input-wrapper .input-group.filled {
    max-width: 100%;
  }

  .inner-search-area .search-input-wrapper .btn.btn-outline-primary,
  .inner-search-area .search-input-wrapper .btn.btn-outline-primary:focus {
    border-color: transparent !important;
    box-shadow: inherit;
    background-color: transparent;
  }
  .nav-tabs {border-bottom: 0;}
  .nav-tabs .nav-link.active,
  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link{padding: 5px 20px;}
</style>
