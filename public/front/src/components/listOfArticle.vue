<template>
    <div v-if="selectedCategory !=''">
        <div class="row article-list-items" v-for="(has_article) in selectedCategory" :key="has_article.id">
            <div class="col-lg-12 mb-15">
                <router-link class="article-item-list-box d-sm-flex position-relative overflow-hidden" :to="{ name: 'ArticleDetail', params: { articleID: has_article.id,articleSlug: has_article.slug }}">
                    <div class="article-list-image mb-20 mb-sm-0" v-if="has_article.contents == ''">
                        <img :src="static_image['article']" alt="no image" class="img-fluid">
                    </div>

                    <div v-for="a_content in has_article.contents" :key="a_content.id">
                        <div class="article-list-image mb-20 mb-sm-0"  v-if="a_content.en_body.match(regexImg)">
                            <img :src="((a_content.en_body).match(regexImg) ? (a_content.en_body).match(regexImg)[0]: static_image['article'] )" alt="no image" class="img-fluid">
                        </div>
                        <div class="article-list-image mb-20 mb-sm-0" v-else>
                            <img :src="static_image['article']" alt="no image" class="img-fluid">
                        </div>
                    </div>
                    <div class="article-content-list-box pl-0 pl-sm-10">
                        <h3 class="article-list-title mb-0 pb-2 font-20">
                            <span v-if="(has_article.en_title).length<70"> {{ has_article.en_title }}</span>
                            <span v-else> {{ (has_article.en_title).substring(0,70)+"..." }}</span>
                        </h3>
                        <small class="font-8 mb-0 d-block pb-2">Published at: {{has_article.created_at}}</small>
                        <p class="font-14 mb-0" v-if="(has_article.en_short_summary).length<200">{{has_article.en_short_summary}}</p>
                        <p class="font-14 mb-0" v-else>{{(has_article.en_short_summary).substring(0,200)+"..."}}</p>
                    </div>
                </router-link>
            </div>
        </div>

        <div class="item-list text-left">
            <div v-if="selectedCategory">
                <div v-for="(has_article, index) in selectedCategory" :key="has_article.id">
                    <div v-if="index === 0">
                        <div v-if="pagination.total > pagination.per_page" class="col-md-offset-4">
                            <ul class="pagination">
                                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item mx-1">
                                    <a @click.prevent="changeCategoryArticlePage(has_article.category.slug,pagination.first_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                                </li>
                                <li :class="[{disabled:!pagination.prev_page_url}]" class="page-item mx-1">
                                    <a @click.prevent="changeCategoryArticlePage(has_article.category.slug,pagination.prev_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                                </li>
                                <li v-for="n in pagination.last_page" class="page-item mx-1" :key="n">
                                    <a @click.prevent="changeCategoryArticlePage(has_article.category.slug,'article/category/'+has_article.category.slug+'?page='+n)" class="px-3 bg-primary text-white py-2 rounded-sm" href="#">{{ n }}</a>
                                </li>

                                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item mx-1">
                                    <a @click.prevent="changeCategoryArticlePage(has_article.category.slug,pagination.next_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                </li>
                                <li :class="[{disabled:!pagination.next_page_url}]" class="page-item mx-1">
                                    <a @click.prevent="changeCategoryArticlePage(has_article.category.slug,pagination.last_page_url)" href="#" class="px-3 bg-primary text-white py-2 rounded-sm"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>No Article Found!</div>
</template>

<script>
    import axios from 'axios'
    export default {
        name: "listOfArticle",

        props: ['selected_category'],

        data(){
            return{
                selectedCategory : '',
                static_image : [],
                regexImg : /(http:\/\/[^">]+)/img,
                pagination:{
                    from: '',
                    to: '',
                    first_page_url: '',
                    last_page: '',
                    last_page_url: '',
                    next_page_url:'',
                    prev_page_url: '',
                    path: '',
                    per_page: 5,
                    total: ''
                },
            }
        },

        methods:{
            getStaticMedia()
            {
                this.static_image['category'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
                this.static_image['article'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
                this.static_image['banner'] = axios.defaults.baseURL.replace('api/','')+'static_media/banner.jpg';
            },
            changeCategoryArticlePage(categoryID,pageUrl){
                window.scrollTo(0, 0);
                let _that = this;
                pageUrl = pageUrl == undefined ? 'article/category/'+categoryID+'?page=1' : pageUrl;
                axios.get(pageUrl)
                    .then(function (response) {
                        _that.selectedCategory = response.data.article_list.data;
                        _that.pagination = response.data.article_list;
                        _that.$router.push('/category-list/'+_that.categoryID)
                    })
            },
        },

        created() {
            this.selectedCategory = this.selected_category;
            this.getStaticMedia();
            // console.log(this.selectedCategory)
        }
    }
</script>

<style scoped>

</style>