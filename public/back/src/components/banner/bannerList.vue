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
            <div class="content-area" >
                <div class="content-title-wrapper px-15 py-10">
                    <h2 class="content-title text-uppercase m-0">Banner List</h2>
                </div>
                <div class="content-wrapper bg-white">
                    <!-- list top area -->
                    <div class="list-top-area px-15 py-10 d-sm-flex justify-content-between align-items-center">
                        <div class="adding-btn-area d-md-flex align-items-center">
                            <div>
                                <button class="btn common-gradient-btn ripple-btn new-agent-session right-side-common-form mx-10 m-w-140 px-15 mb-10 mb-md-0"
                                        @click="isAddCheck=true" v-if="checkPermission('banner-create')">
                                    <i class="fas fa-plus"></i>
                                    Add Banner
                                </button>
                            </div>
                        </div>
                        <div class="reload-download-expand-area">
                            <ul class="list-inline d-inline-flex align-items-center justify-content-end mb-0 w-100">
                                <li>
                                    <button class="reload-btn">
                                        <div class="d-flex jsutify-content-center align-items-center">
                                            <i class="fas fa-sync"></i> <span class="hide-on-responsive">Reload</span>
                                        </div>
                                    </button>
                                </li>
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
                                                <v-data-table :headers="headers" :items="bannerList" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">

                                                    <template v-slot:item.user.first_name="{item}">
                                                        {{ item.user ? (item.user.first_name +' '+ item.user.last_name) : '' }}
                                                    </template>

                                                    <template v-slot:item.media="{item}">

                                                        <div v-for="a_url in item.media" :key="a_url.id">
                                                            <span v-if="a_url.url.match(/\.(jpeg|jpg|gif|png)$/) != null">
                                                                <img :src="a_url.url" class="preview"/>
                                                            </span>

                                                            <span v-else>
                                                                <video class="preview" controls>
                                                                    <source :src="a_url.url" type="video/mp4">
                                                                    <source :src="a_url.url" type="video/ogg">
                                                                </video>

                                                            </span>
                                                        </div>

                                                    </template>

<!--                                                    <template v-slot:item.history="{item}">-->

<!--                                                        <button class="btn btn-primary ripple-btn right-side-common-form btn-xs mx-1" @click="isHistoryCheck=true, banner_info=item"-->
<!--                                                                v-if="checkPermission('article-edit')">-->
<!--                                                            <i class="fas fa-book text-white"></i>-->
<!--                                                        </button>-->


<!--                                                    </template>-->

                                                    <template v-slot:item.actions="{item}">
                                                        <button class="btn btn-primary ripple-btn right-side-common-form btn-xs mx-1" @click="isHistoryCheck=true, banner_info=item"
                                                                v-if="checkPermission('history-list')">
                                                            <i class="fas fa-book text-white"></i>
                                                        </button>
                                                        <button class="btn btn-success ripple-btn right-side-common-form btn-xs mx-1"
                                                                @click="banner_id=item.id, isEditCheck=true" v-if="checkPermission('banner-edit')">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <button  class="btn btn-danger ripple-btn right-side-common-form btn-xs mx-1"
                                                                 @click="banner_id=item.id, isDeleteCheck=true" v-if="checkPermission('banner-delete')">
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
            <!-- Content Area End -->
        </div>
        <!-- Common Right SideBar -->
        <div class="action-modal-wraper" v-if="success_message">
            <span>{{ success_message }}</span>
        </div>

        <div class="action-modal-wraper-error" v-if="error_message">
            <span>{{ error_message }}</span>
        </div>
        <!--        work-->
        <div class="right-sidebar-wrapper with-upper-shape fixed-top px-20 pb-30 pb-md-40 pt-70">
            <div class="close-bar d-flex align-items-center justify-content-end">
                <button class="right-side-close-btn ripple-btn-danger" @click="clearAllChecker">
                    <img src="../../assets/img/cancel.svg" alt="cancel">
                </button>
            </div>
            <!--            add data-->
            <BannerAdd v-if="isAddCheck" :isAddCheck="isAddCheck" @banner-slide-close="getAddDataFromChild"></BannerAdd>
            <!--            edit data-->
            <BannerEdit v-if="isEditCheck" :isEditCheck="isEditCheck" :categoryId="banner_id" @banner-slide-close="getEditDataFromChild"></BannerEdit>
<!--            history-->
            <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isHistoryCheck">
                <div class="right-sidebar-content-area px-2">

                    <div class="form-wrapper">
                        <h2 class="section-title text-uppercase mb-20">History List</h2>

                        <!--                        find category -->
                        <!--                        <small>Last updated: {{category_info.updated_at}}</small>-->
                        <!--                        <h3 class="text-uppercase mb-20">{{category_info.name}}</h3>-->
                        <!--                        <img :src="category_info.media[0].url" style="height:350px; width: auto">-->
                        <!--                        <br/>-->

                        <!--                        find history-->
                        <HistoryList v-if="isHistoryCheck" :isHistoryCheck="isHistoryCheck" :postID="banner_info.id"></HistoryList>
                    </div>
                </div>
            </div>
            <!--            delete data -->
            <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isDeleteCheck">
                <div class="right-sidebar-content-area px-2">

                    <div class="form-wrapper">
                        <h2 class="section-title text-uppercase mb-20">Delete</h2>

                        <div class="text-danger" v-if="error_message">
                            <h5>{{ error_message }}</h5>
                        </div>

                        <div class="row mt-50 mt-md-80">
                            <div class="col-md-12">
                                <figure class="mx-auto text-center">
                                    <img class="img-fluid mxw-100" src="../../assets/img/delete-big-icon.svg" alt="delete-big">
                                </figure>
                                <p class="text-center"> Confirmation for Deleting Banner</p>
                                <div class="form-group d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-danger text-white rounded-pill ripple-btn px-30 mx-2" @click="deleteBanner()"><i class="fas fa-trash"></i> Confirm</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-30 mx-2" @click="removingRightSideWrapper(), isDeleteCheck=false"><i class="fas fa-times-circle" ></i> Cancel</button>
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
    import axios from 'axios'
    import Menu from '@/layouts/common/Menu'
    import Header from '@/layouts/common/Header'
    import BannerAdd from "@/components/banner/bannerAdd";
    import BannerEdit from "@/components/banner/bannerEdit";
    import Loading from "@/components/loader/loading";
    import HistoryList from "@/components/history/HistoyList";

    import $ from "jquery";

    export default {
        name: "bannerList.vue",
        components: {
            Header,
            Menu,
            BannerAdd,
            BannerEdit,
            Loading,
            HistoryList
        },

        data() {
            return {
                isHistoryCheck      : false,
                isLoading           : false,
                isEditCheck         : false,
                isAddCheck          : false,
                isDeleteCheck       : false,
                isExportCheck        :false,
                isSearch            : false,
                banner_id         : '',
                category_parent_id  : '',
                category_name       : '',
                success_message     : '',
                error_message       : '',
                token               : '',
                bannerList        : '',
                userInfo            : '',
                downloadUrl         : 'banner/export/',
                user_permissions    : '',
                mappedPermission    : '',
                banner_info         : '',
                filter      : {
                    isAdmin         : 1,
                    name            : ''
                },
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
                        text: 'Title',
                        value: 'title',
                    },
                    {
                        text: 'Media',
                        value: 'media',
                    },
                    {
                        text: 'Created Date',
                        value: 'created_at',
                    },
                    // {
                    //     text: 'History',
                    //     value: 'history',
                    // },
                    {
                        text: 'Actions',
                        value: 'actions',
                        sortable:false
                    },


                ],
            }
        },
        methods: {
            removingRightSideWrapper()
            {
                this.isAddCheck         = false;
                this.isEditCheck        = false;
                this.isDeleteCheck      = false;
                this.isHistoryCheck      = false;

                document.body.classList.remove('open-side-slider');
                $('.right-sidebar-wrapper').toggleClass('right-side-common-form-show');
            },

            clearAllChecker()
            {
                this.isAddCheck         = false;
                this.isEditCheck        = false;
                this.isDeleteCheck      = false;
                this.isHistoryCheck      = false;
                this.getBannerList()
            },

            getAddDataFromChild (status)
            {
                this.isLoading = true;
                this.success_message = status;
                this.getBannerList();
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },

            getEditDataFromChild (status)
            {
                this.isLoading = true;
                this.success_message = status;
                this.getBannerList();
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },

            getBannerList(pageUrl)
            {
                let _that = this;

                pageUrl = pageUrl == undefined ? 'banners' : pageUrl;

                axios.get(pageUrl+'?page='+this.pagination.current,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                    }).then(function (response)
                {
                    if(response.data.status_code === 200)
                    {
                        console.log(response.data.banner_list);
                        _that.pagination.current = response.data.banner_list.current_page;
                        _that.pagination.total = response.data.banner_list.last_page;
                        _that.bannerList      = response.data.banner_list.data;
                        _that.isLoading         = false;
                        _that.isExportCheck     = true;

                    }
                    else
                    {
                        _that.success_message   = "";
                        _that.error_message     = response.data.error;
                    }
                });

            },
            onPageChange() {
                this.getBannerList();
            },

            categoryUpdate(categoryId)
            {
                let _that = this;

                axios.put('categories/update', {
                        id              : categoryId,
                        name            : this.category_name,
                        parent_id       : this.category_parent_id,
                    },
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code == 200) {
                        _that.getCategoryList();
                        _that.selectedCategory      = '';
                        _that.error_message         = '';
                        _that.success_message       = response.data.messages;

                    }
                    else {
                        _that.success_message       = "";
                        _that.error_message         = response.data.error;
                    }
                }).catch(function (error) {
                    console.log(error);
                });

            },

            deleteBanner()
            {
                let _that = this;
                axios.delete('banners/delete',
                    {
                        data:
                            {
                                id      : _that.banner_id
                            },
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    }).then(function (response) {

                    if (response.data.status_code == 200) {
                        _that.isLoading = true;
                        _that.getBannerList();
                        _that.removingRightSideWrapper();
                        _that.error_message         = '';
                        _that.success_message       = "Banner Deleted Successfully";
                        _that.setTimeoutElements();

                    } else {
                        _that.success_message       = "";
                        _that.error_message         = response.data.error;
                        _that.removingRightSideWrapper();
                        _that.setTimeoutElements();

                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },

            //alert message remove

            setTimeoutElements()
            {
                setTimeout(() => this.success_message = "", 3e3);
                setTimeout(() => this.error_message = "", 3e3);
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
            this.isLoading = true;
            this.getBannerList();
            this.user_permissions = JSON.parse(localStorage.getItem("userPermissions"));
            this.mappedPermission = (this.user_permissions ).map(x => x.slug);
            this.downloadUrl = axios.defaults.baseURL+this.downloadUrl;
        }
    }
</script>

<style scoped>
    .mhv-100 {
        min-height: 50vh;
    }

    .preview{
        height: 150px;
    }
</style>
