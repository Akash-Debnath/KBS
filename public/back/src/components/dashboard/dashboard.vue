<template>
  <div class="main-wrapper d-flex">
    <!-- sidebar -->
    <Menu></Menu>
    <!-- sidebar end -->

    <div class="main-content-wrapper w-100 position-relative overflow-auto">
      <!-- Topbar -->
      <Header></Header>
      <!-- Topbar End -->

      <!-- Content Area -->
      <div class="content-area">
        <!-- news start -->
        <BreakingNews v-if="isArticleList" :recent_article="articleList" />
        <!-- news end -->
        <div class="content-title-wrapper px-15 py-15">
          <h2 class="content-title text-uppercase m-0">Dashboard</h2>
        </div>

        <div class="content-wrapper d-fullscreen">

          <BannerSlider v-if="isBannerStatus" :banner_list="bannerList"></BannerSlider>

          <!-- Content Area -->
          <div class="data-content-area pr-15 pb-10">
            <div class="gredient-card-wrapper mb-40">

              <div class="gredient-card-body p-20 p-md-30" style="background: #ff7b8836;">
                <div class="row">
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                    <div class="box-with-centered-icon position-relative text-center bg-white p-20 my-30">
                      <div class="icon d-inline-flex justify-content-center align-items-center bg-primary text-white"><i class="fas fa-user-tie"></i></div>
                      <h3 class="my-0 font-16 pt-40 pb-20">Total Users</h3>
                      <div class="counting-number text-primary">
                        <total-user v-if="totalCountList" :totalUser="totalCountList.total_user"></total-user>
                        <div class="counting-number text-primary" v-else>0</div>

                      </div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                    <div class="box-with-centered-icon position-relative text-center bg-white p-20 my-30">
                      <div class="icon d-inline-flex justify-content-center align-items-center bg-red text-white"><i class="fas fa-user-times"></i></div>
                      <h3 class="my-0 font-16 pt-40 pb-20">Total Articles</h3>

                      <total-article v-if="totalCountList" :totalArticle="totalCountList.total_article"></total-article>
                      <div class="counting-number text-red" v-else>0</div>
                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                    <div class="box-with-centered-icon position-relative text-center bg-white p-20 my-30">
                      <div class="icon d-inline-flex justify-content-center align-items-center bg-yellow text-white"><i class="fas fa-hourglass-half"></i></div>
                      <h3 class="my-0 font-16 pt-40 pb-20">Total FAQs</h3>
                      <total-faqs v-if="totalCountList" :totalFaq="totalCountList.total_faq"></total-faqs>

                      <div class="counting-number text-yellow" v-else>0</div>

                    </div>
                  </div>
                  <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6">
                    <div class="box-with-centered-icon position-relative text-center bg-white p-20 my-30">
                      <div class="icon d-inline-flex justify-content-center align-items-center bg-light-blue text-white"><i class="fas fa-headphones-alt"></i></div>
                      <h3 class="my-0 font-16 pt-40 pb-20">Total Quiz</h3>
                      <!--                      <div class="counting-number text-light-blue"> {{ totalCountList.total_quiz }} </div>-->
                      <total-quiz v-if="totalCountList" :totalQuiz="totalCountList.total_quiz"></total-quiz>

                      <div class="counting-number text-light-blue" v-else>0</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- charts start -->
            <div class="row">
              <div class="col-lg-6">
                <div class="gredient-card-wrapper mb-30">
                  <div class="gredient-card-header p-10" style="background:#f8f9fa;color:#323232;">
                    <h2 class="card-title my-0 font-16">Area Chart</h2>
                  </div>

                  <div class="gredient-card-body p-20 p-md-30 with-bottom-shape" style="background: #fff;">
                    <AreaChart style="height:250px;"/>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="gredient-card-wrapper mb-30">
                  <div class="gredient-card-header p-10" style="background:#f8f9fa;color:#323232;">
                    <h2 class="card-title my-0 font-16">Bar Chart</h2>
                  </div>

                  <div class="gredient-card-body p-20 p-md-30 with-bottom-shape" style="background: #fff;">
                    <BarChart style="height:250px;"/>
                  </div>
                </div>
              </div>
            </div>
            <!-- charts end -->

            <!-- most recent posts -->
            <div class="gredient-card-wrapper mb-40">
              <div class="gredient-card-header p-10" style="background:#f8f9fa;color:#323232;">
                <h2 class="card-title my-0 font-16">Recent Articles</h2>
              </div>
              <div class="gredient-card-body pt-20 pb-20 px-10" style="background: #fff;">
                <div class="table-responsive">
                  <table class="table table-bordered gsl-table">
                    <thead>
                    <tr>
                      <th class="text-left">Title</th>
                      <th class="text-left">Category</th>
                      <th class="text-left">Author</th>

                      <th class="text-left">Status</th>
                      <th class="text-left">Tag</th>
                      <th class="text-left">Publish Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="an_article in articleList" :key="an_article.id">
                      <td class="text-left">
                        <span v-if="(an_article.en_title).length<30"> {{ an_article.en_title }}</span>
                        <span v-else> {{ (an_article.en_title).substring(0,30)+"...." }}</span>
                      </td>
                      <td class="text-left">{{ an_article.category ? an_article.category.name : ''  }}</td>
                      <td class="text-left">{{ an_article.user ? (an_article.user.first_name +' '+ an_article.user.last_name) : '' }}</td>

                      <td class="text-left">{{ an_article.status  }}</td>
                      <td class="text-left">{{ an_article.tag  }}</td>
                      <td class="text-left">{{ an_article.created_at  }}</td>


                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <!-- most recent posts end -->

          </div>
          <!-- Content Area End -->
        </div>
      </div>
      <!-- Content Area End -->
    </div>
    <div class="action-modal-wraper" v-if="success_message">
      <span>{{ success_message }}</span>
    </div>

    <div class="action-modal-wraper-error" v-if="error_message">
      <span>{{ error_message }}</span>
    </div>
  </div>
</template>

<script>

  import $ from "jquery";

  $(document).on('keyup',(e)=> {
    if (e.keyCode === 27){
      // $('.content-wrapper').toggleClass('expandable-content-area');
      $('.content-wrapper.d-fullscreen').removeClass('expandable-content-area');
    }
  });


  import Header from "@/layouts/common/Header";
  import Menu from "@/layouts/common/Menu";

  import totalUser from "../wallboard/totalUser";
  import totalArticle from "../wallboard/totalArticle";
  import totalFaqs from "../wallboard/totalFaqs";
  import totalQuiz from "../wallboard/totalQuiz";


  import AreaChart from "../chart/AreaChart";
  import BarChart from "../chart/BarChart.vue";
  // import PieChart from "../chart/PieChart.vue";
  // import LineChart from "../chart/LineChart.vue";
  // import RadarChart from "../chart/RadarChart.vue";

  import axios from "axios";
  import TotalFaqs from "../wallboard/totalFaqs";
  import TotalQuiz from "../wallboard/totalQuiz";

  import BannerSlider from '../slider/slider'
  import BreakingNews from '../breakingnews/breakingnews'
  // import Breakingnews from '../breakingnews/breakingnews.vue';

  export default {
    name: "dashboard.vue",
    components: {
      TotalQuiz,
      TotalFaqs,
      Header,
      Menu,
      AreaChart,
      BarChart,
      totalUser,
      totalArticle,
      BannerSlider,
      BreakingNews,
      // Breakingnews,
      // totalFaqs,
      // totalQuiz,
      // PieChart,
      // LineChart,
      // RadarChart
    },

    data() {
      return {
        isBannerStatus        : false,
        isArticleList         : false,
        category_parent_id    : '',
        category_name         : '',
        success_message       : '',
        error_message         : '',
        token                 : '',
        // categoryList          : '',
        articleList           : '',
        userInfo              : '',
        recentArticles        : '',
        bannerList            : '',

        user_info             : '',
        current_user_role_id  : '',
        // user_permissions      : '',
        // mappedPermission      : '',

        filter        : {
          isAdmin             : 1,
          category_id         : '',
          status              : '',
          en_title            : '',
          tag                 : '',
        },

        chartData   : {
          Books               : 24,
          Magazine            : 30,
          Newspapers          : 10
        },

        totalCountList        : '',
      }
    },
    methods: {
      setTimeoutElements()
      {
        setTimeout(() => this.success_message = "", 2e3);
        setTimeout(() => this.error_message = "", 2e3);
      },

      clearFilter()
      {
        this.isArticleList        = false;
        this.filter.category_id   = "";
        this.filter.status        = "";
        this.filter.en_title      = "";
        this.filter.tag           = "";
        this.success_message      = "";
        this.error_message        = "";
      },

      // getCategoryList()
      // {
      //   let _that =this;
      //
      //   axios.get('categories',
      //           {
      //             headers: {
      //               'Authorization': 'Bearer '+localStorage.getItem('authToken')
      //             },
      //             params :
      //                     {
      //                       isAdmin : 1
      //                     },
      //
      //           })
      //           .then(function (response) {
      //             if(response.data.status_code === 200){
      //               console.log(response.data)
      //               _that.categoryList = response.data.category_list.data;
      //             }
      //             else{
      //               _that.success_message = "";
      //               _that.error_message   = response.data.error;
      //             }
      //           })
      // },

      getArticleList()
      {
        let _that =this;

        axios.get('latest-article-list',
                {
                  headers: {
                    'Authorization': 'Bearer '+localStorage.getItem('authToken')
                  },
                  params :{
                    isAdmin : 1,
                    isRole : _that.current_user_role_id
                  },

                }).then(function (response){
                  if(response.data.status_code === 200)
                  {
                    _that.isArticleList = true;
                    _that.articleList = response.data.article_list;
                  }
                  else{
                    _that.success_message = "";
                    _that.error_message   = response.data.error;
                  }
                })
      },

      getAllTotalCount()
      {
        let _that =this;

        axios.post('total-count-data',{
                  isAdmin : 1
                },
                {
                  headers: {
                    'Authorization': 'Bearer '+localStorage.getItem('authToken')
                  },

                })
                .then(function (response) {
                  if(response.data.status_code === 200){
                    _that.totalCountList = response.data.total_count;
                    _that.totalUser = _that.totalCountList.total_user
                  }
                  else{
                    _that.success_message = "";
                    _that.error_message   = response.data.error;
                  }
                })
      },

      getBannerList()
      {
        let _that =this;

        axios.post('role-banners',
                {
                  role_id : this.current_user_role_id
                },
                {
                  headers: {
                    'Authorization': 'Bearer '+localStorage.getItem('authToken')
                  },

                })
                .then(function (response) {

                  _that.isBannerStatus = true;
                  if(response.data.status_code === 200){
                    // console.log(response.data.banner_list)
                    _that.bannerList = response.data.banner_list;
                  }
                  else{
                    _that.success_message = "";
                    _that.error_message   = response.data.error;
                  }
                })
      }

    },

    mounted(){



    },

    created()
    {

      this.user_info          = JSON.parse(localStorage.getItem("userInformation"));
      if (this.user_info != ''){

        // this.getCategoryList();

        this.current_user_role_id = this.user_info.roles[0].id
        this.getAllTotalCount();
        this.getBannerList();
        this.getArticleList();
      }

    }
  }
</script>

<style scoped>


</style>
