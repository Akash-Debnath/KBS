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
          <h2 class="content-title text-uppercase m-0">FAQ List</h2>
        </div>
        <div class="content-wrapper bg-white">
          <!-- list top area -->
          <div class="list-top-area px-15 py-10 d-sm-flex justify-content-between align-items-center">
            <div class="adding-btn-area d-md-flex align-items-center justify-content-between">
              <div>
                <button class="btn common-gradient-btn ripple-btn new-agent-session right-side-common-form mx-10 m-w-140 px-15 mb-10 mb-md-0"
                        @click="isAddCheck=true" v-if="checkPermission('faq-create')">
                  <i class="fas fa-plus"></i>
                  Add FAQ
                </button>
                <!-- <button class="btn common-gradient-btn ripple-btn search-btn right-side-common-form text-white mx-10 m-w-140 px-15 mb-10 mb-md-0"
                        @click="isSearchCheck=true">
                  <i class="fas fa-search"></i> <span class="ml-1">Search</span>
                </button> -->
              </div>
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
                  <v-container>
                    <v-row justify="end">
                      <v-col md="3" class="customer-search-wrapper">
                        <v-text-field v-model="search" append-icon="mdi-magnify" label="Search" single-line hide-details />
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col class="customer-data-table-wrapper">
                        <v-data-table :headers="headers" :items="faqList" :search="search" :hide-default-footer=true  class="elevation-1" :items-per-page="20">
                          <template v-slot:item.user.first_name="{item}">
                            {{ item.user ? (item.user.first_name +' '+ item.user.last_name) : '' }}
                          </template>
                          <template v-slot:item.category="{item}">
                            {{ item.category ? item.category.name : ''  }}
                          </template>

                          <template v-slot:item.commentable_status="{item}">
                            {{item.commentable_status === 0 ? 'Inactive' : 'Active'}}
                          </template>

                          <template v-slot:item.is_notifiable="{item}">
                            {{item.is_notifiable == 0 ? 'NO' : 'YES'}}
                          </template>

                          <template v-slot:item.status="{item}">
                            <select class="form-control" v-model="item.status" @change="faqStatusRequest(item)">
                              <option value="draft">Draft</option>
                              <option value="hide">Hide</option>
                              <option value="private">Private</option>
                              <option value="public">Public</option>
                            </select>
                          </template>

                          <!--                              <template v-slot:item.history="{item}">-->

                          <!--                                <button class="btn btn-primary ripple-btn right-side-common-form btn-xs mx-1" @click="isHistoryCheck=true, faq_info=item"-->
                          <!--                                      v-if="checkPermission('faq-edit')">-->
                          <!--                                  <i class="fas fa-book text-white"></i>-->
                          <!--                                </button>-->


                          <!--                            </template>-->

                          <template v-slot:item.actions="{item}" >
                            <button class="btn btn-primary ripple-btn right-side-common-form btn-xs mx-1" @click="isHistoryCheck=true, faq_info=item"
                                    v-if="checkPermission('history-list')">
                              <i class="fas fa-book text-white"></i>
                            </button>
                            <router-link :to="{ name: 'faqDetails', params: { id: item.id, slug: item.slug }}" class="btn btn-secondary btn-xs m-1">
                              <i class="fas fa-eye text-white"></i>
                            </router-link>
                            <button class="btn btn-success ripple-btn right-side-common-form btn-xs m-1"
                                    @click="faq_id=item.id, isEditCheck = true"
                                    v-if="checkPermission('faq-edit')">
                              <i class="fas fa-pen"></i>
                            </button>

                            <button  class="btn btn-danger ripple-btn right-side-common-form btn-xs m-1"
                                     @click="faq_id=item.id, isDeleteCheck = true"
                                     v-if="checkPermission('faq-delete')">
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
    <!--        slide form-->
    <div class="right-sidebar-wrapper with-upper-shape fixed-top px-20 pb-30 pb-md-40 pt-70">
      <div class="close-bar d-flex align-items-center justify-content-end">
        <button class="right-side-close-btn ripple-btn-danger" @click="clearAllChecker">
          <img src="../../assets/img/cancel.svg" alt="cancel">
        </button>
      </div>
      <!--            add-->
      <!--      <FaqAdd v-if="isAddCheck" :isAddCheck= "isAddCheck"  @faq-id="getFaqIDFromChild" @faq-slide-close="getAddDataFromChild()"></FaqAdd>-->
      <FaqStepAdd v-if="isAddCheck" :isAddCheck= "isAddCheck"  @faq-id="getFaqIDFromChild" @faq-slide-close="getAddDataFromChild()"></FaqStepAdd>
      <!--            edit-->
      <FaqEdit v-if="isEditCheck" :isEditCheck="isEditCheck" :faqId="faq_id"  @faq-edit-id="getFaqIDFromChild" @faq-slide-close="getEditDataFromChild()"></FaqEdit>
      <!--history-->

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
            <HistoryList v-if="isHistoryCheck" :isHistoryCheck="isHistoryCheck" :postID="faq_info.id"></HistoryList>
          </div>
        </div>
      </div>
      <!--delete-->
      <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isDeleteCheck">
        <div class="right-sidebar-content-area px-2">

          <div class="form-wrapper">
            <h2 class="section-title text-uppercase mb-20">Delete FAQ</h2>
            <div class="row mt-50 mt-md-80">
              <div class="col-md-12">
                <figure class="mx-auto text-center">
                  <img class="img-fluid mxw-100" src="../../assets/img/delete-big-icon.svg" alt="delete-big">
                </figure>
                <p class="text-center"> Confirmation for Deleting FAQ</p>

                <div class="form-group d-flex justify-content-center align-items-center">
                  <button type="button" class="btn btn-danger text-white rounded-pill ripple-btn px-30 mx-2" @click="deleteFaq()"><i class="fas fa-trash"></i> Confirm</button>
                  <button type="button" class="btn btn-outline-secondary rounded-pill px-30 mx-2" @click="removingRightSideWrapper()"><i class="fas fa-times-circle" ></i> Cancel</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--            search-->
      <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isSearchCheck">
        <div class="right-sidebar-content-area px-2">

          <div class="form-wrapper" >
            <h2 class="section-title text-uppercase mb-20">Search</h2>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-15">
                  <label for="title">Title</label>
                  <input class="form-control" type="text" v-model="filter.en_title"  id="title" @keyup.enter="getFaqList(), removingRightSideWrapper()">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-15">
                  <label for="closedReasonCode">Tag</label>
                  <input class="form-control" type="text" v-model="filter.tag" id="closedReasonCode" @keyup.enter="getFaqList(), removingRightSideWrapper()">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">\
                  <label for="category_id">Select A Category</label>

                  <select class="form-control" v-model="filter.category_id" id="category_id">
                    <option value="">Select A Category</option>
                    <option v-for="a_category in categoryList" :value="a_category.id" :key="a_category">
                      {{a_category.name}}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="status">Select A Status</label>

                  <select class="form-control" v-model="filter.status" id="status">
                    <option value="">Select A Status</option>
                    <option value="draft">Draft</option>
                    <option value="hide">Hide</option>
                    <option value="private">Private</option>
                    <option value="public">Public</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group text-right">
              <button class="btn common-gradient-btn ripple-btn px-50" @click="getFaqList(), removingRightSideWrapper()"><i class="fas fa-search"></i> <span class="ml-1">Search</span></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Common Right SideBar End -->
    <div class="modal fade" id="alertModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 text-center">
                <h5 class="pt-10 pb-15 mb-0">Are you sure to perform this action?</h5>
                <div>
                  <button type="button" id="closeModal" class="btn btn-danger rounded btn-md m-2" data-dismiss="modal" @click="getFaqList()">Close</button>
                  <button type="button" class="btn btn-primary rounded btn-md m-2" @click="changeFaqStatus();">Update</button>
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
  import FaqAdd from "@/components/faq/faqAdd";
  import FaqStepAdd from "@/components/faq/faqAddStepForm";
  import FaqEdit from "@/components/faq/faqEdit";
  import Loading from "@/components/loader/loading";
  import HistoryList from "@/components/history/HistoyList";
  import axios from "axios";
  import $ from "jquery";

  export default {
    name: "faqList.vue",
    components: {
      Header,
      Menu,
      FaqAdd,
      FaqEdit,
      Loading,
      HistoryList,
      FaqStepAdd
    },

    data() {
      return {
        isHistoryCheck      : false,
        isFaqStatus         : '',
        isLoading           : false,
        isEditCheck         : false,
        unstoredFaqID       : '',
        isAddCheck          : false,
        isDeleteCheck       : false,
        isSearchCheck       : false,
        isExportCheck        :false,
        downloadUrl         : 'faqs/export/',
        success_message     : '',
        error_message       : '',
        token               : '',
        categoryList        : '',
        user_permissions    : '',
        mappedPermission    : '',
        faqList             : '',
        faq_id              : '',
        faq_info            : '',

        search              :"",
        pagination          :{
          current         :1,
          per_page        : 20,
          total           : ''
        },
        headers: [
          {
            text: 'Title',
            value: 'en_title',
          },
          {
            text: 'Author',
            value: 'user.first_name',
          },
          {
            text: 'Category',
            value: 'category',
          },
          {
            text: 'Status',
            value: 'status',
          },
          {
            text: 'Tag',
            value: 'tag',
          },
          {
            text: 'Commentable',
            value: 'commentable_status',
          },
          {
            text: 'Notifiable',
            value: 'is_notifiable',
          },
          {
            text: 'Publish Date',
            value: 'created_at',
          },
          // {
          //   text: 'History',
          //   value: 'history',
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
      getFaqIDFromChild(faq_id){
        console.log(faq_id);
        this.unstoredFaqID = faq_id;
      },
      faqStatusRequest(selected){
        $('#alertModal').modal('show');
        localStorage.setItem('faq_status', JSON.stringify(selected));
      },
      changeFaqStatus(){
        this.isFaqStatus = JSON.parse(localStorage.getItem("faq_status"));
        $('#alertModal').modal('toggle');
        axios.post('change-faq-status',
                {
                  id          : this.isFaqStatus.id,
                  status      : this.isFaqStatus.status,
                },
                {
                  headers: {
                    'Authorization': 'Bearer '+localStorage.getItem('authToken')
                  }
                }).then(function (response) {
          if (response.data.status_code === 200){
            // this.getArticleList();
            // this.success_message   = "Article status changed successfully!";
            localStorage.removeItem("faq_status");
          }
        }).catch(function (error) {
          console.log(error);
        });
      },
      clearFilter()
      {
        this.filter.category_id     = "";
        this.filter.status          = "";
        this.filter.en_title        = "";
        this.filter.tag             = "";
        this.success_message        = "";
        this.error_message          = "";
        this.getFaqList();
      },
      clearAllChecker()
      {
        let _that = this;
        _that.isSearchCheck      = false;
        _that.isAddCheck         = false;
        _that.isEditCheck        = false;
        _that.isDeleteCheck      = false;
        _that.isHistoryCheck      = false;

        if (_that.unstoredFaqID){
          axios.get('contents-faq-exist/'+this.unstoredFaqID,{
            headers: {
              'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
            },
          }).then(function (response) {
            console.log(response);
            _that.unstoredFaqID = '';
          })
        }
      },
      setTimeoutElements()
      {
        // setTimeout(() => this.isLoading = false, 3e3);
        setTimeout(() => this.success_message = "", 2e3);
        setTimeout(() => this.error_message = "", 2e3);
      },

      removingRightSideWrapper()
      {
        this.isAddCheck         = false;
        this.isEditCheck        = false;
        this.isDeleteCheck      = false;
        this.isSearchCheck      = false;
        this.isHistoryCheck      = false;

        document.body.classList.remove('open-side-slider');
        $('.right-sidebar-wrapper').toggleClass('right-side-common-form-show');
      },

      getAddDataFromChild (status)
      {
        // console.log(status);
        this.isLoading = true;
        this.success_message = "FAQ added successfully!";
        this.getFaqList();
        this.removingRightSideWrapper();
        this.setTimeoutElements();
      },

      getEditDataFromChild (status)
      {
        console.log(status);
        this.isLoading = true;
        this.success_message = "FAQ updated successfully!";
        this.getFaqList();
        this.removingRightSideWrapper();
        this.setTimeoutElements();
      },

      getCategoryList()
      {
        let _that   = this;
        axios.get('categories',
                {
                  headers : {
                    'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                  },
                  params  :
                          {
                            isAdmin             : 1,
                            without_pagination  : 1
                          },

                })
                .then(function (response) {
                  if(response.data.status_code === 200){
                    _that.categoryList          = response.data.category_list;
                  }
                  else{
                    _that.success_message       = "";
                    _that.error_message         = response.data.error;
                  }
                })
      },

      getFaqList(pageUrl)
      {
        let _that =this;
        pageUrl = pageUrl == undefined ? 'faqs' : pageUrl;

        axios.get(pageUrl+'?page='+this.pagination.current,
                {
                  headers: {
                    'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                  },
                  params : {
                    isAdmin         : 1,
                    isRole          : _that.userInformation.roles[0].id
                  },

                })
                .then(function (response) {
                  if(response.data.status_code === 200){
                    console.log(response.data);
                    _that.pagination.current = response.data.faq_list.current_page;
                    _that.pagination.total   = response.data.faq_list.last_page;
                    _that.faqList            = response.data.faq_list.data;
                    // _that.pagination         = response.data.faq_list;
                    _that.isLoading          = false;
                    _that.isExportCheck      = true;
                  }
                  else{
                    _that.success_message   = "";
                    _that.error_message     = response.data.error;
                  }
                })
      },
      onPageChange() {
        this.getFaqList();
      },

      deleteFaq()
      {
        let _that = this;
        axios.delete('faqs/delete',
                {
                  data        : {
                    id      : this.faq_id
                  },
                  headers     : {
                    'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                  },
                }).then(function (response) {
          if (response.data.status_code === 200) {
            _that.isLoading = true;
            _that.getFaqList();
            _that.removingRightSideWrapper();
            _that.success_message = "FAQ deleted successfully!";
            _that.error_message   = '';
            _that.setTimeoutElements();
          }
          else
          {
            _that.success_message = "";
            _that.error_message   = response.data.error;
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
    },
    created()
    {
      this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
      if(this.userInformation!='')
      {
        this.isLoading = true;
        this.getFaqList();
        this.getCategoryList();
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
</style>
