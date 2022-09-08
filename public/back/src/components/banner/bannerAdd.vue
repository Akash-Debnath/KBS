<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Banner</h2>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="bannerTitle">Banner Title <span class="required">*</span></label>
                            <input @keyup="checkAndChangeValidation(bannerData.title, '#bannerTitle', '#bannerTitleError', '*title')" class="form-control" type="text" v-model="bannerData.title" id="bannerTitle" placeholder="Enter Banner Title" required>
                            <span id="bannerTitleError" class="small text-danger banner_name" role="alert"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Image or Video</label>
                            <input type="file" id="files" accept="audio/*,video/*,image/*" required refs="files" @change="onMediaFileChange" >
                            <div class="m-0 p-0">
                                <span id="fileError" class="small text-danger banner_name" role="alert"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" v-if="logo_url">
                        <div class="form-group" >
                            <img v-if="logo_file.type != 'video/mp4' && logo_file.type != 'audio/ogg' && logo_file.type != 'video/quicktime'" class="preview" style="height:250px; width: auto" :src="logo_url"/>

                            <video class="preview" v-else controls>-->
                                <source :src="logo_url" type="video/mp4">
                                <source :src="logo_url" type="video/ogg">
                                <source :src="logo_url" type="video/quicktime">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group mb-0">
                            <label>Roles <span class="required">*</span><span id="roleIdError" class="text-danger small"></span></label>
<!--                            <ul class="list-unstyled permission-list m-0 p-0">-->

<!--                                <li class="text-left pb-2">-->
<!--                                    <label class="pl-2 mb-0"><input class="check-role-all" type="checkbox" v-model="roleAccess" :value="0" @click="allCheck()"> All </label>-->
<!--                                </li>-->

<!--                                <li v-for="a_user in user_roles" :key="a_user.id" class="text-left pb-2">-->
<!--                                    <label  v-if="a_user.id==1" class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="roleAccess.checked" :value="a_user.id"  checked disabled> {{ a_user.name }} </label>-->

<!--                                    <label v-else class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="roleAccess" :value="a_user.id"> {{ a_user.name }} </label>-->
<!--                                    &lt;!&ndash;                                    <label class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id" :value="a_user.id" v-bind:id="a_user.id" > {{ a_user.name }} </label>&ndash;&gt;-->
<!--                                </li>-->
<!--                            </ul>-->
                            <CustomRoleAdd v-if="user_roles" :userRoles="user_roles" @granted-roles="getPermissionGrantedAccess"/>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="fileStatus">Status<span class="required">*</span></label>
                            <select id="fileStatus" class="form-control" v-model="bannerData.status">
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Add</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import CustomRoleAdd from '../custom-role/CustomRoleAdd'
    import axios from 'axios'
    import $ from "jquery";

    export default {

        name: "bannerAdd",
        components:{
            CustomRoleAdd
        },
        props: ['isAddCheck'],
        data() {
            return {
                success_message             : '',
                error_messages              : [],
                bannerData          : {
                    title                   : '',
                    status                  : 1
                },
                logo_file                   : '',
                logo_url                    : '',
                user_roles                  : '',
                roleAccess                  : [],
                validation_error    : {
                    isTitleStatus           : false,
                    isRoleStatus            : false,
                    isFileStatus            :false,
                },
            }
        },
        methods: {
            getPermissionGrantedAccess(status)
            {
                this.roleAccess = status.split(',');
            },
            checkFileValid()
            {
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
                    $('#fileError').html("");
                }
            },

            validateAndSubmit(){
                if (this.roleAccess.length > 0){
                    $('#roleIdError').html("");
                }

                if (!this.bannerData.title){
                    $('#bannerTitle').css({
                        'border-color': '#FF7B88',
                    });
                    $('#bannerTitleError').html("*title field is required");
                }

                this.checkFileValid();


                // if (this.roleAccess.length == 0){
                //     $('#roleIdError').html("role field is required");
                // }

                if (this.validation_error.isTitleStatus    === true &&
                    this.validation_error.isFileStatus    === true){
                    this.bannerAdd();
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
                    // else if (val.includes("role id")==true){
                    //     $('#roleIdError').html(val)
                    // }

                    else if (val.includes("banner file")==true){
                        $('#fileError').html(val)
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

                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },

            onMediaFileChange(e) {
                this.checkFileValid();
                console.log(event.target.files);
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
                console.log(this.logo_url);
            },


            //category add
            bannerAdd(){

                let _that = this;

                if (_that.roleAccess.length ==0){
                    _that.roleAccess.push(1);
                }
                // console.log(_that.roleAccess);
                let formData = new FormData();

                formData.append('banner_file', _that.logo_file);
                formData.append('title', _that.bannerData.title);
                formData.append('role_id', _that.roleAccess);
                formData.append('status', _that.bannerData.status);

                // console.log(_that.roleAccess);

                console.log(_that.logo_file)

                if (_that.logo_file !=''){
                    axios.post('banners', formData,
                        {
                            headers: {
                                'Content-Type'  : 'multipart/form-data',
                                'Authorization': 'Bearer '+localStorage.getItem('authToken')
                            }
                        }).then(function (response) {
                        console.log(response)
                        if (response.data.status_code === 200){

                            _that.bannerData          = '';
                            _that.error_messages        = '';
                            _that.success_message       = "Banner Added Successfully";
                            _that.$emit('banner-slide-close', _that.success_message);
                        }
                        else if(response.data.status_code === 400){
                            _that.success_message       = "";
                            _that.error_message         = "";
                            _that.showServerError(response.data.errors);

                        }
                        else{
                            _that.success_message       = "";
                            _that.error_message         = response.data.message;
                        }
                    }).catch(errors => console.log(errors));
                } else{
                    $('#fileError').html('*file not found!')
                }

            },
        },

        created(){
            this.getUserRoles();
        }
    }
</script>

<style scoped>

</style>
