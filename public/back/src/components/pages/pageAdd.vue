<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Page</h2>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="pageName">Page Title <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="pageData.title" id="pageTitle" @change="checkPageNameExist(pageData.title)" @keyup="checkAndChangeValidation(pageData.title,'#pageName', '#pageTitleError', '*page name')" placeholder="Enter Page Title" required>
                            <span id="pageTitleError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select A Menu<span class="required">*</span></label>
                            <select id="menuId" class="form-control" v-model="pageData.menu_id"  @change="checkMenuNameExist(pageData.menu_id)" required>
                                <option disabled value="">Select a page</option>
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
                            <textarea class="form-control" type="text" v-model="pageData.short_summary" id="shortSummary" placeholder="Short Desription" required> </textarea>
                            <span id="shortSummaryError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label >Body Content</label>
                        <Summernote  v-model="pageData.en_body" :idFromParent="enBody"></Summernote>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Banner Image</label>
                            <input type="file" id="banner_iamge" ref="files" @change="onBannerFileChange" accept="image/*">
                           <p><small class="text-danger ">Recommended (1800 x 400)px</small></p>
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
                                    :key="index">
                                    <a :href="file.url" class="font-12 text-body d-flex justify-content-between align-items-center">
                                        {{ file.name }}
                                        <span class="close-btn btn btn-danger btn-sm" @click="deleteUploadedFile(index)"><i class="fa fa-trash" aria-hidden="true"></i></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="pageData.status" >
                                <option  value="1">Active</option>
                                <option  value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-md-12 mt-10">
                    <div class="form-group text-right">
                        <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Add</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import CustomRoleAdd from './../custom-role/CustomRoleAdd';
    import Summernote from "@/components/summer-note/summernote";
    import axios from 'axios';
    import $ from "jquery";
    export default {

        name        : "pageAdd.vue",
        props       : ['isAddCheck'],
        components  : {
            CustomRoleAdd,
            Summernote,
            Multiselect
        },
        data() {
            return {
                success_message             : '',
                error_messages              : [],
                token                       : '',
                menuList                    : '',
                selectedpage                : '',
                userInfo                    : '',
                enBody                      : "en_Body",
                logo_file                   : '',
                logo_url                    : '',
                banner_file                 : '',
                banner_url                  : '',
                user_roles                  : '',
                roleAccess                  : [],
                page_files                  : [],
                parent_role_id              : '',
                categoryHasParent           : false,
                userInformation             : '',

                pageData                    : {
                    title                   : '',
                    short_summary           : '',
                    menu_id                 : '',
                    en_body                 : '',
                    status                  : '1',
                },
            validation_error             : {
                    ispageTitleStatus    : false,
                    ispageMenuIdStatus   : false,
                    isGroupError         : true,
                },
            }
        },
        methods: {
            validateAndSubmit()
            {
                console.log("Validation errors", this.validation_error)
                if (!this.pageData.title){
                    $('#pageTitleError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#pageTitleError').html("*page title field is required");
                    this.validation_error.ispageTitleStatus = false;
                }

                if (!this.pageData.menu_id){
                    $('#menuIdError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#menuIdError').html("*menu id is required");
                    this.validation_error.ispageMenuIdStatus = false;
                }

                if (this.validation_error.ispageTitleStatus === false &&  this.validation_error.ispageMenuIdStatus === false){
                    this.pageAdd();
                }

            },
           
            groupByName ({ name }) {
                return `${name}`
            },

          
            onLogoFileChange(e)
            {
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
            },


            onMediaFileChange(e) {
                this.checkFileValid();
                console.log(event.target.files);
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
                console.log(this.logo_url);
            },


            onBannerFileChange(e)
            {
                this.banner_file = e.target.files[0];
                this.banner_url = URL.createObjectURL(this.banner_file);
            },


            deleteUploadedFile(index)
            {
                document.getElementById('files').value= "";
                (this.page_files).splice(index, 1);
            },


            fileUploadChange(e)
            {
                let _that = this;
                const selectedFiles = e.target.files;

                for(var j=0; j<selectedFiles.length; j++){
                    console.log(selectedFiles[j]);
                    _that.page_files.push(selectedFiles[j]);
                }

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
                            title            : this.pageData.title,
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
                            menu_id            : this.pageData.menu_id,
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
            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                        if (selected_name === "*page title"){
                            this.validation_error.ispageTitleStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*page title" ){
                            this.validation_error.ispageTitleStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*page title" ){
                        this.validation_error.ispageTitleStatus = false;
                    }
                }
            },
            //server validation
            showServerError(errors)
            {
                $('#pageTitleError').html("");
                $('#pageTitle').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("title")==true){
                        $('#pageTitleError').html(val)
                        $('#pageTitle').css({'border-color': '#FF7B88'});
                    }

                    if (val.includes("menu id")==true){
                        $('#menuIdError').html(val)
                        $('#menuId').css({'border-color': '#FF7B88'});
                    }
                })

                
            },

            //category add
            pageAdd()
            {

                let _that = this;
                _that.pageData.en_body      = $('#en_Body').val();
    
                let formData = new FormData();


                for( var i = 0; i < this.page_files.length; i++ ){
                    let file = this.page_files[i];

                    formData.append('file_name[' + i + ']', file);
                }

                if (_that.roleAccess.length == 0){
                    _that.roleAccess.push(1);
                }
               
                formData.append('title', _that.pageData.title);
                formData.append('menu_id', _that.pageData.menu_id);
                formData.append('status', _that.pageData.status);
                formData.append('short_summary', _that.pageData.short_summary);
                formData.append('en_body', _that.pageData.en_body);
                formData.append('banner_img', this.banner_file);


                axios.post('dynamic-pages', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200){

                        _that.pageData          = '';
                        _that.selectedpage      = '';
                        _that.error_messages    = '';
                        _that.success_message   = "Page Added Successfully";

                        _that.$emit('page-slide-close', _that.success_message);
                    }else if(response.data.status_code === 400){

                        _that.success_message       = "";
                        _that.error_message         = "";
                        _that.showServerError(response.data.errors);
                    }else{

                        _that.success_message       = "";
                        _that.error_messages         = response.data.message;
                    }
                }).catch(errors => console.log(errors));

            },
            //category list
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
        },
        created()
        {
            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            if (this.userInformation != ''){
                this.getMenuList();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
