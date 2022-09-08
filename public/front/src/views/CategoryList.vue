<template>
  <div class="page-wrapper">
    <div v-if="isLoading">
      <Loader></Loader>
    </div>

    <div v-else v-cloak class="min-height-wrapper">
      <main>
        <section class="inner-search-area py-20">
          <div class="container">
            <div class="search-input-wrapper d-block d-sm-flex justify-content-between align-items-center">
              <div class="search-input-box position-relative order-sm-2">
                <div class="input-group" style="max-width: 100%;width: 100%;">
                  <input type="text" class="form-control" v-on:keyup.enter="query_string ? searchData() : ''" v-model="query_string"  placeholder="Search Article Here"  v-on:keyup="autoSuggetion" aria-label="Search Here" aria-describedby="searchBtn">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" id="searchBtn" type="button" @click="query_string ? searchData() : ''">
                      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                        <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                      </svg>
                    </button>
                  </div>
                </div>
                <div v-if="suggestedArtiles.length" class="search-suggestion" style="left:0; width: 100%">
                  <ul>
                    <li  v-for="a_suggestion in suggestedArtiles" :key="a_suggestion.id">
                      <router-link class="" :to="{ name: 'ArticleDetail', params: { articleID: a_suggestion.id,articleSlug: a_suggestion.slug }}">
                        {{a_suggestion.en_title.length == 50 ? a_suggestion.en_title : (a_suggestion.en_title).substring(0,50)+"..."}}
                      </router-link>
                    </li>
                  </ul>
                </div>
              </div>

              <!-- <button @click="dynamicBackFunc()" class="btn d-block d-sm-inline-block mt-10 mb-sm-0 btn-primary btn-common-2 position-relative font-18 overflow-hidden ripple-btn text-left py-3 px-30 text-white order-sm-1"><i class="fa fa-angle-double-left"></i> Back</button> -->
              <div class="breadcrumbs mt-10 mt-sm-0">
                <ul class="list-inline list-unstyled mb-0">
                  <li class="list-inline-item">
                    <router-link class="nav-item" :to="{ name: 'Home'}">
                      <i class="fa fa-home"></i>
                    </router-link>
                  </li>
                  <li class="list-inline-item">
                    <router-link :to="{ name: 'CategoryList', params: { categorySlug: routePath ? routePath : categorySlug }}">
                      categories
                    </router-link>
                  </li>
                  <li class="list-inline-item">{{ routePath ? routePath : categorySlug }}</li>
                </ul>
              </div>
            </div>
          </div>
        </section>
        <section class="category-page-area py-50 py-md-60">
          <div class="container">
            <div class="row">

              <div class="col-lg-4 col-md-5 text-left">
                <div class="menu-wrapper cat-menu-wrapper bg-white mb-50" v-if="categoryHasArticle">
                  <h3 class="menu-title mb-20 p-15">Categories</h3>

                  <div class="nav nav-pills flex-column d-block px-15" v-for="a_cate_art in categoryHasArticle" :key="a_cate_art.id">
                    <ul class="list-unstyled list-inline mb-0">
                      <tree-view class="item" :item="a_cate_art" :router_path="categorySlug" @category-slug="getCategorySlugFromChild"></tree-view>
                    </ul>

                  </div>
                </div>

                <div v-if="selectedCategory">
                  <TagView :selectedCategory="selectedCategory"/>
                </div>
              </div>

              <div class="col-lg-8 col-md-7">
                <div class="mb-30 mt-0">
                  <h1 class="mb-3">ARTICLE LIST</h1>
                  <h6 class="heading-thin text-theme-grey font-18 mb-20">Getting Started</h6>

                  <list-of-article v-if="selectedCategory" :selected_category="selectedCategory"></list-of-article>

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
  import Loader from "@/components/Loader";
  import TreeView from "@/components/TreeView";
  import TagView from "@/components/TagView";
  import listOfArticle from "@/components/listOfArticle";
  import axios from 'axios'


  export default {
    name: "CategoryList",
    components:{
      TagView,
      Loader,
      TreeView,
      listOfArticle
    },
    data(){
      return{
        categoryArticleList       : '',
        routePath                 : '',
        isLoading                 : true,
        allCategoryArticle        : '',
        categoryHasArticle        : [],
        categorySlug              : '',
        suggestedArtiles          : [],
        categoryIDArr             : [],
        selectedCategory          : '',
        userInformation           : '',
        selectedCategoryArr       : [],
        query_string              : '',
        regexImg                  : /(http:\/\/[^">]+)/img,
        static_image              : [],
        pagination                : {
          from                    : '',
          to                      : '',
          first_page_url          : '',
          last_page               : '',
          last_page_url           : '',
          next_page_url           : '',
          prev_page_url           : '',
          path                    : '',
          per_page: 5,
          total: ''
        },

      }
    },
    methods:{
      getCategorySlugFromChild(status){
        // console.log(status)
        this.routePath = status;
        // console.log(this.routePath)
        this.selectedCategory = '';
        this.categorySearch(status);
      },
      getStaticMedia()
      {
        this.static_image['category'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
        this.static_image['article'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
        this.static_image['banner'] = axios.defaults.baseURL.replace('api/','')+'static_media/banner.jpg';
      },

      searchData(){
        let _that = this;
        if (localStorage.query_string){
          localStorage.setItem('query_string','');
          localStorage.setItem('query_string',this.query_string);
        }else{
          localStorage.setItem('query_string',this.query_string);
        }
        _that.$router.push({ name: 'Search'});
      },
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
                        _that.categoryHasArticle.push(val);
                      }
                    })
                  }
                })
      },

      categorySearch(v) {
        let _that = this;
        let pageUrl;
        _that.categorySlug = v;
        pageUrl = pageUrl == undefined ? 'article/category/'+_that.categorySlug+'?page=1' : pageUrl;
        if (this.userInformation==""){
          axios.get(pageUrl)
                  .then(function (response) {
                    // console.log(response.data.article_list.data);
                    _that.selectedCategory = response.data.article_list.data;
                    _that.pagination = response.data.article_list;
                    // console.log(_that.selectedCategory)
                    _that.$router.push('/category-list/'+_that.categorySlug).catch(() => {});
                  })
        } else {
          axios.get(pageUrl,
                  {
                    params : {
                      isRole : 1
                    }
                  })
                  .then(function (response) {
                    // console.log(response.data.article_list.data);
                    _that.selectedCategory = response.data.article_list.data;
                    _that.pagination = response.data.article_list;
                    _that.$router.push('/category-list/'+_that.categorySlug).catch(() => {});
                  })
        }
      },


      autoSuggetion(e) {
        let _that = this;
        _that.suggestedArtiles = [];
        if(e.target.value.length>3){
          setTimeout(()=>{
            if (this.userInformation ==''){
              axios.get('article/search/'+e.target.value)
                      .then(function (res) {
                        _that.suggestedArtiles = res.data.article_list.data;
                        console.log(_that.suggestedArtiles);
                      })
            } else{
              axios.get('article/search/'+e.target.value,{
                params : {
                  isRole :  _that.userInformation.roles[0].id
                }
              }).then(function (res) {
                _that.suggestedArtiles = res.data.article_list.data;
                console.log(_that.suggestedArtiles);
              })
            }

          },500);
        }

      },

      changeCategoryArticlePage(categorySlug,pageUrl){
        window.scrollTo(0, 0);
        let _that = this;
        pageUrl = pageUrl == undefined ? 'article/category/'+categorySlug+'?page=1' : pageUrl;
        axios.get(pageUrl)
                .then(function (response) {
                  _that.selectedCategory = response.data.article_list.data;
                  _that.pagination = response.data.article_list;
                  _that.$router.push('/category-list/'+_that.categorySlug)
                })
      },
    },
    created() {
      if (sessionStorage.userInformation) {
        this.userInformation = JSON.parse(sessionStorage.userInformation);
      }
      this.categorySlug = this.$route.params.categorySlug;
      this.getCategoryArticleList();
      this.categorySearch(this.categorySlug);
      this.getStaticMedia();
    }
  }
</script>

<style>

</style>
