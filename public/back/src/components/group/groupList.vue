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
                    <h2 class="content-title text-uppercase m-0">Group List</h2>
                </div>

                <div class="content-wrapper bg-white">
                    <!-- list top area -->
                    <div class="list-top-area px-15 py-10 d-sm-flex justify-content-between align-items-center">
                        <div class="adding-btn-area d-md-flex align-items-center">
                            <div class="d-flex align-items-center">
                                <button class="btn common-gradient-btn ripple-btn new-agent-session right-side-common-form mx-10 m-w-140 px-15 mb-10 mb-md-0" @click="isAddCheck=true" v-if="checkPermission('role-create')">
                                    <i class="fas fa-plus"></i>
                                    Add Group
                                </button>
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
                                                <v-data-table :headers="headers" :items="groupList" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">

                                                    <template v-slot:item.actions="{item}" >
                                                        <button  v-if="checkPermission('group-edit') && item.name!='Super Admin' " class="btn btn-success ripple-btn right-side-common-form btn-xs mx-1"  @click="group_id=item.id, isEditCheck=true"><i class="fas fa-pen"></i></button>
                                                        <button  v-if="checkPermission('group-delete') && item.name!='Super Admin' " class="btn btn-danger ripple-btn right-side-common-form btn-xs mx-1" @click="group_id=item.id, isDeleteCheck=true"><i class="fas fa-trash-restore-alt"></i></button>
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

        <div class="right-sidebar-wrapper with-upper-shape fixed-top px-20 pb-30 pb-md-40 pt-70">
            <div class="close-bar d-flex align-items-center justify-content-end">
                <button class="right-side-close-btn ripple-btn-danger" @click="clearAllChecker">
                    <img src="../../assets/img/cancel.svg" alt="cancel">
                </button>
            </div>
            <!--            addd-->
            <GroupAdd v-if="isAddCheck" :isAddCheck= "isAddCheck" @group-slide-close="getAddDataFromChild"></GroupAdd>
            <!--            edit-->
            <GroupEdit v-if="isEditCheck" :isEditCheck="isEditCheck" :roleId="group_id" @group-slide-close="getEditDataFromChild"></GroupEdit>
            <!--            delete-->
            <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isDeleteCheck===true">
                <div class="right-sidebar-content-area px-2">

                    <div class="form-wrapper">
                        <h2 class="section-title text-uppercase mb-20">Delete Role</h2>

                        <div class="row mt-50 mt-md-80">
                            <div class="col-md-12">
                                <figure class="mx-auto text-center">
                                    <img class="img-fluid mxw-100" src="../../assets/img/delete-big-icon.svg" alt="delete-big">
                                </figure>
                                <p class="text-center"> Confirmation for Deleting Role</p>

                                <div class="form-group d-flex justify-content-center align-items-center">
                                    <button type="button" class="btn btn-danger text-white rounded-pill ripple-btn px-30 mx-2" @click="deleteRole()"><i class="fas fa-trash"></i> Confirm</button>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-30 mx-2" @click="removingRightSideWrapper()"><i class="fas fa-times-circle" ></i> Cancel</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

        <!-- Common Right SideBar End -->
    </div>
</template>

<script>
    import Header from "@/layouts/common/Header";
    import Menu from "@/layouts/common/Menu";
    import Loading from "@/components/loader/loading";
    import axios from "axios";
    import $ from "jquery";
    import GroupAdd from "./groupAdd";
    import GroupEdit from "./groupEdit";

    export default {
        name: "groupList",
        components: {
            GroupEdit,
            GroupAdd,
            Header,
            Menu,
            Loading
        },
        data (){
            return {
                isLoading           : false,
                isEditCheck         : false,
                isAddCheck          : false,
                isDeleteCheck       : false,
                isSearchCheck       : false,
                success_message     : '',
                error_message       : '',
                group_id             : '',
                groupList           : '',

                user_permissions    : '',
                mappedPermission    : '',
                search              :"",
                pagination  :{
                    current         :1,
                    per_page        : 20,
                    total           : ''
                },
                headers: [
                    {
                        text: 'Group Name',
                        value: 'name',
                    },
                    {
                        text: 'Created Date',
                        value: 'created_at',
                    },
                    {
                        text: 'Updated Date',
                        value: 'updated_at',
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
            clearAllChecker()
            {
                this.isAddCheck         = false;
                this.isDeleteCheck      = false;
                this.isEditCheck        = false;
                this.isSearchCheck      = false;
            },

            removingRightSideWrapper()
            {
                this.isAddCheck         = false;
                this.isEditCheck        = false;
                this.isDeleteCheck      = false;
                this.isSearchCheck      = false;

                document.body.classList.remove('open-side-slider');
                $('.right-sidebar-wrapper').toggleClass('right-side-common-form-show');
            },

            getAddDataFromChild (status)
            {
                this.isLoading = true;
                this.getGroupList();
                this.success_message = status;
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },

            getEditDataFromChild (status)
            {
                this.isLoading = true;
                this.getGroupList();
                this.success_message = status;
                this.removingRightSideWrapper();
                this.setTimeoutElements();
            },

            getGroupList(pageUrl)
            {
                let _that       = this;
                pageUrl         = pageUrl == undefined ? 'groups' : pageUrl;

                axios.get(pageUrl+'?page='+this.pagination.current,
                    {
                        headers: {
                            'Authorization'        : 'Bearer '+localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if(response.data.status === 200){
                            _that.pagination.current = response.data.group_list.current_page;
                            _that.pagination.total = response.data.group_list.last_page;
                            _that.groupList       = response.data.group_list.data;
                            _that.isLoading       = false;
                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },

            onPageChange() {
                this.getGroupList();
            },

            deleteRole()
            {
                let _that = this;

                axios.delete('groups/'+_that.group_id,
                    {
                        data    : {
                            id                  : this.group_id
                        },
                        headers : {
                            'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                        },
                    }).then(function (response) {
                    if (response.data.status == 200){
                        _that.isLoading = true;
                        _that.getGroupList();
                        _that.removingRightSideWrapper();
                        _that.error_message     = '';
                        _that.success_message   = "Group deleted successfully!";
                        _that.setTimeoutElements();
                    }
                    else
                    {
                        _that.success_message   = "";
                        _that.error_message     = response.data.error;
                        _that.removingRightSideWrapper();
                        _that.setTimeoutElements();
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },

            setTimeoutElements()
            {
                // setTimeout(() => this.isLoading = false, 3e3);
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
            this.isLoading  = true;
            this.user_permissions = JSON.parse(localStorage.getItem("userPermissions"));
            this.mappedPermission = (this.user_permissions ).map(x => x.slug);
            this.getGroupList();
        }
    }
</script>

<style scoped>
    .mhv-100 {
        min-height: 50vh;
    }
</style>
