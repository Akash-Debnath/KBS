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
                    <h2 class="content-title text-uppercase m-0">Exchange Rate List</h2>
                </div>
                <div class="content-wrapper bg-white">
                    <!-- list top area -->
                    <div class="list-top-area px-15 py-10 d-sm-flex justify-content-between align-items-center">
                        <div class="adding-btn-area d-md-flex align-items-center">
                            <div>
                                <button class="btn common-gradient-btn ripple-btn new-agent-session right-side-common-form mx-10 m-w-140 px-15 mb-10 mb-md-0"
                                        @click="isAddCheck=true" v-if="checkPermission('category-create')">
                                    <i class="fas fa-plus"></i>
                                    Add Exchange Rate
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
                                <li>
                                    <a v-if="isExportCheck"
                                       :href=downloadUrl class="download-btn" title="Download CSV">
                                        <i class="fas fa-download"></i> <span class="hide-on-responsive">Download CSV</span>
                                    </a>
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
                                                <v-data-table :headers="headers" :items="exchangeRate" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">
                                                    <template v-slot:item.status="{item}">
                                                        {{ item.status=="1" ? "Enable": 'Disable'   }}
                                                    </template>

        
                                                    <template v-slot:item.actions="{item}">

                                                        <button class="btn btn-success ripple-btn right-side-common-form btn-xs mx-1"
                                                                @click="exchange_rate_id=item.id, isEditCheck=true" v-if="checkPermission('category-edit')">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <button  class="btn btn-danger ripple-btn right-side-common-form btn-xs mx-1"
                                                                 @click="exchange_rate_id=item.id, isDeleteCheck=true" v-if="checkPermission('category-delete')">
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
            <exchangeRateAdd v-if="isAddCheck" :isAddCheck="isAddCheck" @rate-slide-close="getAddDataFromChild"></exchangeRateAdd>
            <!--            edit data-->
            <exchangeRateEdit v-if="isEditCheck" :isEditCheck="isEditCheck" :exchangeRateId="exchange_rate_id" @rate-slide-close="getEditDataFromChild"></exchangeRateEdit>
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
                                <p class="text-center"> Confirmation for Deleting Menu</p>
                                <div class="form-group d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-danger text-white rounded-pill ripple-btn px-30 mx-2" @click="deleteLink()"><i class="fas fa-trash"></i> Confirm</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-30 mx-2" @click="removingRightSideWrapper(), isDeleteCheck=false"><i class="fas fa-times-circle" ></i> Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isHistoryCheck">
                <div class="right-sidebar-content-area px-2">

                    <div class="form-wrapper">
                        <h2 class="section-title text-uppercase mb-20">History List</h2>
                        <HistoryList v-if="isHistoryCheck" :isHistoryCheck="isHistoryCheck" :postID="menu_info.id"></HistoryList>
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
    import exchangeRateAdd from "@/components/exchange-rate/exchangeRateAdd";
    import exchangeRateEdit from "@/components/exchange-rate/exchangeRateEdit";
    import Loading from "@/components/loader/loading";
    import HistoryList from "@/components/history/HistoyList";
    import $ from "jquery";
    export default {
        name        : "exchangeRate.vue",
        components  : {
            Header,
            Menu,
            exchangeRateAdd,
            exchangeRateEdit,
            Loading,
            HistoryList,
        },

        data() {
            return {
                isLoading               : false,
                isHistoryCheck          : false,
                isEditCheck             : false,
                isAddCheck              : false,
                isDeleteCheck           : false,
                isExportCheck           : false,
                isSearch                : false,
                menu_info               : '',
                exchange_rate_id        : '',
                category_name           : '',
                success_message         : '',
                error_message           : '',
                token                   : '',
                exchangeRate            : '',
                userInfo                : '',
                downloadUrl             : 'categories/export/',
                user_permissions        : '',
                mappedPermission        : '',
                userInformation         :'',
                filter                  : {
                    isAdmin             : 1,
                    name                : ''
                },
                search                  : "",
                pagination              : {
                    current             : 1,
                    per_page            : 20,
                    total               : ''
                },
                headers                 : [
                    // {
                    //     text: 'ID',
                    //     value: 'id',
                    // },
                    {
                        text: 'Currency Name',
                        value: 'currency_name',
                    },
                    {
                        text: 'Currency Code',
                        value: 'currency_code',
                    },
                    {
                        text: 'Buying',
                        value: 'buying_rate',
                    },
                    {
                        text: 'Selling',
                        value: 'selling_rate',
                    },
                    {
                        text: 'Status',
                        value: 'status',
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
            removingRightSideWrapper()
            {
                this.isAddCheck         = false;
                this.isEditCheck        = false;
                this.isDeleteCheck      = false;
                this.isHistoryCheck     = false;

                document.body.classList.remove('open-side-slider');
                $('.right-sidebar-wrapper').toggleClass('right-side-common-form-show');
            },
            clearAllChecker()
            {
                this.isAddCheck         = false;
                this.isEditCheck        = false;
                this.isDeleteCheck      = false;
                this.isHistoryCheck     = false;
            },
            getAddDataFromChild (status)
            {

                this.success_message = status;
                this.isLoading = true;
                this.getExchangeRate();
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },
            getEditDataFromChild (status)
            {
                this.isLoading = true;
                this.success_message = status;
                this.getExchangeRate();
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },
            getExchangeRate(pageUrl)
            {
                let _that = this;

                pageUrl = pageUrl == undefined ? 'exchange-rate' : pageUrl;

                axios.get(pageUrl+'?page='+this.pagination.current,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                        params :
                        {
                            isAdmin : 1,
                            isList: 1,
                            isRole : _that.userInformation.roles[0].id,
                        },
                    }).then(function (response)
                {
                    if(response.data.status_code === 200)
                    {   
                        console.log(response.data);
                        _that.pagination.current = response.data.exchange_rate.current_page;
                        _that.pagination.total   = response.data.exchange_rate.last_page;
                        _that.exchangeRate       = response.data.exchange_rate.data;
                        _that.isLoading          = false;
                        _that.isExportCheck      = true;
                    }
                    else
                    {
                        _that.success_message   = "";
                        _that.error_message     = response.data.error;
                    }
                });

            },
            onPageChange()
            {
                this.getExchangeRate();
            },
            
            deleteLink()
            {
                let _that = this;
                axios.delete('exchange-rate/delete',
                    {
                        data:
                            {
                                id  : this.exchange_rate_id
                            },
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    }).then(function (response) {

                    if (response.data.status_code == 200) {
                        _that.isLoading = true;
                        _that.getExchangeRate();
                        _that.removingRightSideWrapper();
                        _that.error_message         = '';
                        _that.success_message       = "Exchange Rate Deleted Successfully";
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

            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            if (this.userInformation!= ''){
                this.isLoading = true;
                this.getExchangeRate();
                this.user_permissions = JSON.parse(localStorage.getItem("userPermissions"));
                this.mappedPermission = (this.user_permissions ).map(x => x.slug);
                this.downloadUrl = axios.defaults.baseURL+this.downloadUrl;
            }

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
