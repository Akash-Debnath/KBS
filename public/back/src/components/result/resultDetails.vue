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
                    <h2 class="content-title text-uppercase m-0">Details Result</h2>
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
                        <div class="content-box" v-if="isLoading===false">
                            <div class="d-flex p-3">
                                <div class="col-md-6">
                                    <div>
                                         <strong>Quiz Title: </strong>{{resultDetails[0].quiz.name}}
                                    </div>
                                    <div>
                                         <strong>Performed Date: </strong>{{resultDetails[0].created_at}}
                                    </div>
                                    
                                     
                                </div>
                                <div class="col-md-6">
                                    <strong>Total marks </strong>: {{resultDetails[0].quiz.total_marks}}
                                    <div>
                                         <strong>Obtained marks: </strong>{{(resultDetails[0].result)}}
                                    </div>
                                </div>
                            </div>

                            <div class=" p-3 ">
                                <ul class="result-items-wrapper list-inline list-unstyled">
                                    <!-- {{resultDetails}} -->
                                    <li v-for="(item, index) in resultDetails" :key="item.id">
                                        <div class="q-box">
                                            <label class=""><b><span>{{index + 1}}.</span> {{item.question.f_label}}</b> </label>
                                            <div v-if="item.question.f_option_value">
                                                <ol class="ml-30" type="A">
                                                    <li v-for="option in item.question.f_option_value.split(',')" :key="option">
                                                        <label v-if="option==item.answer">
                                                            <span v-if="item.answer == item.question.f_default_value" class="text-success">{{option}}</span>  
                                                            <span v-else class="text-danger"> {{option}}</span>  
                                                        </label>
                                                        <label v-else>  {{option}}</label>
                                                    </li>
                                                </ol>
                                            </div>
                                            <div v-else>
                                                <label >
                                                    Your Answer:
                                                    <span v-if="item.answer == item.question.f_default_value" class="text-success"> {{item.answer}}</span>
                                                    <span v-else class="text-danger"> {{item.answer}}</span>
                                                </label>    
                                            </div>
                                            <p class="mb-0"> <b>Correct Answer: </b>{{item.question.f_default_value}} </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            
                            
                           
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
        name: "resultDetails.vue",

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
                resultDetails       : '',
                perPage             :2,
                currentPage         :1,
                unstoredArticleID   :'',
                post_id          :'',
                userID           :'',
                quizID           :'',
                attempt          :'',
                search           :"",
                counter          :0,
        
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
            splitJoin(data) {
                return data.split(',');
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


            getResultDetails(pageUrl)
            {
                let _that = this;

                pageUrl = pageUrl == undefined ? 'result-details' : pageUrl;

                axios.get(pageUrl,
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        },
                        params: {
                            user_id: _that.userID,
                            quiz_id:_that.quizID,
                            attempt:_that.attempt
                        }
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response.data);
                            _that.resultDetails   = response.data.result_details;
                            // alert(_that.resultDetails)
                            _that.isLoading          = false;
                            _that.setTimeoutElements();

                        }
                        else{
                            _that.success_message   = "";
                            _that.error_message     = response.data.error;
                        }
                    })
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
            this.attempt = this.$route.params.attempt;
            // console.log( this.userID, this.attempt);
            this.getResultDetails();
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

    .result-items-wrapper {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }

    .result-items-wrapper > li {
        width: 50%;
        max-width: 0 0 50%;
        padding: 10px 15px;
    }

    .result-items-wrapper > li .q-box {
        background: #f1f1f1;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 30px;
        height: 100%;
        box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }

    @media screen and (max-width: 767px) {
        .result-items-wrapper > li {
            width: 100%;
            max-width: 0 0 100%;
        }
    }
</style>
