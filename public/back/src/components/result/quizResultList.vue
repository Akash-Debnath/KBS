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
                    <h2 class="content-title text-uppercase m-0">Quiz Result List</h2>
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
                                                <v-data-table :headers="headers" :items="resultList" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">
                                                    <template v-slot:item.passFail="{item}">
                                                        <router-link :to="{ name: 'quizResultDetails', params: { quizId: item.quiz_id, status:1 }}" class=" text-green" tag="button" >{{item.pass}}</router-link>
                                                        <span>/</span>
                                                        <router-link :to="{ name: 'quizResultDetails', params: { quizId: item.quiz_id, status:0 }}" class=" text-red" tag="button" >{{item.fail}}</router-link>
                                                        <!-- <span>{{item.pass}} / {{item.fail}}</span> -->
                                                    </template>

                                                    <!-- <template v-slot:item.actions="{item}">
                                                        <router-link :to="{ name: 'userQuizList', params: { userId: item.user_id }}" class="btn btn-info btn-xs m-1" tag="button" ><i class="fas fa-eye text-white"></i> View Perform Details</router-link>
                                                    </template> -->

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
    </div>
</template>

<script>
    import Header from "@/layouts/common/Header";
    import Menu from "@/layouts/common/Menu";
    import Loading from "@/components/loader/loading";
    import axios from "axios";
    import $ from "jquery";


    export default {
        name: "quizResultList.vue",

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
                resultList    : '',
                perPage             :2,
                currentPage         :1,
                unstoredArticleID   :'',

                post_id          :'',
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
                        text: 'Quiz Title',
                        value: 'quiz.name',
                    },
                    {
                        text: 'Total Attempt',
                        value: 'attempt',
                    },
        
                    
                    {
                        text: 'Pass / Fail',
                        value: 'passFail',
                        sortable: false

                    },
                ],

                success_message : '',
                error_message   : '',
            }
        },
        methods: {
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


            getResultList(pageUrl)
            {
                let _that = this;

                pageUrl = pageUrl == undefined ? 'quiz-results' : pageUrl;

                axios.get(pageUrl+'?page='+this.pagination.current,
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response.data);
                            _that.pagination.current = response.data.result_list.current_page;
                            _that.pagination.total   = response.data.result_list.last_page;
                            _that.resultList   = response.data.result_list.data;
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
                this.getResultList();
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
            this.getResultList();
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
