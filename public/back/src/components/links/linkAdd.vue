<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Link</h2>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Title<span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="linkData.title" id="linkTitle"  @keyup="checkAndChangeValidation(linkData.title,'#linkTitle', '#linkTitleError', '*title')" placeholder="Enter Link Title" required>
                            <span id="linkTitleError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="linkUrl">Link Url <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="linkData.url" id="linkUrl"  @keyup="checkAndChangeValidation(linkData.url,'#linkUrl', '#linkUrlError', '*link url')" placeholder="Enter Link Url" required>
                            <span id="linkUrlError" class="text-danger small"></span>
                        </div>
                    </div>
                    

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="linkData.status" >
                                <option  value="1">Enable</option>
                                <option  value="0">Disable</option>
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
    import Multiselect from 'vue-multiselect'
    import CustomRoleAdd from './../custom-role/CustomRoleAdd'
    import axios from 'axios'
    import $ from "jquery";
    export default {

        name        : "linkAdd.vue",
        props       : ['isAddCheck'],
        components  : {
            CustomRoleAdd,
            Multiselect
        },
        data() {
            return {
                success_message             : '',
                error_messages              : [],
                token                       : '',
                linkList                    : '',
                userInfo                    : '',
                logo_file                   : '',
                logo_url                    : '',
                user_roles                  : '',
                roleAccess                  : [],
                userInformation             : '',

                linkData : {
                    title                  : '',
                    url                    : '',
                    status                 : '1',
                },
                validation_error    : {
                    islinkUrlStatus    : false,
                    islinkTitleStatus  : false,
                },
            }
        },
        methods: {
            validateAndSubmit()
            {
                if (!this.linkData.url){
                    $('#linkUrlError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#linkUrlError').html("*link url field is required");
                    this.validation_error.islinkUrlStatus = false;
                }



                if (!this.linkData.title){
                    $('#linkTitleError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#linkTitleError').html("*title field is required");
                    this.validation_error.islinkTitleStatus = false;
                }

                if ( this.validation_error.islinkTitleStatus === true){
                    this.linkAdd();
                }

            },
           
            groupByName ({ name }) {
                return `${name}`
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

                            console.log(response);

                            _that.user_roles = response.data.role_list;

                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },
            getPermissionGrantedAccess(status)
            {
                this.roleAccess = status.split(',');
                console.log(this.roleAccess)
            },

   
            onLogoFileChange(e)
            {
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
            },
           
            //keyup validation
            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    $(selected_id).css({
                        'border-color': '#ced4da',
                    });
                    $(selected_error_id).html("");

                    if (selected_name === "*link url" ){
                        this.validation_error.islinkUrlStatus = true;
                    }

                } else {
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*link url" ){
                        this.validation_error.islinkUrlStatus = false;
                    }
                }



                if (selected_data.length >0) {
                    if (selected_name === "*title" ){
                        this.validation_error.islinkTitleStatus = true;
                    }
                    

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*title" ){
                        this.validation_error.islinktitleStatus = false;
                    }
                }
            },
            //server validation
            showServerError(errors)
            {

                $('#linkTitleError').html("");
                $('#linkTitle').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("title")==true){
                        $('#linkTitleError').html(val)
                        $('#linkTitle').css({'border-color': '#FF7B88'});
                    }
                })
            },

            //category add
            linkAdd()
            {
                console.log('i am from add', this.linkData);
    
                let _that = this;
                let formData = new FormData();

                formData.append('title', _that.linkData.title);
                formData.append('url', _that.linkData.url);
                formData.append('status', _that.linkData.status);

                formData.append('role_id', this.roleAccess);

                axios.post('links', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200){

                        _that.linkData          = '';
                        _that.error_messages    = '';
                        _that.success_message   = "Link Added Successfully";

                        _that.$emit('link-slide-close', _that.success_message);
                    }else if(response.data.status_code === 400){

                        _that.success_message       = "";
                        _that.error_message         = "";
                        _that.showServerError(response.data.errors);
                    }else{

                        _that.success_message       = "";
                        _that.error_message         = response.data.message;
                    }
                }).catch(errors => console.log(errors));

            },
            //category list
            getLinkList()
            {

                let _that =this;

                axios.get('links',
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
                            _that.linkList = response.data.link_list;
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
                this.getLinkList();
                this.getUserRoles();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
