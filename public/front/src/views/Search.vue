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
                            <div class="inner-search-wrapper position-relative order-sm-2">
                                <div class="input-group" style="max-width: 100%;width: 100%;">
                                    <input type="text" class="form-control" v-on:keyup.enter="query_string ? searchData() : ''" v-model="query_string" v-on:keyup="autoSuggetion" placeholder="Search Article Here" aria-label="Search Here" aria-describedby="searchBtn">
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
                                        <li  v-for="a_suggestion in suggestedArtiles" :key="a_suggestion.id"><router-link class="" :to="{ name: 'ArticleDetail', params: { articleID: a_article.id,articleSlug: a_suggestion.slug }}">{{a_suggestion.en_title.length == 50 ? a_suggestion.en_title : (a_suggestion.en_title).substring(0,50)+"..."}}</router-link></li>
                                        <!--                      <li  v-for="a_suggestion in suggestedArtiles" :key="a_suggestion.id"><router-link class="" :to="{ name: 'ArticleDetail', params: { articleSlug: a_suggestion.slug }}">{{a_suggestion.slug}}</router-link></li>-->
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
                                        search
                                    </li>

                                    <li class="list-inline-item">
                                        <!--                    {{query_string}}-->
                                        {{query_string == 50 ? query_string : (query_string).substring(0,50)+"..."}}
                                    </li>
                                </ul>
                            </div>
                            <!--                        <button @click="dynamicBackFunc()" class="btn d-block d-sm-inline-block mt-10 mb-sm-0 btn-primary btn-common-2 position-relative font-18 overflow-hidden ripple-btn text-left py-3 px-30 text-white order-sm-1"><i class="fa fa-angle-double-left"></i> Back</button>-->
                        </div>
                    </div>
                </section>

                <section class="category-page-area py-50 py-md-60 text-left">
                    <div class="container">
                        <div class="mb-30 mt-20">
                            <h1 class="mb-2">Search Result</h1>
                            <h6 class="heading-thin text-theme-grey font-18">List of Article in give keyword : {{query_string}} </h6>
                        </div>
                        <div class="item-list">
                            <div id="searchList" data-keyword="What is Knowledge Base System?">
                                <div class="topics-category-holder my-20">
                                    <div class="topics-category-items">
                                        <ul>
                                            <li v-for="a_article in allArticles" :key="a_article.id">


                                                <router-link class="article-item-list-box d-sm-flex position-relative overflow-hidden" :to="{ name: 'ArticleDetail', params: { articleID: a_article.id,articleSlug: a_article.slug }}">
                                                    <!--                          <div class="article-list-image mb-20 mb-sm-0">-->
                                                    <!--                            <img :src="((a_article.en_body).match(regexImg) ? (a_article.en_body).match(regexImg)[0]: static_image['article'] )" alt="no image" class="img-fluid">-->
                                                    <!--                          </div>-->
                                                    <div class="article-list-image mb-20 mb-sm-0" v-if="a_article.contents == ''">
                                                        <img :src="static_image['article']" alt="no image" class="img-fluid">
                                                    </div>

                                                    <div v-for="a_content in a_article.contents" :key="a_content.id">
                                                        <div class="article-list-image mb-20 mb-sm-0"  v-if="a_content.en_body.match(regexImg)">
                                                            <img :src="((a_content.en_body).match(regexImg) ? (a_content.en_body).match(regexImg)[0]: static_image['article'] )" alt="no image" class="img-fluid">
                                                        </div>

                                                        <div class="article-list-image mb-20 mb-sm-0" v-else>
                                                            <img :src="static_image['article']" alt="no image" class="img-fluid">
                                                        </div>
                                                    </div>
                                                    <div class="article-content-list-box pl-0 pl-sm-10">
                                                        <h3 class="article-list-title mb-0 pb-2 font-20">
                                                            <span v-if="(a_article.en_title).length<70"> {{ a_article.en_title }}</span>
                                                            <span v-else> {{ (a_article.en_title).substring(0,70)+"..." }}</span>
                                                        </h3>
                                                        <small class="font-8 mb-0 pb-2 d-block">Published at: {{a_article.created_at}}</small>
                                                        <p class="font-14 mb-0" v-if="(a_article.en_short_summary).length<200">{{a_article.en_short_summary}}</p>
                                                        <p class="font-14 mb-0" v-else>{{(a_article.en_short_summary).substring(0,200)+"..."}}</p>
                                                    </div>
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="allArticles">
                            <div v-for="(a_article,index) in allArticles" :key="a_article.id">
                                <div v-if="index===0">
                                    <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4">
                                        <ul class="pagination">
                                            <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item mx-1">
                                                <a @click.prevent="searchData(pagination.first_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                                            </li>
                                            <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item mx-1">
                                                <a @click.prevent="searchData(pagination.prev_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                                            </li>
                                            <li v-for="n in pagination.last_page" class="page-item mx-1"  :key="n">
                                                <a @click.prevent="searchData('article/search/'+query_string+'?page='+n)" class="px-3 bg-primary text-white py-2 rounded-sm" href="#">{{ n }}</a>
                                            </li>

                                            <li :class="[{disabled:!pagination.next_page_url}]" class="page-item mx-1">
                                                <a @click.prevent="searchData(pagination.next_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                            </li>
                                            <li :class="[{disabled:!pagination.next_page_url}]" class="page-item mx-1">
                                                <a @click.prevent="searchData(pagination.last_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
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

    import $ from 'jquery'

    $(document).on('focus','.search-input-wrapper input', function(){
        $(this).parent().addClass('focused');
    });

    $(document).on('blur','.search-input-wrapper input', function () {
        let inputValue = $(this).val();
        if ( inputValue == "" ) {
            $(this).removeClass('filled');
            $(this).parents().removeClass('focused');
        } else {
            $(this).addClass('filled');
        }
    });
    import axios from 'axios'
    import Loader from "../components/Loader";
    export default {
        name: "Search",
        data(){
            return{
                isLoading: true,
                user_info : '',
                query_string:'',
                allArticles:'',
                articleSlug : '',
                suggestedArtiles:[],
                allSearchQuery:[],
                regexImg                : /(http:\/\/[^">]+)/img,
                static_image : [],
                pagination:{
                    from: '',
                    to: '',
                    first_page_url: '',
                    last_page: '',
                    last_page_url: '',
                    next_page_url:'',
                    prev_page_url: '',
                    path: '',
                    per_page: 10,
                    total: ''

                },
            }
        },
        components:{
            Loader,
        },
        methods:{
            // searchData(){
            //     let _that = this;
            //     let query_string = _that.query_string;
            //     // console.log(query_string)
            //     // _that.allSearchQuery.push(_that.query_string);
            //     // localStorage.setItem("query_string",_that.query_string);
            //     pageUrl = pageUrl == undefined ? 'article/search/'+query_string+'?page=1' : pageUrl;
            //     axios.get('article/search/'+query_string).then((response)=>{
            //         console.log(response.data)
            //         _that.isLoading= false;
            //         _that.allArticles = response.data.article_list.data;
            //         console.log(_that.allArticles)
            //         _that.pagination  = response.data.article_list;
            //     })
            // },

            searchData(pageUrl){
                let _that = this;
                let query_string = _that.query_string;
                // _that.allSearchQuery.push(_that.query_string);
                // localStorage.setItem("query_string",_that.query_string);

                if (_that.user_info == ''){
                    pageUrl = pageUrl == undefined ? 'article/search/'+query_string+'?page=1' : pageUrl;
                    axios.get(pageUrl).then((response)=>{
                        _that.isLoading= false;
                        _that.allArticles = response.data.article_list.data;
                        _that.pagination  = response.data.article_list;
                        localStorage.setItem("query_string", _that.query_string);
                    })
                } else{
                    pageUrl = pageUrl == undefined ? 'article/search/'+query_string+'?page=1' : pageUrl;
                    axios.get(pageUrl,{
                        params:{
                            isRole : 1
                        }
                    }).then((response)=>{
                        _that.isLoading= false;
                        _that.allArticles = response.data.article_list.data;
                        _that.pagination  = response.data.article_list;
                        localStorage.setItem("query_string", _that.query_string);
                    })
                }
            },
            autoSuggetion(e) {
                let _that = this;
                _that.suggestedArtiles = [];
                if(e.target.value.length>3){
                    setTimeout(()=>{
                        if (this.userInformation == ''){
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
            getStaticMedia()
            {
                this.static_image['category'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
                this.static_image['article'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
                this.static_image['banner'] = axios.defaults.baseURL.replace('api/','')+'static_media/banner.jpg';
            },
        },
        created()
        {
            if (sessionStorage.userInformation) {
                this.user_info = JSON.parse(sessionStorage.userInformation);
            }
            this.query_string = localStorage.getItem("query_string");
            // console.log(this.query_string);
            this.searchData();
            this.getStaticMedia();
        },
    }
</script>

<style scoped>

</style>
