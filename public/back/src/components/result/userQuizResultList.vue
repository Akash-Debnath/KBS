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
                    <h2 class="content-title text-uppercase m-0">User Quiz Result List</h2>
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
                                                <v-data-table :headers="headers" :items="userQuizResultList" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">
                                                  
                                                    <template v-slot:item.actions="{item}">
                                                        <router-link :to="{ name: 'resultDetails', params: { userId: item.user_id, quizId:item.quiz_id,attempt:item.attempt }}" class="btn btn-info btn-xs m-1" tag="button" ><i class="fas fa-eye text-white"></i> View Result</router-link>

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
        name: "userQuizResultList.vue",

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
                user_permissions    : '',
                mappedPermission    : '',
                userQuizResultList    : '',
                perPage             :2,
                currentPage         :1,
                unstoredArticleID   :'',
                post_id          :'',
                userID          :'',
                quizID          :'',
                search           :"",
                pagination  :{
                    current         :1,
                    per_page        : 20,
                    total           : ''
                },
                headers: [

                    {
                        text: 'Quiz ID',
                        value: 'quiz_id',
                    },
                    {
                        text: 'Quiz Name',
                        value: 'quiz.name',
                    },

                    {
                        text: 'Attempt',
                        value: 'attempt',
                    },
                    {
                        text: 'Performed Date',
                        value: 'created_at',
                    },
                    {
                        text: 'Result',
                        value: 'result',
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
            quizResultDelete(commentID){
                let _that = this;
                axios.delete('notifications/delete',
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
                        _that.getNotificationList();
                        _that.success_message       = "Notification Deleted Successfully";
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
        
            // acl permission
            checkPermission(permissionForCheck)
            {
                if((this.mappedPermission).includes(permissionForCheck) === true) {
                    return true;
                } else {
                    return false;
                }
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


            getUserQuizResultList(pageUrl)
            {
                let _that = this;

                pageUrl = pageUrl == undefined ? 'user-quiz-result-list' : pageUrl;

                axios.get(pageUrl,
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        },
                        params: {
                            userId: _that.userID,
                            quizId:_that.quizID,
                            page: _that.pagination.current
                        }
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response.data);
                            _that.pagination.current = response.data.user_quiz_result_list.current_page;
                            _that.pagination.total   = response.data.user_quiz_result_list.last_page;
                            _that.userQuizResultList   = response.data.user_quiz_result_list.data;
                            _that.isLoading          = false;
                            _that.setTimeoutElements();

                        }
                        else{
                            _that.success_message   = "";
                            _that.error_message     = response.data.error;
                        }
                    })
            },
            onPageChange() {
                this.getUserQuizResultList();
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
            this.userID = this.$route.params.userId;
            this.quizID = this.$route.params.quizId;
            console.log( this.userID, this.quizID);
            this.getUserQuizResultList();
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
