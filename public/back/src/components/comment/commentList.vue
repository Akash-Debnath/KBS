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
                <div class="content-title-wrapper px-15 py-10">
                    <h2 class="content-title text-uppercase m-0">Comment List</h2>
                </div>
                <div class="content-wrapper bg-white">
                    <!-- list top area -->
                    <div class="list-top-area px-15 py-10 d-sm-flex justify-content-between align-items-center">
                        <div class="adding-btn-area d-md-flex align-items-center justify-content-between w-100">
                            <div>

                            </div>
                            <div class="reload-download-expand-area">
                                <ul class="list-inline d-inline-flex align-items-center justify-content-end mb-0 w-100">
                                    <li>
                                        <button class="reload-btn" @click="clearFilter()">
                                            <div class="d-flex jsutify-content-center align-items-center">
                                                <i class="fas fa-sync"></i> <span class="hide-on-responsive">Reload</span>
                                            </div>
                                        </button>
                                    </li>

                                    <li><button class="screen-expand-btn"><i class="fas fa-expand-arrows-alt"></i> <span class="hide-on-responsive">Full Screen</span></button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- list top area end -->
                    <!-- Content Area -->
                    <div class="data-content-area px-15 py-10">
                        <Loading v-if="isLoading===true"></Loading>
                        <!-- Table Data -->
                        <div class="table-responsive" v-if="isLoading===false">
                            <v-app>
                                <v-main>
                                    <v-container class="p-0 position-relative overflow-hidden">
                                        <v-row justify="end">
                                            <v-col md="3" class="customer-search-wrapper">
                                                <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details />
                                            </v-col>
                                        </v-row>
                                        <v-row>
                                            <v-col class="customer-data-table-wrapper">
                                                <v-data-table :headers="headers" :items="articleList" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">

                                                    <template v-slot:item.user.first_name="{item}">
                                                        {{ item.user ? (item.user.first_name +' '+ item.user.last_name) : '' }}
                                                    </template>

                                                    <template v-slot:item.article.en_title="{item}">
                                                        <router-link v-if="item.article" :to="{ name: 'articleDetails', params: { id: item.article.id,slug: item.article.slug }}">
                                                            <span v-if="(item.article.en_title).length<30"> {{ item.article.en_title }}</span>
                                                            <span v-else> {{ (item.article.en_title).substring(0,30)+"...." }}</span>
                                                            <i class="fas fa-eye text-secondary"></i>
                                                        </router-link>
                                                    </template>

                                                    <template v-slot:item.faq.en_title="{item}">
                                                        <router-link v-if="item.faq" :to="{ name: 'faqDetails', params: { id: item.faq.id, slug: item.faq.slug }}">
                                                            <span v-if="(item.faq.en_title).length<30"> {{ item.faq.en_title }}</span>
                                                            <span v-else> {{ (item.faq.en_title).substring(0,30)+"...." }}</span>
                                                            <i class="fas fa-eye text-secondary"></i>
                                                        </router-link>
                                                    </template>


                                                    <template v-slot:item.status="{item}">
                                                        <span>
                                                            <input type="hidden" v-model="item.id">
                                                            <label for="status_inactive" class="col-form-label"><input id="status_inactive" type="radio" v-model="item.status" value="0" @change="changeCommentStatus($event, item.id)"/> Inactive</label>
                                                            <label for="status_active" class="col-form-label"><input id="status_active" type="radio" v-model="item.status" value="1" @change="changeCommentStatus($event, item.id)" /> Active</label>
                                                        </span>
                                                    </template>

                                                    <template v-slot:item.actions="{item}">
                                                        <button  class="btn btn-danger ripple-btn right-side-common-form btn-xs m-1" @click="post_id=item.id, isDeleteCheck=true" v-if="checkPermission('comment-delete')"><i class="fas fa-trash-restore-alt"></i></button>
                                                    </template>

                                                </v-data-table>

                                            </v-col>
                                        </v-row>
                                        <v-row justify="end" class="pagination-wrapper">
                                            <v-col>
                                                <v-pagination :total-visible="7" v-model="pagination.current" :length="pagination.total" @input="onPageChange">
                                                </v-pagination>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-main>
                            </v-app>
                        </div>
                        <!-- Table Data End -->

                    </div>
                    <!-- Content Area End -->
                </div>
            </div>
            <!-- Content Area End -->
        </div>
        <!-- Common Right SideBar -->
        <!--alert message-->
        <div class="action-modal-wraper" v-if="success_message">
            <span>{{ success_message }}</span>
        </div>
        <div class="action-modal-wraper-error" v-if="error_message">
            <span>{{ error_message }}</span>
        </div>

        <div class="right-sidebar-wrapper with-upper-shape fixed-top px-20 pb-30 pb-md-40 pt-70">
            <div class="close-bar d-flex align-items-center justify-content-end">
                <button class="right-side-close-btn ripple-btn-danger" @click="clearAllChecker">
                    <img src="../../assets/img/cancel.svg" alt="cancel">
                </button>
            </div>

            <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isDeleteCheck===true">
                <div class="right-sidebar-content-area px-2">
                    <div class="form-wrapper">
                        <h2 class="section-title text-uppercase mb-20">Delete Comment</h2>
                        <div class="row mt-50 mt-md-80">
                            <div class="col-md-12">
                                <figure class="mx-auto text-center">
                                    <img class="img-fluid mxw-100" src="../../assets/img/delete-big-icon.svg" alt="delete-big">
                                </figure>
                                <p class="text-center"> Confirmation for Deleting Comment</p>

                                <div class="form-group d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-danger text-white rounded-pill ripple-btn px-30 mx-2" @click="commentDelete(post_id)"><i class="fas fa-trash"></i> Confirm</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-30 mx-2" @click="removingRightSideWrapper()"><i class="fas fa-times-circle" ></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Header from "@/layouts/common/Header";
    import Menu from "@/layouts/common/Menu";
    import Loading from "@/components/loader/loading";
    import axios from "axios";
    import $ from "jquery";


    export default {
        name: "CommentList.vue",

        components: {
            Header,
            Menu,
            Loading,
        },
        data() {
            return {
                isArticleStatus     : '',
                isExportCheck       : false,
                isLoading           : false,
                isEditCheck         : false,
                isAddCheck          : false,
                isDeleteCheck       : false,
                isSearchCheck       : false,
                downloadUrl         : 'articles/export/',
                user_permissions : '',
                mappedPermission : '',
                perPage             :2,
                currentPage         :1,
                unstoredArticleID   :'',

                post_id          :'',
                search              :"",
                pagination  :{
                    current         :1,
                    per_page        : 20,
                    total           : ''
                },
                headers: [
                    {
                        text: 'Author',
                        value: 'user.first_name',
                    },
                    {
                        text: 'Post on [ article ]',
                        value: 'article.en_title',
                    },
                    {
                        text: 'Post on [ faq ]',
                        value: 'faq.en_title',
                    },

                    {
                        text: 'Comment',
                        value: 'comment_body',
                    },
                    {
                        text: 'Status',
                        value: 'status',
                    },
                    {
                        text: 'Published Date',
                        value: 'created_at',
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable: false

                    },
                ],

                success_message : '',
                error_message   : '',
            }
        },
        methods: {
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
                        _that.getCommentList();
                        _that.success_message       = "Comment Deleted Successfully";
                        _that.setTimeoutElements();
                        _that.removingRightSideWrapper();
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
                        _that.getCommentList();
                        _that.success_message = "Comment ststus changed";
                        _that.setTimeoutElements();
                    }
                })

            },

            // acl permission
            checkPermission(permissionForCheck)
            {
                if((this.mappedPermission).includes(permissionForCheck) === true) {
                    return true;
                } else {
                    return false;
                }
            },
            // clear serach
            clearFilter()
            {
                this.filter.category_id = "";
                this.filter.status   = "";
                this.filter.en_title = "";
                this.filter.tag      = "";
                this.success_message = "";
                this.error_message   = "";
                this.getCommentList();

            },
            // slider close
            clearAllChecker()
            {
                let _that = this;
                _that.isSearchCheck      = false;
                _that.isAddCheck         = false;
                _that.isEditCheck        = false;
                _that.isDeleteCheck      = false;
            },
            // after crud close
            removingRightSideWrapper()
            {
                this.isAddCheck         = false;
                this.isEditCheck        = false;
                this.isDeleteCheck      = false;
                this.isSearchCheck      = false;

                document.body.classList.remove('open-side-slider');
                $('.right-sidebar-wrapper').toggleClass('right-side-common-form-show');
            },


            getCommentList(pageUrl)
            {
                let _that = this;

                pageUrl = pageUrl == undefined ? 'comments' : pageUrl;

                axios.get(pageUrl+'?page='+this.pagination.current,
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response.data);
                            _that.pagination.current = response.data.comment_list.current_page;
                            _that.pagination.total = response.data.comment_list.last_page;
                            _that.articleList       = response.data.comment_list.data;
                            _that.isLoading         = false;
                            _that.setTimeoutElements();

                        }
                        else{
                            _that.success_message   = "";
                            _that.error_message     = response.data.error;
                        }
                    })
            },
            onPageChange() {
                this.getArticleList();
            },
            setTimeoutElements()
            {
                // setTimeout(() => this.isLoading = false, 3e3);
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
            },
        },
        created() {
            this.isLoading = true;
            this.getCommentList();
            this.user_permissions = JSON.parse(localStorage.getItem("userPermissions"));
            this.mappedPermission = (this.user_permissions ).map(x => x.slug);
            // this.downloadUrl = axios.defaults.baseURL+this.downloadUrl;
        }
    }
</script>

<style scoped>
    .mhv-100 {
        min-height: 50vh;
    }
</style>
