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
                  <input type="text" v-on:keyup.enter="query_string ? searchData() : ''" v-model="query_string" class="form-control" v-on:keyup="autoSuggetion" placeholder="Search Here" aria-label="Search Here" aria-describedby="searchBtn">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" id="searchBtn" type="button" @click="query_string ? searchData() : ''">
                      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
                        <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                      </svg>
                    </button>
                  </div>
                </div>
                <div v-if="suggestedArtiles.length" class="search-suggestion" id="search-suggestion" style="left:0; width: 100%">
                  <ul>
                    <li v-for="a_suggestion in suggestedArtiles" :key="a_suggestion.en_title"  @click.prevent="articleSearch(a_suggestion.slug)">
                      {{a_suggestion.en_title.length == 50 ? a_suggestion.en_title : (a_suggestion.en_title).substring(0,50)+"..."}}
                    </li>
                  </ul>
                </div>
              </div>

              <div class="breadcrumbs mt-10 mt-sm-0">
                <ul class="list-inline list-unstyled mb-0">
                  <li class="list-inline-item">
                    <router-link class="nav-item" :to="{ name: 'Home'}">
                      <i class="fa fa-home"></i>
                    </router-link>
                  </li>
                  <li class="list-inline-item">
                    <router-link v-if="aArticle.category" :to="{ name: 'CategoryList', params: { categoryID: aArticle.category.slug }}">
                      categories
                    </router-link>
                  </li>
                  <li class="list-inline-item">{{ aArticle.category? (aArticle.category.name).toLowerCase() : '' }}</li>
                </ul>
              </div>
              <!--                        <button @click="dynamicBackFunc()" class="btn d-block d-sm-inline-block mt-10 mb-sm-0 btn-primary btn-common-2 position-relative font-18 overflow-hidden ripple-btn text-left py-3 px-30 text-white order-sm-1"><i class="fa fa-angle-double-left"></i> Back</button>-->
            </div>
          </div>
        </section>


        <section class="category-details-area py-50 py-md-60 text-left">
          <div class="container">
            <div class="row" v-if="aArticle">
              <div class="col-md-12">
                <div>
                  <h1 class="mb-0 font-weight-bold">Articles Details Page</h1>

                  <div class="ta-wrapper d-flex align-items-center py-10 my-40">
                    <div class="avatar mr-10">
                      <img class="img-fluid" src="../assets/img/avatar.png" style="height: 50px; width: 50px" alt="avatar">
                    </div>
                    <div class="tc-wrapper">
                      <h5 v-cloak v-if="aArticle.user" class="my-0 pb-1">{{aArticle.user.first_name}} {{aArticle.user.last_name}}</h5>
                      <p class="mb-0">Post on: {{aArticle.created_at}}</p>
                    </div>
                  </div>

                  <div>
                    <small class="font-16"><strong>Category: </strong>{{aArticle.category ? aArticle.category.name : 'N/A'}}</small>
                  </div>
                  <p class="font-16"><strong>Tags: </strong>{{aArticle.tag}}</p>
                </div>

                <div class="pb-10">
                  <ul class="nav nav-tabs" id="myTab" v-if="aArticle.bn_title != 'n/a'">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="tab" href="#tabEnglish">English</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#tabBangla">Bangla</a>
                    </li>
                  </ul>
                </div>
              </div>

              <div class="col-md-9 col-md-8">
                <div class="tab-content pt-3" id="myTabContent">
                  <div class="tab-pane fade active show" id="tabEnglish">
                    <div class="ta-content-wrapper">
                      <h3 class="">{{aArticle.en_title}}</h3>
                      <p class="" v-if="aArticle.en_short_summary != ''">{{aArticle.en_short_summary}}</p>
                    </div>
                    <div class="ta-content-wrapper pb-10 d-block" v-if="userInformation != '' && aArticle.contents">
                      <div v-for="(a_content) in aArticle.contents" :key="a_content.id">

                        <div class="pb-5" v-if="a_content.role_id.includes(userInformation.roles[0].id) && a_content.en_body!='n/a'" v-html="a_content.en_body"></div>

                      </div>
                    </div>


                    <div class="ta-content-wrapper pb-10 d-block" v-else-if="userInformation == '' && aArticle.contents">

                      <div v-for="(a_content) in aArticle.contents" :key="a_content.id">

                        <div v-if="a_content.role_id.includes('0') && a_content.en_body!='n/a'" class="pb-5" v-html="a_content.en_body"></div>

                      </div>
<!--                      <div><u class="text-primary">Log in read the article.</u></div>-->

                    </div>


<!--                    <div v-else><u class="text-primary">Log in read the article.</u></div>-->
                  </div>


                  <!--                                bangla-->
                  <div class="tab-pane fade" id="tabBangla" v-if="aArticle.bn_title != 'n/a'">
                    <div class="ta-content-wrapper">
                      <h3 class="">{{aArticle.bn_title}}</h3>
                      <p class="" v-if="aArticle.bn_short_summary != ''">{{aArticle.bn_short_summary}}</p>
                    </div>

                    <div class="ta-content-wrapper pb-10 d-block" v-if="userInformation && aArticle.contents">
                      <div v-for="(a_content) in aArticle.contents" :key="a_content.id">

                        <div class="pb-5" v-if="a_content.role_id.includes(userInformation.roles[0].id) && a_content.bn_body!='n/a'" v-html="a_content.bn_body"></div>

                      </div>
                    </div>

                    <div class="ta-content-wrapper pb-10 d-block" v-else-if="userInformation == '' && aArticle.contents">

                      <div v-for="(a_content) in aArticle.contents" :key="a_content.id">

                        <div v-if="a_content.role_id.includes('0') && a_content.bn_body!='n/a'" class="pb-5"  v-html="a_content.bn_body"></div>

                      </div>
<!--                      <div><u class="text-primary">নিবন্ধ পড়তে লগ ইন করুন।</u></div>-->

                    </div>

<!--                    <div v-else><u class="text-primary">নিবন্ধ পড়তে লগ ইন করুন।</u></div>-->
                  </div>
                </div>
                <!--              resource block-->

                <div class="pb-10" v-if="aArticle.media.length > 0 && userInformation">
                  <h5 class="mb-0 font-weight-bold pb-2">Download Resources</h5>
                  <ul class="pl-15">
                    <li v-for="a_file in aArticle.media" :key="a_file.id">
                      <a :href="a_file.url">{{a_file.url | formatFileName }}</a>
                    </li>
                  </ul>
                </div>

                <!--              comment-block-->
                <div v-if="aArticle.commentable_status && userInformation !=''">
                  <h5 class="mb-0 font-weight-bold pb-10">Discussions</h5>
                  <!-- Comment Section Start -->
                  <div class="comment-box">
                    <div class="comment-input-box d-flex">
                      <div class="featured-image avatar mr-10">
                        <img class="img-fluid rounded-circle" src="../assets/img/avatar.png" style="height: 50px; width: 50px" alt="avatar">
                      </div>
                      <div class="comment-input w-100">
                        <input type="hidden" v-model="aArticle.id">
                        <input type="hidden" v-model="userInformation.id">
                        <textarea v-model="comment_body" placeholder="Write your comment..." class="form-control px-10 py-10"></textarea>
                        <input hidden type="radio" checked value="0" v-model="status">
                      </div>
                    </div>
                    <div class="replied-btn-wrapper pt-10 text-right">
                      <button class="btn btn-primary common-gradient-btn text-white px-25 py-2 rounded-pill" @click="addComment()">Add</button>
                    </div>
                  </div>

                  <div v-if="comments !='' && userInformation !=''">
                    <div class="reply-box mt-30" v-for="a_comment in comments" :key="a_comment.id">
                      <div class="reply-input-box d-flex">
                        <div class="featured-image avatar mr-10">
                          <img class="img-fluid rounded-circle" src="../assets/img/avatar.png" style="height: 50px; width: 50px" alt="avatar">
                        </div>
                        <div class="reply-text position-relative w-100 px-10 py-3">
                          <!-- action button start -->
                          <div class="action-button-wrapper">
                            <button :id="'comment_edit_'+a_comment.id" class="btn btn-success ripple-btn m-1" @click="commentEdit('comment_edit_'+a_comment.id,'comment_box_'+a_comment.id,'comment_edit_box_'+a_comment.id,'comment_update_'+a_comment.id)"  v-if="a_comment.user.id ==userInformation.id"><i class="fa fa-pencil"></i></button>

                            <button  class="btn btn-danger ripple-btn m-1" v-if="a_comment.user.id ==userInformation.id" @click="commentDelete(a_comment.id)"><i class="fa fa-trash"></i></button>
                          </div>
                          <!-- action button end -->

                          <!-- reply content box start -->
                          <div class="reply-content-box-wrap">
                            <h6 class="font-weight-bold">{{a_comment.user.first_name ? a_comment.user.first_name+' '+ a_comment.user.last_name : a_comment.user_id}}</h6>
                            <div>
                              <small><strong>Posted on:</strong> {{a_comment.created_at}}</small>
                            </div>
                            <div class="reply-status-box d-none">
                              <small><strong>Status : </strong>
                                <input type="hidden" v-model="a_comment.id">
                                <label class="col-form-label py-1 mr-2" for="status_inactive"><input class="mr-1" id="status_inactive" type="radio" v-model="a_comment.status" value="0" @change="changeCommentStatus($event, a_comment.id)"/> Inactive</label>
                                <label class="col-form-label py-1" for="status_active"><input class="mr-1" id="status_active" type="radio" v-model="a_comment.status" value="1" @change="changeCommentStatus($event, a_comment.id)"/> Active</label>
                              </small>
                            </div>

                            <div class="body-content-box pt-1 mt-1">
                              <p :id="'comment_box_'+a_comment.id" style="display: block">{{a_comment.comment_body}}</p>

                              <textarea style="display: none" :id="'comment_edit_box_'+a_comment.id" v-model="a_comment.comment_body" placeholder="Write your comment..." class="form-control py-10"></textarea>

                              <button style="display: none" :id="'comment_update_'+a_comment.id" @click="commentUpdate(a_comment,'comment_edit_'+a_comment.id,'comment_box_'+a_comment.id,'comment_edit_box_'+a_comment.id,'comment_update_'+a_comment.id)" class="btn-primary btn common-gradient-btn text-white py-1 px-25 rounded-pill m-2">Update</button>
                            </div>
                          </div>
                          <!-- reply content box end -->
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-else>No comment yet!</div>
                </div>
              </div>

              <div class="col-lg-3 col-md-6 col-12">
                <div class="article-sidebar">
                  <div class="menu-wrapper bg-white mb-30">
                    <h3 class="menu-title mb-0 p-15">Recent Articles</h3>
                    <ul class="nav nav-pills flex-column pb-10">
                      <li class="nav-item" v-for="a_art in allArticle" :key="a_art.id">
                        <a class="nav-link px-0 py-0"  href="#" @click.prevent="articleSearch(a_art.slug)">
                          <div class="recent-article-item-wrapper d-flex p-2">
                            <!--                          <div v-if="a_art.contents"></div>-->
                            <div class="ra-item-image" v-if="a_art.contents == ''">
                              <img :src="static_image['article']" alt="no image" class="img-fluid">
                            </div>

                            <div v-for="a_content in a_art.contents" :key="a_content.id">
                              <div class="ra-item-image" v-if="a_content.en_body.match(regexImg)">
                                <img class="img-fluid" :src="((a_content.en_body).match(regexImg) ? (a_content.en_body).match(regexImg)[0]: static_image['article'] )" alt="no image">
                              </div>

                              <div class="ra-item-image" v-else>
                                <img :src="static_image['article']" alt="no image" class="img-fluid">
                              </div>
                            </div>
                            <div class="ra-item-content">
                              <span v-if="(a_art.en_title).length<40"> {{ a_art.en_title }}</span>
                              <span v-else> {{ (a_art.en_title).substring(0,40)+"..." }}</span>
                            </div>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </div>

                  <div class="menu-wrapper bg-white mb-30">
                    <h3 class="menu-title mb-20 p-15">Categories</h3>
                    <div class="article-details-cat-wrapper">
                      <ul class="nav nav-pills flex-column px-15 pb-15">
                        <li class="nav-item" v-for="a_cate_art in categoryHasArticle" :key="a_cate_art.id">
                          <router-link class="nav-link px-0 py-0"  :to="{ name: 'CategoryList', params: { categoryID: a_cate_art.slug }}">
                            <div class="recent-article-item-wrapper d-flex">
                              <div class="ra-item-image">
                                <img class="img-fluid" :src="a_cate_art.media[0] ? a_cate_art.media[0].url : static_image['article'] " alt="no image">
                              </div>
                              <div class="ra-item-content">
                                {{a_cate_art.name}}
                              </div>
                            </div>
                          </router-link>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <div class="action-modal-wraper" v-if="success_message">
                <span>{{ success_message }}</span>
              </div>
              <div class="action-modal-wraper-error" v-if="error_message">
                <span>{{ error_message }}</span>
              </div>

              <div class="col-md-9 col-12" v-if="aArticle.commentable_status">

                <!-- Comment Section End -->

                <!-- Comment Read Start -->

                <!--Comment Read  End-->
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
  import $ from 'jquery'
  export default {
    name: "ArticleDetail",
    components:{
      Loader,
    },
    filters:{
      formatFileName(fileName){
        let res;
        res = fileName.split('/')
        return res[res.length - 1];
      }
    },
    data(){
      return{
        success_message :'',
        error_message :'',
        userToken: '',
        routePath : '',
        isLoading : true,
        articleSlug:'',
        aArticle:'',
        rolesAccess : [],
        articleSlugArr:[],
        suggestedArtiles:[],
        articleCounter:0,
        allCategoryArticle:'',
        categoryHasArticle:[],
        allArticle:'',
        query_string:'',
        comment_body : '',
        status : 0,
        comments : '',


        static_image            : [],
        regexImg                : /(http:\/\/[^">]+)/g,
        // articleImageArray       : []
      }
    },
    methods:{
      commentDelete(commentID){
        let _that = this;
        axios.delete('comments/delete',
                {
                  data    : {
                    id              : commentID
                  },
                  headers : {
                    'Authorization'     : 'Bearer ' +_that.userToken
                  },
                }).then(function (response) {
          if (response.data.status_code == 200)
          {
            _that.getCommentListWithArticle();
            _that.success_message       = "Comment Deleted Successfully";
            _that.setTimeoutElements();

          }
          else
          {
            _that.success_message       = "";
            _that.error_message         = response.data.error;
          }

        }).catch(function (error) {
          console.log(error);
        });
      },
      setTimeoutElements()
      {
        setTimeout(() => this.success_message = "", 2e3);
        setTimeout(() => this.error_message = "", 2e3);
      },
      commentUpdate(data,btnEdit,paraID,textareaID,btnID){
        let _that = this;
        let updateBody =$("#"+textareaID).val();

        axios.put('comments/update',
                {
                  id            : data.id,
                  comment_body    : updateBody,
                },
                {
                  headers: {
                    'Authorization' : 'Bearer '+_that.userToken
                  }
                }).then(function (response) {
          console.log(response);
          if (response.data.status_code === 200){
            $("#"+btnEdit).css({display : 'inline'})
            $("#"+paraID).css({display : 'inline'})
            $("#"+textareaID).css({display : 'none'})
            $("#"+btnID).css({display : 'none'})
            _that.getCommentListWithArticle();
            _that.success_message       = "Comment pending wait for admin approval!";
            _that.setTimeoutElements();
          }
          else if (response.data.status_code === 400){
            console.log(response.data.errors);
            _that.showServerError(response.data.errors)
          }
        })
      },
      commentEdit(btnEdit,paraID,textareaID,btnID){
        $("#"+btnEdit).css({display : 'none'})
        $("#"+paraID).css({display : 'none'})
        $("#"+textareaID).css({display : 'inline'})
        $("#"+btnID).css({display : 'inline'})
      },
      addComment(){
        let _that = this;

        let formData = new FormData();
        formData.append('post_id', _that.aArticle.id);
        formData.append('user_id', _that.userInformation.id);
        formData.append('comment_body', _that.comment_body);
        formData.append('status', _that.status);

        axios.post('comments',formData,{
          headers: {
            'Authorization' : 'Bearer '+_that.userToken
          }
        }).then(function (res) {
          console.log(res.data.status_code);
          if (res.data.status_code == 200){
            _that.getCommentListWithArticle()
            _that.success_message = "Comment pending wait for admin approval!";
            _that.comment_body='';
            _that.setTimeoutElements();
          }
        })
      },
      getCommentListWithArticle(){
        let _that = this;

        axios.get('post-comments/'+_that.articleID,{
          // params:{
          //   isVisitor : 1
          // },
          headers: {
            'Authorization' : 'Bearer '+_that.userToken
          }
        }).then(function (res) {
          console.log(res.data)
          _that.comments = res.data.comments;
          console.log(_that.comments)
        })
      },
      articleSearch(v){
        let _that = this;
        _that.articleSlug = v;
        _that.articleCounter++;
        let last_history_article = _that.articleSlugArr[_that.articleSlugArr.length - 1];

        if (last_history_article != _that.articleSlug){
          _that.articleSlugArr.push(_that.articleSlug);
          axios.get('article-details/'+_that.articleSlug)
                  .then(function (response) {
                    console.log(response)
                    _that.aArticle = response.data.article_info;
                    // _that.$router.push('/article-detail/'+_that.articleSlug)
                    _that.$router.push('/article-detail/'+_that.articleID+'/'+_that.articleSlug).catch(() => {});
                    // console.log(_that.aArticle.category? _that.aArticle.category.name : '')
                    if (document.getElementById("search-suggestion")){
                      document.getElementById("search-suggestion").style.visibility = "hidden";
                    }
                    // document.getElementById("search-suggestion").style.visibility = "hidden";
                  })
        }else{
          console.log(last_history_article);
        }

      },
      searchData()
      {
        let _that = this;
        if (localStorage.query_string){
          localStorage.setItem('query_string','');
          localStorage.setItem('query_string',this.query_string);
        }else{
          localStorage.setItem('query_string',this.query_string);
        }
        _that.$router.push({ name: 'Search'});
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

      getRecentArticleList()
      {
        let _that = this;
        axios.get('article-list')
                .then(function (response) {
                  _that.allArticle = response.data.article_list;
                })
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
                    console.log(_that.categoryHasArticle);
                  }
                })
      },


      dynamicBackFunc() {
        let _that =this;
        _that.articleSlugArr = _that.articleSlugArr.slice(0,_that.articleSlugArr.length-1);
        _that.articleSlug = _that.articleSlugArr[_that.articleSlugArr.length-1];
        if (_that.articleSlug){
          axios.get('article-details/'+_that.articleSlug)
                  .then(function (response) {
                    _that.aArticle = response.data.article_info;
                    _that.$router.push('/article-detail/'+_that.articleSlug).catch(() => {});
                    console.log(_that.aArticle)
                  })
        }
        else{
          _that.$router.push('/');
        }
      },

      getStaticMedia()
      {
        this.static_image['category'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
        this.static_image['article'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
        this.static_image['banner'] = axios.defaults.baseURL.replace('api/','')+'static_media/banner.jpg';
      }
    },
    created() {
      this.articleSlug = this.$route.params.articleSlug;
      this.articleID = this.$route.params.articleID;
      if (sessionStorage.userInformation){
        this.userInformation = JSON.parse(sessionStorage.userInformation);
        this.userToken = sessionStorage.userToken;
        this.getCommentListWithArticle();
      }else{
        this.userInformation = '';
      }
      this.articleSearch(this.articleSlug);
      this.getCategoryArticleList();
      this.getStaticMedia();
      this.getRecentArticleList();
    }
  }
</script>

<style scoped>
  textarea{
    resize: none !important;
  }
</style>
