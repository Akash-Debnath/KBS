<template>
    <div class="main-wrapper d-flex">
        <!-- sidebar -->
        <Menu></Menu>
        <!-- sidebar end -->

        <div class="main-content-wrapper w-100 position-relative overflow-auto bg-white" >
            <!-- Topbar -->
            <Header></Header>

            <!-- Topbar End -->

            <!-- Content Area -->
            <div class="content-area">
                <div class="content-title-wrapper px-15 py-10 d-md-flex justify-content-between align-items-center">
                    <h2 class="content-title text-uppercase m-0">Article Details</h2>
                    <div class="button-group">
                        <div class="btn common-gradient-btn ripple-btn btn-xs m-1 px-15 py-2" data-toggle="modal" data-target="#searchReplaceModal">
                            <i class="fas fa-search"></i> Search & Replace
                        </div>
                        <router-link :to="{ name: 'articleList'}" class="btn common-gradient-btn ripple-btn btn-xs m-1 px-15 py-2">
                            <i class="fas fa-arrow-left"></i> Back
                        </router-link>
                    </div>
                </div>

                <!-- search & replace modal -->
                <div class="modal fade" id="searchReplaceModal" tabindex="-1" aria-labelledby="searchReplaceModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="searchReplaceModalLabel">Search & Replace</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <search-replace v-if="aArticle" :post_slug="aArticle.slug" :post_id="aArticle.id" @keyword-search="findKeyword" @replace="getReplacedData"></search-replace>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- search & replace modal end -->
                <div class="action-modal-wraper" v-if="success_message">
                    <span>{{ success_message }}</span>
                </div>
                <div class="action-modal-wraper-error" v-if="error_message">
                    <span>{{ error_message }}</span>
                </div>


                <Loading v-if="isLoading===true"></Loading>


                <div class="content-wrapper bg-white" v-if="isLoading == false">
                    <div class="data-content-area px-15 py-10" v-if="aArticle">
                        <h3 class="font-weight-bold">Articles Details Page</h3>
                        <div>
                            <small class="font-16"><strong>Category: </strong>{{aArticle.category ? aArticle.category.name : 'N/A'}}</small>
                        </div>
                        <p class="font-16" :id="'article_keyword_tag_'+aArticle.id"><strong>Tags: </strong>{{aArticle.tag}}</p>

                        <div class="ta-wrapper d-flex align-items-center py-10 my-15">
                            <div class="avatar mr-10">
                                <img class="img-fluid" src="../../assets/img/avatar.png" style="height: 50px; width: 50px" alt="avatar">
                            </div>
                            <div class="tc-wrapper">
                                <h5 v-if="aArticle.user" v-cloak class="my-0 pb-1">{{aArticle.user.first_name}} {{aArticle.user.last_name}}</h5>
                                <p class="mb-0">Post on: {{aArticle.created_at}}</p>
                            </div>
                        </div>


                        <div class="col-md-12 article-lang-switcher" v-if="aArticle.bn_title != 'n/a'">
                            <ul class="nav nav-tabs" id="myTab">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabEnglish">English</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabBangla">Bangla</a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-12">
                            <div class="tab-content pt-3" id="myTabContent">
                                <div class="tab-pane fade active show" id="tabEnglish" v-if="aArticle">
                                    <div class="ta-content-wrapper">
                                        <h3 :id="'article_keyword_en_title_'+aArticle.id" class="pb-10">{{aArticle.en_title}}</h3>
                                        <p :id="'article_keyword_en_short_summary_'+aArticle.id" class="pb-10" v-if="aArticle.en_short_summary != ''">{{aArticle.en_short_summary}}</p>
                                    </div>
                                    <div class="ta-content-wrapper pb-10" v-for="a_article_content in aArticle.contents">
                                        <div :id="'article_keyword_en_body_'+a_article_content.id" v-if="a_article_content.en_body != 'n/a' && a_article_content.role_id.includes(userInformation.roles[0].id)" v-html="a_article_content.en_body"></div>
                                    </div>
                                </div>


                                <!--bangla-->
                                <div class="tab-pane fade" id="tabBangla" v-if="aArticle.bn_title != 'n/a'">
                                    <div class="ta-content-wrapper">
                                        <h3 :id="'article_keyword_bn_title_'+aArticle.id" class="pb-10">{{aArticle.bn_title}}</h3>
                                        <p :id="'article_keyword_bn_short_summary_'+aArticle.id" class="pb-10 article_keyword" v-if="aArticle.bn_short_summary != ''">{{aArticle.bn_short_summary}}</p>
                                    </div>
                                    <div class="ta-content-wrapper pb-10" v-for="a_article_content in aArticle.contents">
                                        <div :id="'article_keyword_bn_body_'+a_article_content.id" v-if="a_article_content.bn_body != 'n/a' && a_article_content.role_id.includes(userInformation.roles[0].id)" v-html="a_article_content.bn_body"></div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <br/>

                        <div class="col-md-12" v-if="aArticle.media.length > 0">
                            <h5 class="mb-0 font-weight-bold pl-2 pb-3">Refferences</h5>
                            <ul class="pl-25 pb-2">
                                <li v-for="a_file in aArticle.media" :key="a_file.id">
                                    <a :href="a_file.url">{{a_file.url | formatFileName }}</a>
                                </li>
                            </ul>
                        </div>

                        <!-- Content Area End -->

                        <div class="action-modal-wraper" v-if="success_message">
                            <span>{{ success_message }}</span>
                        </div>
                        <div class="action-modal-wraper-error" v-if="error_message">
                            <span>{{ error_message }}</span>
                        </div>

                        <div class="col-md-12" v-if="aArticle.commentable_status">
                            <h5 class="pb-10">Discussions</h5>
                        </div>

                        <div class="col-md-12" v-if="aArticle.commentable_status">
                            <!-- Comment Section Start -->
                            <div class="comment-box">
                                <div class="comment-input-box d-flex">
                                    <div class="featured-image avatar mr-10">
                                        <img class="img-fluid rounded-circle" src="../../assets/img/avatar.png" style="height: 50px; width: 50px" alt="avatar">
                                    </div>
                                    <div class="comment-input w-100">
                                        <input type="hidden" v-model="aArticle.id">
                                        <input type="hidden" v-model="userInformation.id">
                                        <textarea v-model="comment_body" placeholder="Write your comment..." class="form-control px-10 py-10"></textarea>
                                        <input hidden type="radio" checked value="0" v-model="status">
                                    </div>
                                </div>
                                <div class="replied-btn-wrapper pt-10 text-right">
                                    <button class="btn btn-primary common-gradient-btn px-25 py-2 rounded-pill" @click="addComment()">Add</button>
                                </div>
                            </div>
                            <!-- Comment Section End -->

                            <!-- Comment Read Start -->
                            <div class="reply-box mt-30" v-for="a_comment in comments" :key="a_comment.id">
                                <div class="reply-input-box d-flex" v-if="">
                                    <div class="featured-image avatar mr-10">
                                        <img class="img-fluid rounded-circle" src="../../assets/img/avatar.png" style="height: 50px; width: 50px" alt="avatar">
                                    </div>
                                    <div class="reply-text position-relative w-100 px-10 py-3">
                                        <!-- action button start -->
                                        <div class="action-button-wrapper">
                                            <button :id="'comment_edit_'+a_comment.id" class="btn btn-success ripple-btn m-1" @click="commentEdit('comment_edit_'+a_comment.id,'comment_box_'+a_comment.id,'comment_edit_box_'+a_comment.id,'comment_update_'+a_comment.id)"  v-if="checkPermission('comment-edit') && a_comment.user.id ==userInformation.id"><i class="fas fa-pen"></i></button>

                                            <button  class="btn btn-danger ripple-btn m-1" @click="commentDelete(a_comment.id)" v-if="checkPermission('comment-delete')"><i class="fas fa-trash-restore-alt"></i></button>
                                        </div>
                                        <!-- action button end -->
                                        <!-- reply content box start -->
                                        <div class="reply-content-box-wrap">
                                          <h6 class="font-weight-bold">{{a_comment.user.first_name ? a_comment.user.first_name+' '+ a_comment.user.last_name : a_comment.user_id}}</h6>
                                          <div>
                                              <small><strong>Posted on:</strong> {{a_comment.created_at}}</small>
                                          </div>
                                          <div class="reply-status-box">
                                              <small><strong>Status : </strong>
                                                  <input type="hidden" v-model="a_comment.id">
                                                  <label class="col-form-label py-1 mr-2" for="status_inactive"><input class="mr-1" id="status_inactive" type="radio" v-model="a_comment.status" value="0" @change="changeCommentStatus($event, a_comment.id)"/> Inactive</label>
                                                  <label class="col-form-label py-1" for="status_active"><input class="mr-1" id="status_active" type="radio" v-model="a_comment.status" value="1" @change="changeCommentStatus($event, a_comment.id)"/> Active</label>
                                              </small>
                                          </div>

                                          <div class="body-content-box pt-1 mt-1">
                                              <p :id="'comment_box_'+a_comment.id" style="display: block">{{a_comment.comment_body}}</p>

                                              <textarea style="display: none" :id="'comment_edit_box_'+a_comment.id" v-model="a_comment.comment_body" placeholder="Write your comment..." class="form-control py-10"></textarea>

                                              <button style="display: none" :id="'comment_update_'+a_comment.id" @click="commentUpdate(a_comment,'comment_edit_'+a_comment.id,'comment_box_'+a_comment.id,'comment_edit_box_'+a_comment.id,'comment_update_'+a_comment.id)" class="btn-primary btn common-gradient-btn py-1 px-25 rounded-pill m-2">Update</button>
                                          </div>
                                        </div>
                                        <!-- reply content box end -->
                                    </div>
                                </div>
                            </div>
                            <!--Comment Read  End-->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content Area End -->
        </div>

        <!-- Common Right SideBar End -->
    </div>
</template>

<script>
    import Header from "@/layouts/common/Header";
    import Menu from "@/layouts/common/Menu";
    import SearchReplace from "../replace/SearchReplace";
    import Loading from "@/components/loader/loading";
    import $ from 'jquery'
    import axios from "axios";

    export default {
        name: "articleDetails.vue",
        components: {
            Header,
            Menu,
            SearchReplace,
            Loading
        },
        filters:{
            formatFileName(fileName){
                let res;
                res = fileName.split('/')
                return res[res.length - 1];;
            }
        },
        data(){

            return{
                isLoading : true,

                isCommentEdit : false,

                articleID: '',
                articleSlug: '',
                aArticle: '',
                articleCounter: [],
                allCategoryArticle: '',
                categoryHasArticle: [],
                allArticle: '',
                query_string: '',
                roleAccess : [],
                userInformation : '',
                comment_body : '',
                status : 0,
                success_message : '',
                error_message   : '',
                comments : '',
                user_permissions :'',
                mappedPermission : ''
            }

        },

        methods: {
            findKeyword(keyword){
                // this.articleSearch(this.articleSlug);
                this.color_word_tag('article_keyword_tag_'+this.aArticle.id,keyword, 'hotpink')
                this.color_word_en_title('article_keyword_en_title_'+this.aArticle.id,keyword, 'hotpink')
                this.color_word_bn_title('article_keyword_bn_title_'+this.aArticle.id,keyword, 'hotpink')
                this.color_word_en_short_summary('article_keyword_en_short_summary_'+this.aArticle.id,keyword, 'hotpink')
                this.color_word_bn_short_summary('article_keyword_bn_short_summary_'+this.aArticle.id,keyword, 'hotpink')
                this.color_word_en_body('article_keyword_en_body_',keyword, 'hotpink')
                this.color_word_bn_body('article_keyword_bn_body_',keyword, 'hotpink')
            },

            color_word_bn_body(text_id,word, color){
                let nodes = $('[id^='+text_id+']');
                // let rex = /(<([^>]+)>)/ig;
                let contents = [];

                for(let i=0;i<nodes.length; i++){
                    // .replace(rex , "")

                    contents[i] =$('#'+ nodes[i].id).html();
                    // console.log(contents[i]);
                    if (contents[i].includes(word)){

                        let tags = contents[i].split(' ');
                        // console.log(contents[i])
                        tags = tags.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                        contents[i] = tags.join(' ');
                        console.log(contents[i])
                        console.log('<br/>')
                        $('#'+ nodes[i].id).html(contents[i])

                    }

                }

                // $('#' + text_id).html(new_words);
                // console.log(new_words)

            },

            color_word_en_body(text_id,word, color){
                let nodes = $('[id^='+text_id+']');
                // let rex = /(<([^>]+)>)/ig;
                let contents = [];

                for(let i=0;i<nodes.length; i++){
                    // .replace(rex , "")

                    contents[i] =$('#'+ nodes[i].id).html();
                    // console.log(contents[i]);
                    if (contents[i].includes(word)){

                        let tags = contents[i].split(' ');
                        // console.log(contents[i])
                        tags = tags.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                        contents[i] = tags.join(' ');
                        console.log(contents[i])
                        console.log('<br/>')
                        $('#'+ nodes[i].id).html(contents[i])

                    }

                }

                // $('#' + text_id).html(new_words);
                // console.log(new_words)

            },

            color_word_en_title(text_id,word, color){
                let words = $("#" + text_id).text().split(' ');
                words = words.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                let new_words = words.join(' ');
                $('#' + text_id).html(new_words);
            },
            color_word_bn_title(text_id,word, color){
                let words = $("#" + text_id).text().split(' ');
                words = words.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                let new_words = words.join(' ');
                $('#' + text_id).html(new_words);
            },

            color_word_en_short_summary(text_id,word, color){
                let words = $("#" + text_id).text().split(' ');
                words = words.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                let new_words = words.join(' ');
                $('#' + text_id).html(new_words);
            },

            color_word_bn_short_summary(text_id,word, color){
                let words = $("#" + text_id).text().split(' ');
                words = words.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                let new_words = words.join(' ');
                $('#' + text_id).html(new_words);
            },

            color_word_tag(text_id,word, color) {
                let words = $("#" + text_id).text().split(' ');

                for(let i =0 ; i< words.length ; i++){
                    if (words[i].includes(',')){
                        let tags = words[i].split(',');
                        tags = tags.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                        words[i] = tags.join(',');
                    }else{
                        words = words.map(function(item) { return item == word ? "<span style='color: " + color + "'>" + word + '</span>' : item });
                    }

                }
                let new_words = words.join(' ');
                $('#' + text_id).html(new_words);
            },

            getReplacedData(status,data){
                console.log(status);

                if (status=='search field empty!'){
                    this.error_message = status;
                    this.setTimeoutElements();
                }
                if (status=='replace field empty!'){
                    this.error_message = status;
                    this.setTimeoutElements();
                }
                if (status=='word replaced!'){
                    this.success_message = status;
                    this.aArticle = data;
                    this.setTimeoutElements();
                }
                if (status=='Not found!'){
                    this.error_message = status;
                    this.setTimeoutElements();
                }
                this.articleSearch(this.articleSlug);
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
                            'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response);
                    if (response.data.status_code === 200){
                        $("#"+btnEdit).css({display : 'inline'})
                        $("#"+paraID).css({display : 'inline'})
                        $("#"+textareaID).css({display : 'none'})
                        $("#"+btnID).css({display : 'none'})
                        _that.getCommentListWithArticle();
                        _that.success_message       = "Comment Updated Successfully";
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
            commentDelete(commentID){
                let _that = this;
                axios.delete('comments/delete',
                    {
                        data    : {
                            id              : commentID
                        },
                        headers : {
                            'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
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
            checkPermission(permissionForCheck)
            {
                if((this.mappedPermission).includes(permissionForCheck) === true) {
                    return true;
                } else {
                    return false;
                }
            },
            changeCommentStatus(event, commentID){
                let _that = this;
                let optionText = event.target.value;
                console.log(commentID);
                let formData = new FormData();
                formData.append('id', commentID);
                formData.append('status', optionText);
                axios.post('comment-status',formData,{
                    headers: {
                        'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                    }
                }).then(function (res) {
                    console.log(res.data.status_code);
                    if (res.data.status_code == 200){
                        _that.getCommentListWithArticle()
                        _that.success_message = "Comment ststus changed";
                        _that.setTimeoutElements();
                    }
                })

            },
            setTimeoutElements()
            {
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
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
                        'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                    }
                }).then(function (res) {
                    console.log(res.data.status_code);
                    if (res.data.status_code == 200){
                        _that.getCommentListWithArticle()
                        _that.success_message = "Comment added successfully";
                        _that.comment_body='';
                        _that.setTimeoutElements();
                    }
                })
            },

            getCommentListWithArticle(){
                let _that = this;

                axios.get('post-comments/'+_that.articleID,{
                    params:{
                      isAdmin : 1
                    },
                    headers: {
                        'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                    }
                }).then(function (res) {
                    _that.comments = res.data.comments;
                    console.log(res.data.comments)
                })
            },

            articleSearch(v){
                let _that = this;
                console.log(v);
                let articleSlug = v;
                axios.get('article-details/'+articleSlug)
                    .then(function (response) {
                        _that.isLoading = false;
                        _that.aArticle = response.data.article_info;
                        console.log(_that.aArticle);
                        // location.reload()
                    })
            },

            searchData(){
                let _that = this;
                _that.$router.push({ name: 'Search', params: { query_string: _that.query_string } });
            },

            getRecentArticleList(){
                let _that = this;
                axios.get('article-list')
                    .then(function (response) {
                        _that.allArticle = response.data.article_list;
                    })
            },

            getCategoryArticleList()
            {
                let _that =this;
// let articleCounter = 0;
                axios.get('category-article-list', { cache: false })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            _that.allCategoryArticle = response.data.category_list;
                            _that.allCategoryArticle.forEach(val =>{
                                if (val.article.length!=0){
                                    _that.categoryHasArticle.push(val);
                                }
                            })
                        }
                    })
            },

        },

        created() {
            this.isLoading = true;
            this.articleID = this.$route.params.id;
            this.articleSlug = this.$route.params.slug;
            this.articleSearch(this.articleSlug);
            this.getCategoryArticleList();
            this.getRecentArticleList();
            this.getCommentListWithArticle()
            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            this.user_permissions = JSON.parse(localStorage.getItem("userPermissions"));
            this.mappedPermission = (this.user_permissions ).map(x => x.slug);
        }
    }

</script>

<style scoped>

</style>
