<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">


            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Edit Link</h2>

                <div class="alert-success" v-if="success_message">
                    <span>{{ success_message }}</span>
                </div>

                <div  v-if="error_message">
                    <span class="text-danger">{{ error_message }}</span>
                </div>

                <div class="row">

                    <div class="col-md-12" v-if="linkId ? link_id=linkId : ''">
                        <div class="form-group">
                            <label for="linkTitle">Link Title <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="link_title" id="linkTitle" @keyup="checkAndChangeValidation()" required>
                            <span  id="linkTitleError" class="small text-danger link_title" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="linkUrl">Link Url <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="link_url" id="linkUrl" @keyup="checkAndChangeValidation()" required>
                            <span  id="linkUrlError" class="small text-danger link_url" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="link_status" >
                                <option  value="1">Enable</option>
                                <option  value="0">Disable</option>
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
    import axios from "axios";
    import $ from "jquery";

    export default {

        name: "linkEdit.vue",
        props: ['isEditCheck', 'linkId'],

        components:{
            CustomRoleEdit,
            Multiselect
        },

        data() {
            return {
                link_id          : '',
                menu_parent_id   : '',
                link_title       : '',
                link_url         : '',
                link_status      : '',
                linkDetails      : '',
                tempmenuList     : '',
                menuList         : [],
                error_message    : '',
                success_message  : '',
                token            : '',
                user_roles : '',
                roleAccess          : [],
                group_value         : [],
                group_list          : [],
                menuHasParent : false,

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

                            // console.log(response);

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

            //keyup validation
            checkAndChangeValidation()
            {
                let _that = this;
                if (!_that.link_title){

                    $('#linkTitle').css({
                        'border-color': '#FF7B88',
                    });
                    $('#linkTitleError').html("*The title is required");
                }
                else {
                    $('#linkTitle').css({
                        'border-color': '#ced4da',
                    });
                    $('#linkTitleError').html("");
                }


                if (!_that.link_url){

                    $('#linkUrl').css({
                        'border-color': '#FF7B88',
                    });
                    $('#linkUrlError').html("*The url is required");
                }
                else{
                    $('#linkUrl').css({
                        'border-color': '#ced4da',
                    });
                    $('#linkUrlError').html("");
                }
            },

            showServerError(errors)
            {
                $('#linkTitleError').html("");
                $('#linkTitle').css({'border-color': '#ced4da'});
                errors.forEach(val => {
                    if (val.includes("title")==true){
                        $('#linkTitleError').html(val)
                        $('#linkTitle').css({'border-color': '#FF7B88'});
                    }
                })
            },

            dataValidate()
            {
                let _that = this;

                if (!_that.link_title){

                    $('#linkTitle').css({
                        'border-color': '#FF7B88',
                    });
                    $('#linkTitleError').html("*The title is required");
                } 
                else if (!_that.link_url){

                    $('#linkUrl').css({
                        'border-color': '#FF7B88',
                    });
                    $('#linkUrlError').html("*The url is required");
                }
                else{
                    _that.linkUpdate();
                }
            },

            linkUpdate()
            {
                let _that = this;

                let collection;

                collection = _that.roleAccess.join();

                if (!collection) {
                    collection = 1;
                }

                let formData = new FormData();

                formData.append('id', _that.link_id);
                formData.append('title', _that.link_title);
                formData.append('url', _that.link_url);
                formData.append('status', _that.link_status);



                axios.put('links/update',
                {
                    id                 : _that.link_id,
                    title              : _that.link_title,
                    url                : _that.link_url,
                    status             : _that.link_status,
                },
                    {   
                        
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {

                    if (response.data.status_code === 200){
                        _that.link_title           = '',
                        _that.link_url             = '',
                        _that.error_message        = '';
                        _that.success_message      = "Link Updated Successfully";
                        _that.$emit('link-slide-close',_that.success_message);
                    }
                    else if(response.data.status_code === 400)
                    {
                        _that.success_message           = "";
                        _that.error_message             = "";
                        _that.showServerError(response.data.errors);

                    }else{
                        _that.success_message = "";
                        _that.error_message   = "";

                    }

                }).catch(function (error) {
                });

            },
            groupByName ({ name }) {
                return `${name}`
            },
            

            getLinkDetails()
            {
                let _that   = this;
                let apiUrl  = "links/";
                axios.get(apiUrl+_that.link_id,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            console.log(response.data.link_info);
                            _that.linkDetails         =  response.data.link_info;
                            _that.link_title           =  _that.linkDetails.title;
                            _that.link_url           =  _that.linkDetails.url;
                            _that.link_status           =  _that.linkDetails.status;


                        } else {
                            _that.success_message           = "";
                            _that.error_message            = response.data.errors;
                        }
                    })
            },

        },

        created(){
            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            if (this.userInformation != ''){
                this.link_id = this.linkId;
                this.getLinkDetails(this.link_id);
                this.getUserRoles();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
