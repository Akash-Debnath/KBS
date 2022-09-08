<template>
  <div class="page-wrapper">
    <div>
      <!--    before data load-->
      <div v-if="isLoading">
        <Loader/>
      </div>

      <div v-cloak v-else class="display min-height-wrapper">
        <main>
          <section class="banner-area d-flex align-items-center py-70 py-md-150" :style="{ backgroundImage: 'url(' + (frontPageData ? frontPageData.banner : static_image['banner']) + ')' }">
            <div class="container">
              <div class="mxw-575">
                <div class="search-wrapper position-relative">
                  <h1 class="section-title pb-10 mb-0 text-center">{{ frontPageData ? frontPageData.title : 'Knowledge Based System' }}</h1>
                  <p class="text-center">{{ frontPageData ? frontPageData.description : '' }}</p>
                  <!--              display search form-->
                  <SearchForm/>
                </div>
              </div>
            </div>
          </section>

          <section class="topics-area py-50 py-md-60">
            <div class="container">
              <BannerSlider v-if="bannerInformation!=''" :banner_list="bannerInformation"/>
              <div class="row justify-content-md-center">
                <div class="col-lg-8">
                  <h1 class="section-title bottom-bar text-center mb-10 pb-20">Explore Categories</h1>
                  <p class="font-20 text-center pt-10">We did our best to cover all topics related to this product. Each section have number which represent number of topic in each category.</p>
                </div>
              </div>

              <div class="row text-left">
                <div class="col-xl-4 col-lg-4 mb-30" v-for="a_cate_art in categoryHasArticle" :key="a_cate_art.id">
                  <div class="featured-item position-relative overflow-hidden bg-white align-items-stretch h-100">
                    <router-link :to="{ name: 'CategoryList', params: { categorySlug: a_cate_art.slug }}">
                      <div class="featured-image" >
                        <img class="img-fluid"  :src="(a_cate_art.media).length > 0 ? a_cate_art.media[0].url  :static_image['category']" alt="no image">
                      </div>
                      <div class="featured-content-box p-15 p-md-30">
                        <h4 class="mb-10 mb-lg-0 text-center">{{ a_cate_art.name }}</h4>
                      </div>
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="topics-area py-50 py-md-60">
            <div class="container">
              <div class="row justify-content-md-center">
                <div class="col-lg-8">
                  <h1 class="section-title bottom-bar text-center mb-10 pb-20">Featured Articles</h1>
                  <p class="font-20 text-center pt-10">We did our best to cover all topics related to this product.</p>
                </div>
              </div>

              <div class="row text-left">
                <div class="col-xl-4 col-lg-4 mb-30" v-for="a_latest_art in allLatestArticles" :key="a_latest_art.id">
                  <div class="featured-item position-relative overflow-hidden bg-white align-items-stretch h-100">
                    <router-link class="article-item-box d-block bg-white position-relative align-items-stretch h-100 overflow-hidden" :to="{ name: 'ArticleDetail', params: { articleID: a_latest_art.id,articleSlug: a_latest_art.slug }}">
                      <div class="featured-image" v-if="a_latest_art.contents == ''">
                        <img :src="static_image['article']" alt="no image" class="img-fluid">
                      </div>
                      <div v-for="a_content in a_latest_art.contents" :key="a_content.id">
                        <div class="featured-image" v-if="a_content.en_body.match(regexImg)">
                          <img :src="((a_content.en_body).match(regexImg) ? (a_content.en_body).match(regexImg)[0]: static_image['article'] )" alt="no image" class="img-fluid">
                        </div>

                        <div class="featured-image" v-else>
                          <img :src="static_image['article']" alt="no image" class="img-fluid">
                        </div>
                      </div>
                      <div class="featured-content-box p-15 p-md-20">
                        <h4 class="mb-15 font-18" v-if="(a_latest_art.en_title).length<35"> {{ a_latest_art.en_title }}</h4>
                        <h4 class="mb-15 font-18" v-else> {{ (a_latest_art.en_title).substring(0,35)+"..." }}</h4>

                        <p class="font-14 mb-0" v-if="(a_latest_art.en_short_summary)== null"> N/A </p>
                        <p class="font-14 mb-0" v-else-if="(a_latest_art.en_short_summary).length < 100"> {{ a_latest_art.en_short_summary }} </p>
                        <p class="font-14 mb-0" v-else> {{ (a_latest_art.en_short_summary).substring(0,100)+"..." }}</p>
                      </div>
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <section class="topics-area py-50 py-md-60">
            <div class="container">
              <div class="row justify-content-md-center">
                <div class="col-lg-8">
                  <h1 class="section-title bottom-bar text-center mb-10 pb-20">FAQ</h1>
                  <p class="font-20 text-center pt-10">Frequently Asked Questions</p>
                </div>
              </div>

              <div class="row">
                <!--                style="min-height: 350px ; "-->
                <div class="col-lg-12">
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
              </div>

            </div>
          </section>
        </main>
      </div>

    </div>
  </div>
</template>

<script>

  import Loader from "@/components/Loader";
  import SearchForm from "@/components/Search";
  import BannerSlider from "@/components/slider/slider";
  // import LatestFaq from '../components/faq/LatestFaq'

  import $ from 'jquery'
  import axios from "axios";

  $(document).on('click','.custom-accordion .heading', function () {
    $(this).toggleClass("active").next().slideToggle();
    $(".contents").not($(this).next()).slideUp(200);
    $(this).siblings().removeClass("active");
  });

  export default {
    name: 'Home',
    components: {
      Loader,
      SearchForm,
      BannerSlider,
      // LatestFaq
    },
    data() {
      return {
        static_image            : [],
        isLoading               : true,
        allCategoryArticle      : '',
        frontPageData           : '',
        categoryHasArticle      : [],
        allLatestArticles       : '',
        countBanner : 0,
        // regexImg                : /<img[^>]+src="(http:\/\/[^">]+)"/g,
        regexImg                : /(http:\/\/[^">]+)/img,
        all_Faqs                : '',
        userInformation         : '',
        bannerInformation       : '',
        isAuthinticate          : false,
      }
    },
    methods:{
      getCategoryArticleList()
      {
        let _that =this;
        axios.get('category-article-list', { cache: false })
                .then(function (response) {
                  if(response.data.status_code === 200){
                    _that.isLoading = false;
                    _that.allCategoryArticle = response.data.category_list;
                    _that.allCategoryArticle.forEach(val =>{
                      if (val.article.length!=0){
                        if (_that.categoryHasArticle.length <6){
                          _that.categoryHasArticle.push(val);
                        }
                      }
                    })
                  }
                })
      },
      getLatestArticleList()
      {
        let _that =this;

        if (_that.userInformation == ''){
          axios.get('article-list').then(function (response)
          {
            if(response.data.status_code === 200){
              _that.allLatestArticles = response.data.article_list;
            }
          })
        }else{
          axios.get('article-list',
          {
             params : {
               isRole : 1
             }
          }
          ).then(function (response)
          {
            if(response.data.status_code === 200){
              _that.allLatestArticles = response.data.article_list;
            }
          })
        }


      },
      getPageDecorationData()
      {
        let _that =this;
        axios.get('front-page-config', { cache: false })
                .then(function (response) {
                  if(response.data.status_code === 200){
                    _that.frontPageData = response.data.page_config_info;
                  }
                })
      },

      getStaticMedia()
      {
        this.static_image['category'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
        this.static_image['article'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
        this.static_image['banner'] = axios.defaults.baseURL.replace('api/','')+'static_media/banner.png';
        this.static_image['newlogo'] = axios.defaults.baseURL.replace('api/','')+'static_media/new-logo.png';
        this.static_image['smalllogo'] = axios.defaults.baseURL.replace('api/','')+'static_media/small-logo.png';
      },

      allFaqs(){
        let _that = this;
        axios.get('faq-list',{
          params : {
            isLatest : 1
          },
        })
                .then(function (response) {
                  console.log(response.data.faq_list)
                  _that.all_Faqs = response.data.faq_list;
                })
      },
    },
    created()
    {
      if (sessionStorage.userInformation){
        this.userInformation = JSON.parse(sessionStorage.userInformation);
        this.bannerInformation = JSON.parse(sessionStorage.bannerInformation);
        console.log(this.bannerInformation);
        if (!this.userInformation){
          this.isAuthinticate = false;
        } else{
          this.isAuthinticate = true;
        }
      }else{
        this.userInformation = '';
      }

      this.getStaticMedia()
      this.getPageDecorationData();
      this.getCategoryArticleList();
      this.getLatestArticleList();
      this.allFaqs();
      localStorage.removeItem('category-article-list');
    },
    mounted () {
      document.body.classList.add('home');

    },
    destroyed () {
      document.body.classList.remove('home')
    }
  }
</script>

<style scoped>
  .banner-area .section-title:first-letter {
    color: #d84a4f;
  }

  .nav-tabs {border-bottom: 0;}
  .nav-tabs .nav-link.active,
  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link{padding: 5px 20px;}.banner-area .section-title:first-letter {
                                           color: #d84a4f;
                                         }

  .nav-tabs {border-bottom: 0;}
  .nav-tabs .nav-link.active,
  .nav-tabs .nav-item.show .nav-link,
  .nav-tabs .nav-link{padding: 5px 20px;}
  .mxw-575 {max-width: 575px;}
  @media (max-width: 767px){.mxw-575{margin: 0 auto;}}
</style>
