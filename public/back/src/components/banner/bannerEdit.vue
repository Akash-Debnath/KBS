<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">




            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Edit Banner</h2>

                <div class="alert-success" v-if="success_message">
                    <span>{{ success_message }}</span>
                </div>

                <div  v-if="error_message">
                    <span class="text-danger">{{ error_message }}</span>
                </div>

                <div class="row">

                    <div class="col-md-12" v-if="categoryId ? banner_id=categoryId : ''">

                        <div class="form-group">
                            <label for="bannerTitle">Title <span class="required">*</span></label>
                            <input hidden class="form-control" type="text" v-model="aBanner.id" required>
                            <input  @keyup="checkAndChangeValidation(aBanner.title, '#bannerTitle', '#bannerTitleError', '*title')" class="form-control" type="text" v-model="aBanner.title" id="bannerTitle" required>
                            <span  id="bannerTitleError" class="small text-danger" role="alert"></span>
                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Image or Video</label>
                            <input accept="audio/*,video/*,image/*" type="file" id="files" ref="files" @change="onMediaFileChange" >
                            <div class="p-0 m-0">
                                <span id="fileError" class="small text-danger banner_name" role="alert"></span>
                            </div>
                        </div>
                    </div>

<!--                    previous media-->
                    <div class="col-md-12" v-if="!logo_url">
                        <div class="form-group"  v-for="a_url in aBanner.media" :key="a_url.id">
                            <img v-if="a_url.url.match(/\.(jpeg|jpg|gif|png)$/) != null" class="preview" style="height:250px; width: auto" :src="a_url.url"/>

                            <video v-else class="preview" controls>
                                <source :src="a_url.url" type="video/mp4">
                                <source :src="a_url.url" type="video/ogg">
                            </video>
                        </div>
                    </div>

<!--                    new media-->
                    <div class="col-md-12" v-else>
                        <div class="form-group" >
                            <img v-if="logo_file.type != 'video/mp4' && logo_file.type != 'audio/ogg'" class="preview" style="height:250px; width: auto" :src="logo_url"/>

                            <video class="preview" v-else controls>-->
                                <source :src="logo_url" type="video/mp4">
                                <source :src="logo_url" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label>Roles <span class="required">*</span><span id="roleIdError" class="text-danger small"></span></label>


<!--                            <ul class="list-unstyled permission-list m-0 p-0">-->
<!--                                <li v-for="a_user in user_roles" :key="a_user.id" class="text-left pb-2">-->
<!--                                    <div v-if="roleAccess.includes(a_user.id)" class="d-flex align-items-center">-->
<!--                                        <label class="mb-0 ml-2">-->
<!--                                            <input v-if="a_user.id==1" disabled checked type="checkbox" :value="a_user.id"  v-model="roleAccess">-->
<!--                                            <input v-else @click="checkRoleData(a_user.id)"  checked type="checkbox" :value="a_user.id"  v-model="roleAccess">-->
<!--                                            {{ a_user.name }}-->
<!--                                        </label>-->
<!--                                    </div>-->
<!--                                    <div v-else class="d-flex align-items-center">-->
<!--                                        <label class="mb-0 ml-2"><input @click="checkRoleData(a_user.id)" type="checkbox" v-model="roleAccess" :value="a_user.id"> {{ a_user.name }} </label>-->
<!--                                    </div>-->

<!--                                </li>-->
<!--                            </ul>-->

                            <CustomRoleEdit v-if="user_roles && aBanner" :userRoles="user_roles" :banneRoleAccess="aBanner.role_id" @granted-roles="getPermissionGrantedAccess"/>
                        </div>
                    </div>

                </div>

                <div class="form-group text-right">
                    <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Update</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

    import CustomRoleEdit from './../custom-role/CustomRoleEdit'

    import axios from "axios";
    import $ from "jquery";

    export default {

        name: "bannerEdit.vue",
        props: ['isEditCheck', 'categoryId'],

        components:{
            CustomRoleEdit
        },

        data() {
            return {
                banner_id          : '',
                category_parent_id   : '',
                category_name        : '',
                aBanner : '',
                tempCategoryList     : '',
                categoryList         : [],
                error_message        : '',
                success_message      : '',
                token                : '',

                logo_file        : '',
                logo_url         : '',
                user_roles          : '',
                roleAccess : [],

                validation_error : {
                    isTitleStatus       : true,
                    isRoleStatus        : true,
                },
            }
        },

        methods: {
            getPermissionGrantedAccess(status){
                console.log(status)
                this.roleAccess = status.split(',');
            },
            checkFileValid(){
                let file = $('#files')[0].files[0];

                if (!file) {
                    $('#fileError').html("*choose a file first");
                    this.validation_error.isFileStatus = false;

                }
                // 31457273
                // 15728639
                else if (file.size > 31457273 ){
                    $('#fileError').html("*file size should be less 30MB");
                    this.validation_error.isFileStatus = false;
                }

                else{
                    this.validation_error.isFileStatus = true;
                }
            },

            validateAndSubmit(){

                // this.checkFileValid();

                if (this.roleAccess.length > 0){
                    $('#roleIdError').html("");
                }




                if (!this.aBanner.title){
                    $('#bannerTitle').css({
                        'border-color': '#FF7B88',
                    });
                    $('#bannerTitleError').html("*title field is required");
                }


                if (this.roleAccess.length == 0){
                    $('#roleIdError').html("role field is required");
                }

                if (this.validation_error.isTitleStatus    === true){
                    this.bannerUpdate();
                }
            },

            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {

                if (selected_data.length >0) {
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");
                        if (selected_name === "*title"){
                            this.validation_error.isTitleStatus = false;
                        }
                    }
                    else if (selected_data.length > 200){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain maximum 200 character");
                        if (selected_name === "*title"){
                            this.validation_error.isTitleStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*title" ){
                            this.validation_error.isTitleStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "title" ){
                        this.validation_error.isTitleStatus = false;
                    }
                }
            },

            checkRoleData(role_id){
                if (this.roleAccess.includes(role_id)){
                    // removeItem(role_id, this.roleAccess);
                    let index = this.roleAccess.indexOf(role_id);
                    if (index !== -1) {
                        this.roleAccess.splice(index, 1);
                        console.log(this.roleAccess)
                    }
                }else {
                    this.roleAccess.push(role_id);
                    console.log(this.roleAccess)
                }
            },

            onMediaFileChange(e) {
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
            },


            showServerError(errors)
            {
                $('#bannerTitleError').html("");
                $('#roleIdError').html("");
                $('#fileError').html("");

                $('#bannerTitle').css({'border-color': '#ced4da'});

                errors.forEach(val=>{
                    console.log(val);
                    if (val.includes("title")==true){
                        $('#bannerTitleError').html(val)
                        $('#bannerTitle').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("role id")==true){
                        $('#roleIdError').html(val)
                    }
                    else if (val.includes("banner file")==true){
                        $('#fileError').html(val)
                    }
                })
            },


            bannerUpdate()
            {
                let _that = this;
                let collection;

                // console.log(_that.roleAccess);

                collection = _that.roleAccess.join();

                let formData = new FormData();

                // alert(_that.aBanner.media[0].id);

                formData.append('banner_file', _that.logo_file);
                formData.append('id', _that.aBanner.id);
                formData.append('title', _that.aBanner.title);
                formData.append('role_id', collection);
                formData.append('status', _that.aBanner.status);
                formData.append("_method", "put");
                formData.append("mediable_id", _that.aBanner.id);


                collection = _that.roleAccess.join();
                axios.post('banners/update',formData
                    ,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken'),
                            'Content-Type'  : 'application/x-www-form-urlencoded',
                        }
                    }).then(function (response) {
                    console.log(response);

                    if (response.data.status_code === 200){
                        _that.aBanner = '';
                        _that.success_message         = "Banner Updated Successfully";
                        _that.$emit('banner-slide-close',_that.success_message);
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
                            $('#categoryParentError').html(  response.data.error );
                            $('#categoryParent').css({'border-color': '#FF7B88'});
                        }else {
                            _that.error_message   = response.data.error;
                        }
                    }
                }).catch(function (error) {
                    console.log(error);
                });

            },

            getBannerDetails()
            {
                let _that   = this;
                let apiUrl  = "banners/";
                axios.get(apiUrl+_that.banner_id,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {

                            _that.aBanner         = response.data.banner_info;

                            // console.log(_that.aBanner);

                            if ((_that.aBanner.role_id).includes(',')) {

                                _that.roleAccess        = (_that.aBanner.role_id).split(',');
                                _that.roleAccess        = _that.roleAccess.map(i=>Number(i))

                                // console.log(_that.roleAccess);
                            }else{
                                _that.roleAccess.push(_that.aBanner.role_id);
                            }
                        } else {
                            _that.success_message           = "";
                            _that.error_message            = response.data.errors;
                        }
                    })
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
                            // console.log(_that.user_roles)

                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },

        },

        created(){
            this.banner_id = this.categoryId;
            this.getBannerDetails(this.banner_id);
            this.getUserRoles();
        }
    }
</script>

<style scoped>

</style>
