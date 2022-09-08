<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">


            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Edit Page</h2>

                <div class="alert-success" v-if="success_message">
                    <span>{{ success_message }}</span>
                </div>

                <div  v-if="error_message">
                    <span class="text-danger">{{ error_message }}</span>
                </div>

                <div class="row">

                    <div class="col-md-12" v-if="pageId ? page_id=pageId : ''">

    

                        <div class="form-group">
                            <label for="pageName">Page Title <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="title" id="pageTitle" @change="checkPageNameExist(title)" @keyup="checkAndChangeValidation(title,'#pageName', '#pageTitleError', '*page name')" required>
                            <span id="pageTitleError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select A Menu</label>
                            <select id="menuId" class="form-control" v-model="menu_id"  @change="checkMenuNameExist(menu_id)" required>
                                <option disabled value="">Select A page</option>
                                <option v-for="a_page in menuList" :value="a_page.id" :key="a_page.id">
                                    {{a_page.name}}
                                </option>
                            </select>
                            <span id="menuIdError" class="text-danger small"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pageName">Short Summary</label>
                            <textarea class="form-control" type="text" v-model="short_summary" id="shortSummary" required> </textarea>
                            <span id="shortSummaryError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label >Body Content</label>
                        <SummernoteEdit v-if="isMounted"   :idFromParent="enBodyEdit" :dataFromParent="enBodyData"></SummernoteEdit>
                    </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label  class="d-block">Banner Image</label>
                        <input type="file" id="banner_image" ref="files" accept="image/*" @change="onBannerFileChange" >
                        <p><small class="text-danger">Recommended (1800 x 400)px</small></p>
                    </div>
                </div>

                <div class="col-md-12" v-if="banner_url">
                    <div class="form-group" >
                        <img class="preview" style="height:150px; width: auto" :src="banner_url"/>
                    </div>
                </div>


                    <div class="col-md-12">
                        <div class="form-group mb-15">
                        <label class="form-label" >Additional Files</label>
                        <input type="file"  id="files" ref="files" multiple @change="fileUploadChange"  >
                        </div>
                    </div>

                    <div class="col-md-12 mb-15" v-if="page_files.length >0">
                        <div class="card">
                        <div class="card-header bg-light-2 px-10 py-1">List of Files</div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-10 py-1"
                                v-for="(file, index) in page_files"
                                :key="index"
                            >
                            <a :href="file.url" class="font-12 text-body d-flex justify-content-between align-items-center">
                                {{ file.name }}
                                <span class="close-btn" @click="deleteUploadedFile(index)"><i class="fa fa-trash btn btn-danger btn-xs" aria-hidden="true"></i></span>
                            </a>

                            </li>
                        </ul>
                        </div>
                    </div>


                    <div class="col-md-12 mb-15" v-if="previous_media_list.length >0">
                        <div class="card">
                        <div class="card-header bg-light-2 px-10 py-1">List of Previous Files</div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-10 py-1"
                                    v-for="(previous_file, prev_index) in previous_media_list" :key="prev_index">
                                    <a href="#" class="font-12 text-body d-flex justify-content-between align-items-center">
                                        {{ previous_file.name }}
                                        <span class="close-btn" @click="deletePreviousUploadedFile(prev_index)"><i class="fa fa-trash btn btn-danger btn-xs" aria-hidden="true"></i></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="status" >
                                <option  value="1">Active</option>
                                <option  value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                

                    <div class="col-md-12 mt-10">
                        <div class="form-group text-right">
                            <button class="btn common-gradient-btn ripple-btn px-50" @click="dataValidate()">Update</button>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    import CustomRoleEdit from '../custom-role/CustomRoleEdit'
    import SummernoteEdit from "@/components/summer-note/summernote-edit";

    import axios from "axios";
    import $ from "jquery";

    export default {

        name: "pageEdit.vue",
        props: ['isEditCheck', 'pageId'],

        components:{
            CustomRoleEdit,
            Multiselect,
            SummernoteEdit
        },

        data() {
            return {
                isMounted        : false,
                page_id          : '',
                title            : '',
                short_summary    : '',
                enBodyEdit       : "en_Body_edit",
                enBodyData       : '',
                status           : '',
                menu_id          : '',
                page_files       : [],
                fileUrl          : [],
                file_name        : '',
                banner_file      : '',
                banner_url       : '',
                banner_img       : '',
                files            : [],
                pageDetails      : '',
                tempmenuList     : '',
                menuList         : [],
                error_message    : '',
                success_message  : '',
                token            : '',
                user_roles       : '',
                roleAccess       : [],
                group_value      : [],
                group_list       : [],
                previous_media_list : [],
                // menuHasParent : false,

                logo_file        : '',
                logo_url         : '',
            }
        },

        methods: {
            getPermissionGrantedAccess(status)
            {
                this.roleAccess = status.split(',');
            },

            getUserRoles()
            {
                let _that =this;
                axios.get('roles',
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                        params : {
                            isAdmin : 1,
                            without_pagination : 1
                        },
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){

                            _that.user_roles = response.data.role_list;

                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },

         

            onLogoFileChange(e) {
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
            },

           

            deleteUploadedFile(index)
            {
                document.getElementById('files').value= "";
                (this.page_files).splice(index, 1);
            },

            deletePreviousUploadedFile(index)
            {
                (this.previous_media_list).splice(index, 1);
                console.log(this.previous_media_list);
            },

            fileUploadChange(e)
            {
                let _that = this;
                const selectedFiles = e.target.files;

                for(var j=0; j<selectedFiles.length; j++){
                _that.page_files.push(selectedFiles[j]);
                }

            },

            onBannerFileChange(e) {
                this.banner_file = e.target.files[0];
                this.banner_url = URL.createObjectURL(this.banner_file);
            },

            checkPageNameExist(pageInfo)
            {
                let _that = this;
                let page_info  =  pageInfo.trim();
                if (page_info == null){
                    $('#pageTitleError').html("*title field is required");
                }
                else if (page_info.length > 2 || pageInfo.length < 100){
                    axios.post('dynamic-page-exists',
                        {
                            title            : this.title,
                        },
                        {
                            headers: {
                                'Authorization': 'Bearer '+localStorage.getItem('authToken')
                            }
                        }).then(function (response) {
                        if(response.data.status_code === 400)
                        {
                            _that.success_message           = "";
                            _that.error_message             = "";
                            $('#pageTitleError').html("*"+response.data.error);

                        }
                    }).catch(function (error) {
                        console.log("Duplicate Name : ",error);
                    });
                }
            },


            checkMenuNameExist(menuId)
            {   
                let _that = this;
              
                if (menuId == null){
                    $('#menuIdError').html("*menu id is required");
                }
                else {
                    axios.post('menu-id-exists',
                        {
                            menu_id            : this.menu_id,
                        },
                        {
                            headers: {
                                'Authorization': 'Bearer '+localStorage.getItem('authToken')
                            }
                        }).then(function (response) {
                        if(response.data.status_code === 400)
                        {
                            _that.success_message           = "";
                            _that.error_message             = "";
                            $('#menuIdError').html("*"+response.data.error);

                        } else {
                             $('#menuIdError').html('');
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            },

            //keyup validation
            checkAndChangeValidation()
            {
                let _that = this;
                if (!_that.title){

                    $('#pageName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#pageTitleError').html("*The page name is required");
                }
                else if (_that.title && (_that.title).length >2 && (_that.title).length <100){
                    $('#pageName').css({
                        'border-color': '#ced4da',
                    });
                    $('#pageTitleError').html("");
                }
                else{
                    $('#pageName').css({
                        'border-color': '#FF7B88',
                    });

                    $('#pageTitleError').html("*The page title must be between 3 to 100 charecter");

                }
            },

            showServerError(errors)
            {
                $('#pageTitleError').html("");
                $('#pageName').css({'border-color': '#ced4da'});
                errors.forEach(val => {
                    if (val.includes("title")==true){
                        $('#pageTitleError').html(val)
                        $('#pageTitleError').css({'border-color': '#FF7B88'});
                    }

                })
            },

            dataValidate()
            {
                let _that = this;

                if (!_that.title){

                    $('#pageName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#pageTitleError').html("*The page title is required");
                }
                else if (_that.title && (_that.title).length >2 && (_that.title).length <100){
                    $('#pageName').css({
                        'border-color': '#ced4da',
                    });
                    $('#pageTitleError').html("");
                    _that.menuUpdate();
                }
                else{
                    $('#pageName').css({
                        'border-color': '#FF7B88',
                    });

                    $('#pageTitleError').html("*The page title must be between 3 to 100 charecter");

                }
            },

            menuUpdate()
            {
                let _that = this;

                let collection;
                let formData = new FormData();


                collection = _that.roleAccess.join();


                for( var i = 0; i < this.page_files.length; i++ ){
                let file = this.page_files[i];

                formData.append('file_name[' + i + ']', file);
                }

                var json_arr = JSON.stringify(this.previous_media_list);
                console.log(json_arr)

                if (!collection) {
                    collection = 1;
                }

                 let enBody      = $('#en_Body_edit').val();

                formData.append('id', _that.page_id);
                formData.append('title', _that.title);
                formData.append('short_summary', _that.short_summary);
                formData.append('en_body', enBody);
                formData.append('status', _that.status);
                formData.append('menu_id', _that.menu_id);
                formData.append('banner_url', _that.banner_file);
                formData.append('previous_file_list', json_arr);
                formData.append('_method', 'PUT');

                axios.post('dynamic-pages/update', formData,
                    {   
                        
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {

                    if (response.data.status_code === 200){
                        _that.isMounted         = false;
                        _that.title              = '',
                        _that.page_id            = '',
                        _that.error_message      = '';
                        _that.success_message    = "Page Updated Successfully";
                        _that.$emit('page-slide-close',_that.success_message);
                    }
                    else if(response.data.status_code === 400)
                    {
                        _that.success_message           = "";
                        _that.error_message             = "";
                        _that.showServerError(response.data.errors);

                    }else{
                        _that.success_message = "";
                        _that.error_message   = "";

                        if ((response.data.error).includes("Parent")){
                            $('#menuParentError').html(  response.data.error );
                            $('#menuParent').css({'border-color': '#FF7B88'});
                        }else {
                            _that.error_message   = response.data.error;
                        }

                    }

                }).catch(function (error) {
                });

            },
            groupByName ({ name }) {
                return `${name}`
            },
            
            


            getMenuList()
            {

                let _that =this;

                axios.get('all-menus',
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                        params :
                            {
                                isAdmin : 1,
                                isRole : _that.userInformation.roles[0].id
                            },

                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                          
                            _that.menuList = response.data.menu_list;
                        }
                        else{
                            _that.success_message = "";
                            _that.error_messages   = response.data.error;
                        }
                    })
            },

            getPageDetails()
            {
                let _that   = this;
                let apiUrl  = "dynamic-pages/";
                axios.get(apiUrl+_that.page_id,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            _that.isMounted        = true;
                            _that.pageDetails      =   response.data.page_info;
                            _that.title            =  _that.pageDetails.title;
                            _that.short_summary    =  _that.pageDetails.short_summary;
                            _that.status           =  _that.pageDetails.status;
                            _that.banner_url       =  _that.pageDetails.banner_img;
                            _that.menu_id          =  _that.pageDetails.menu_id;
                            _that.enBodyData       =  _that.pageDetails.en_body;
                        
                        if ((_that.pageDetails.file).length >=0){
                            (_that.pageDetails.file).forEach( aMedia => {

                              
                                var previousMediaTemp = {};

                                var mediaName = (aMedia.file_name).slice( (aMedia.file_name).indexOf('_') + 1);

                                previousMediaTemp = {
                                id   : aMedia.id,
                                file_name  : aMedia.file_name,
                                name : mediaName
                                };

                                _that.previous_media_list.push(previousMediaTemp);
                               
                            });

                    }
                           


                        } else {
                            _that.success_message           = "";
                            _that.error_message             = response.data.errors;
                        }
                    })
            },

        },

        created(){
            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            if (this.userInformation != ''){
                this.page_id = this.pageId;
                this.getPageDetails(this.page_id);
                this.getMenuList();
                this.getUserRoles();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
