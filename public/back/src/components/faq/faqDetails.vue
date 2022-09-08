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
                    <h2 class="content-title text-uppercase m-0">FAQ Details</h2>
                    <router-link :to="{ name: 'faqList'}" class="btn common-gradient-btn ripple-btn btn-xs m-1 px-15 py-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </router-link>
                </div>
                <Loading v-if="isLoading === true"></Loading>

                <div class="content-wrapper bg-white" v-if="isLoading === false" >
                    <div class="data-content-area px-15 py-10" v-if="faqDetails">
                        <h3 class="font-weight-bold">Faq Details Page</h3>
                        <!-- <h3 class="">{{faqDetails.en_title}}</h3> -->
                        <div>
                            <small class="font-16"><strong>Category: </strong>{{faqDetails.category ? faqDetails.category.name : 'N/A'}}</small>
                        </div>
                        <p class="font-16"><strong>Tags: </strong>{{faqDetails.tag}}</p>
                        <div class="ta-wrapper d-flex align-items-center py-10 my-25">
                            <div class="avatar mr-10">
                                <img class="img-fluid" src="@/assets/image.png" style="height: 50px; width: 50px" alt="avatar">
                            </div>
                            <div class="tc-wrapper">
                                <h5 v-cloak class="my-0 pb-1" v-if="faqDetails.user">{{faqDetails.user.first_name}} {{faqDetails.user.last_name}}</h5>
                                <p class="mb-0">Post on: {{faqDetails.created_at}}</p>
                            </div>
                        </div>
                        <div class="col-md-12 article-lang-switcher" v-if="faqDetails.bn_title != 'n/a'">
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
                                <div class="tab-pane fade active show" id="tabEnglish" v-if="faqDetails">
                                    <div class="ta-content-wrapper">
                                        <h3 class="pb-10">{{faqDetails.en_title}}</h3>
                                    </div>
                                    <div class="ta-content-wrapper" v-for="faqContent in faqDetails.contents">
                                        <div v-if="faqContent.en_body != 'n/a' && faqContent.role_id.includes(userInformation.roles[0].id)" v-html="faqContent.en_body"></div>
                                    </div>
                                </div>


                                <!--bangla-->
                                <div class="tab-pane fade" id="tabBangla" v-if="faqDetails.contents.bn_title != 'n/a'">
                                    <div class="ta-content-wrapper">
                                        <h3 class="pb-10">{{faqDetails.bn_title}}</h3>
                                    </div>
                                    <div class="ta-content-wrapper" v-for="faqContent in faqDetails.contents">
                                        <div v-if="faqContent.bn_body != 'n/a' && faqContent.role_id.includes(userInformation.roles[0].id)" v-html="faqContent.bn_body"></div>
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

                        <div class="col-md-12" v-if="faqDetails.commentable_status">
                            <h5 class="pb-10">Discussions</h5>
                        </div>

                        <div class="col-md-12" v-if="faqDetails.commentable_status">
                            <!-- Comment Section Start -->
                            <div class="comment-box">
                                <div class="comment-input-box d-flex">
                                    <div class="featured-image avatar mr-10">
                                        <img class="img-fluid rounded-circle" src="../../assets/img/avatar.png" style="height: 50px; width: 50px" alt="avatar">
                                    </div>
                                    <div class="comment-input w-100">
                                        <input type="hidden" v-model="faqDetails.id">
                                        <input type="hidden" v-model="userInformation.id">
                                        <textarea id="commentBody" v-model="comment_body" placeholder="Write your comment..." class="form-control px-10 py-10"></textarea>
<!--                                        <span id="commentBodyError" class="text-danger small ml-3"></span>-->
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
                                    <div class="reply-text position-relative w-100 px-10 py-10">
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

                    <!-- Content Area End -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from "@/components/loader/loading";
    import Header from "@/layouts/common/Header";
    import Menu from "@/layouts/common/Menu";
    import axios from "axios";
    import $ from 'jquery'

    export default {
        name: "faqDetails.vue",
        components: {
            Header,
            Menu,
            Loading
        },

        data() {
            return {
                isLoading : '',
                category_parent_id : '',
                category_name   : '',
                success_message : '',
                error_message   : '',
                token           : '',
                faqDetails      : '',
                userInfo        : '',
                userInformation : '',
                filter : {
                    isAdmin : 1
                },
                comment_body : '',
                comments : '',
                status : 0,
                faqID : '',
                faqSlug : '',
                user_permissions :'',
                mappedPermission : '',
                history_details : [],
            }
        },

        methods: {
            setTimeoutElements()
            {
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
            },
            changeCommentStatus(event, commentID){
                let _that = this;
                let optionText = event.target.value;
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
                        _that.getCommentListWithFaq()
                        _that.success_message = "Comment ststus changed";
                        _that.setTimeoutElements();
                    }
                })

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
                        _that.getCommentListWithFaq();
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
            commentUpdate(data,btnEdit,paraID,textareaID,btnID){
                let _that = this;
                $("#"+btnEdit).css({display : 'inline'})
                $("#"+paraID).css({display : 'inline'})
                $("#"+textareaID).css({display : 'none'})
                $("#"+btnID).css({display : 'none'})
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
                        _that.getCommentListWithFaq();
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
            addComment(){
                let _that = this;

                let formData = new FormData();
                formData.append('post_id', _that.faqDetails.id);
                formData.append('user_id', _that.userInformation.id);
                formData.append('comment_body', _that.comment_body);
                formData.append('status', _that.status);

                axios.post('comments',formData,{
                    headers: {
                        'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                    }
                }).then(function (response) {
                    console.log(response.data.status_code);
                    if (response.data.status_code == 200){
                        $('#commentBodyError').html("");
                        _that.getCommentListWithFaq()
                        _that.success_message = "Comment added successfully";
                        _that.comment_body='';
                        _that.setTimeoutElements();
                    }else {
                        _that.success_message = "";
                        _that.error_messages   = response.data.errors;
                        _that.showServerError(response.data.errors);
                    }
                })
            },
            showServerError(errors){

                console.log(errors)

                $('#commentBodyError').html("");

                // $('#commentBody').css({'border-color': '#ced4da'});

                errors.forEach(val => {
                    if (val.includes("comment body")==true){
                        $('#commentBodyError').html(val)
                        // $('#commentBody').css({'border-color': '#FF7B88'});
                    }
                })
            },
            checkPermission(permissionForCheck)
            {
                if((this.mappedPermission).includes(permissionForCheck) === true) {
                    return true;
                } else {
                    return false;
                }
            },
            getCommentListWithFaq(){
                let _that = this;

                axios.get('post-comments/'+_that.faqID,{
                    headers: {
                        'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                    }
                }).then(function (res) {
                    _that.comments = res.data.comments;
                    console.log(res.data.comments)
                })
            },
            getFaqDetails() {
                let _that = this;
                let faqID = this.faqID;

                let apiUrl = "faqs/";

                axios.get(apiUrl+faqID,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            console.log(response.data.faq_info);
                            _that.isLoading = false;
                            _that.faqDetails = response.data.faq_info;
                        } else {
                            _that.success_message = "";
                            _that.error_message = response.data.error;
                        }
                    })
            },
        },

        created() {
            this.isLoading = true;
            this.faqID = this.$route.params.id;
            this.faqSlug = this.$route.params.slug;
            this.getFaqDetails();
            this.getCommentListWithFaq()
            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            this.user_permissions = JSON.parse(localStorage.getItem("userPermissions"));
            this.mappedPermission = (this.user_permissions ).map(x => x.slug);
        }
    }
</script>

<style scoped>
    textarea{
        resize: none;
    }
</style>
