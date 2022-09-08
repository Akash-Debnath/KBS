<template>

    <div class="main-wrapper d-flex">
        <Menu></Menu>
        <div class="main-content-wrapper w-100 position-relative overflow-auto bg-white" >
            <!-- Topbar -->
            <Header></Header>
            <!-- Topbar End -->
            <!-- Content Area -->
            <div class="content-area">
                <div class="content-title-wrapper px-15 py-10">
                    <h2 class="content-title text-uppercase m-0">Quiz Form Field List</h2>
                </div>
                <div class="content-wrapper bg-white">
                    <!-- list top area -->
                    <div class="list-top-area px-15 py-10 d-sm-flex justify-content-between align-items-center">
                        <div class="adding-btn-area d-md-flex align-items-center">
                            <div class="d-flex align-items-center">
<!--                                //left-->
                            </div>
                        </div>

                        <div class="reload-download-expand-area">
                            <ul class="list-inline d-inline-flex align-items-center justify-content-end mb-0">
                                <li>
                                    <button class="reload-btn" @click="clearFilter()">
                                        <div class="d-flex jsutify-content-center align-items-center">
                                            <i class="fas fa-sync"></i> <span class="hide-on-responsive">Reload</span>
                                        </div>
                                    </button>
                                </li>
                                <li><button class="download-btn" title="Download CSV"><i class="fas fa-download"></i> <span class="hide-on-responsive">Download CSV</span></button></li>
                                <li><button class="screen-expand-btn"><i class="fas fa-expand-arrows-alt"></i> <span class="hide-on-responsive">Full Screen</span></button></li>
                            </ul>
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
                                                <v-data-table :headers="headers" :items="allFields" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">
<!--                                                    <template v-slot:item.sl="{item}">-->
<!--                                                       {{allFields.map(function(x) {return x.id; }).indexOf(item.id)+1}}-->
<!--                                                    </template>-->

                                                    <template v-slot:item.actions="{item}" >
                                                       <button class="btn btn-success ripple-btn right-side-common-form btn-xs m-1"  @click="quiz_form_field_id = item.id, isEditCheck=true"
                                                                v-if="checkPermission('quiz-form-field-edit')">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <button  class="btn btn-danger ripple-btn right-side-common-form btn-xs m-1" @click="quiz_form_field_id = item.id, isDeleteCheck=true"
                                                                v-if="checkPermission('quiz-form-field-delete')">
                                                            <i class="fas fa-trash-restore-alt"></i>
                                                        </button>
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
<!--                add-->
<!--                <QuizFromFieldAdd v-if="isAddFieldCheck" :isAddFieldCheck= "isAddFieldCheck" :quizFormFieldId="quiz_form_field_id" @quiz-form-field-data="getAddDataFromChild"></QuizFromFieldAdd>-->
<!--                edit-->
                <QuizFromFieldEdit v-if="isEditCheck" :isEditCheck= "isEditCheck" :quizFormFieldId="quiz_form_field_id" @quiz-form-field-slide-close="getEditDataFromChild"></QuizFromFieldEdit>
<!--                delete-->
                <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isDeleteCheck">
                    <div class="right-sidebar-content-area px-2">
                        <div class="form-wrapper">
                            <h2 class="section-title text-uppercase mb-20">Quiz Form Field Delete</h2>
                            <div class="row mt-50 mt-md-80">
                                <div class="col-md-12">
                                    <figure class="mx-auto text-center">
                                        <img class="img-fluid mxw-100" src="../../assets/img/delete-big-icon.svg" alt="delete-big">
                                    </figure>
                                    <p class="text-center"> Confirmation for Deleting Quiz Form</p>
                                    <div class="form-group d-flex justify-content-center align-items-center">
                                        <button type="button" class="btn btn-danger text-white rounded-pill ripple-btn px-30 mx-2" @click="deleteQuizFormField()"><i class="fas fa-trash"></i> Confirm</button>
                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-30 mx-2" @click="removingRightSideWrapper()"><i class="fas fa-times-circle" ></i> Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Content Area End -->
        </div>
    </div>
</template>
<script>
    import Header from "@/layouts/common/Header";
    import Menu from "@/layouts/common/Menu";
    import Loading from "@/components/loader/loading";
    import QuizFromFieldEdit from "../quiz-form-fields/quizFormFieldEdit";
    // import QuizFromFieldAdd from "../quiz-form-fields/quizFormFieldAdd";
    import axios from "axios";
    import $ from "jquery";

    export default {
        name: "quizFormFieldList.vue",
        components: {
            Header,
            Menu,
            // QuizFromFieldAdd,
            QuizFromFieldEdit,
            Loading
        },

        data() {
            return {
                isLoading           : false,
                isAddFieldCheck     : false,
                isAddCheck          : false,
                isEditCheck         : false,
                isDeleteCheck       : false,
                success_message     : '',
                error_message       : '',
                token               : '',
                user_permissions    : '',
                mappedPermission    : '',
                allFields           : '',
                quizFormName        : [],
                quiz_form_field_id  :'',
                allQuizFieldFrom    : '',
                // filter          : {
                //     isAdmin         : 1,
                //     username        : '',
                //     email           : '',
                //     role            : ''
                // },
                // pagination      : {
                //     from            : '',
                //     to              : '',
                //     first_page_url  : '',
                //     last_page       : '',
                //     last_page_url   : '',
                //     next_page_url   :'',
                //     prev_page_url   : '',
                //     path            : '',
                //     per_page        : 10,
                //     total           : ''
                // },
                search              :"",
                pagination  :{
                    current         :1,
                    per_page        : 20,
                    total           : ''
                },
                headers: [
                    // {
                    //     text: 'SL',
                    //     value: 'sl',
                    // },
                    {
                        text: 'Quiz Form ID',
                        value: 'quiz_form_id',
                    },
                    {
                        text: 'Label',
                        value: 'f_label',
                    },
                    {
                        text: 'Name',
                        value: 'f_name',
                    },
                    {
                        text: 'ID',
                        value: 'f_id',
                    },
                    {
                        text: 'Class',
                        value: 'f_class',
                    },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable:false
                    },
                    
                    
                ],
            }
        },

        methods: {
            clearFilter()
            {
                this.getQuizFormFieldList();
                this.isAddCheck         = false;
                this.isDeleteCheck      = false;
                this.isEditCheck        = false;
                // this.filter.username    = "";
                // this.filter.email       = "";
                // this.filter.role        = "";
                // this.success_message    = "";
                // this.error_message      = "";
            },
            clearAllChecker()
            {
                this.isAddCheck         = false;
                this.isDeleteCheck      = false;
                this.isEditCheck        = false;
            },

            removingRightSideWrapper()
            {
                this.isAddCheck         = false;
                this.isEditCheck        = false;
                this.isDeleteCheck      = false;

                document.body.classList.remove('open-side-slider');
                $('.right-sidebar-wrapper').toggleClass('right-side-common-form-show');
            },

            getAddDataFromChild (status)
            {
                this.isLoading = true;
                this.getQuizFormFieldList();
                this.success_message = status;
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },

            getEditDataFromChild (status)
            {
                this.isLoading = true;
                this.getQuizFormFieldList();
                this.success_message = status;
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },

            deleteQuizFormField()
            {
                let _that = this;
              console.log(_that.quiz_form_field_id);

                axios.delete('quiz-form-fields/delete',
                    {
                        data: {
                                id      : _that.quiz_form_field_id
                        },
                        headers: {
                            'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                        },
                    }).then(function (response) {

                    if (response.data.status_code == 200)
                    {
                        _that.isLoading = true;
                        _that.getQuizFormFieldList();
                        _that.removingRightSideWrapper();
                        _that.error_message     = '';
                        _that.success_message   = "Quiz form field deleted successfully!";
                        _that.setTimeoutElements();

                    } else{
                        _that.success_message = "";
                        _that.error_message   = response.data.error;
                    }

                }).catch(function (error) {
                    console.log(error);
                });

            },

            getQuizFormFieldList(pageUrl)
            {
                let _that   = this;
                pageUrl     = pageUrl == undefined ? 'quiz-form-fields' : pageUrl;
                axios.get(pageUrl+'?page='+this.pagination.current,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response.data);
                            // _that.pagination        = response.data.quiz_form_field_list;
                            _that.pagination.current = response.data.quiz_form_field_list.current_page;
                            _that.pagination.total  = response.data.quiz_form_field_list.last_page;
                            _that.allFields         = response.data.quiz_form_field_list.data;
                            _that.isLoading         = false;
                        }
                        else{
                            _that.success_message   = "";
                            _that.error_message     = response.data.error;
                        }
                    })
            },
            onPageChange() {
                this.getQuizFormFieldList();
            },
            setTimeoutElements()
            {
                // setTimeout(() => this.isLoading = false, 3e3);
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
            },

            checkPermission(permissionForCheck)
            {
                if((this.mappedPermission).includes(permissionForCheck) === true) {
                    return true;
                } else {
                    return false;
                }
            },

        },
        created()
        {
            if (this.$route.params){
                this.success_message = this.$route.params.success_message;
                this.setTimeoutElements();
                setTimeout(()=>{
                    this.$route.params.success_message = [];
                },2e3);
            }
            this.isLoading  = true;
            this.getQuizFormFieldList();
            this.user_permissions = JSON.parse(localStorage.getItem("userPermissions"));
            this.mappedPermission = (this.user_permissions ).map(x => x.slug);
        }
    }
</script>

<style scoped>
.mhv-100 {
    min-height: 50vh;
}
</style>
