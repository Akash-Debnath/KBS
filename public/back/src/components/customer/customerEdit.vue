<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Customer Edit</h2>

                <div class="row" v-if="userData">
                    <div class="col-md-12">
                        <div v-if="success_message" class="alert alert-success" role="alert">
                            {{ success_message }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="userData.first_name" id="firstName" @keyup="checkAndChangeValidation(userData.first_name, '#firstName', '#firstNameError', '*first name')" placeholder="Enter first name here!!" required>
                            <span id="firstNameError" class="text-danger small"> </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="userData.last_name" id="lastName" @keyup="checkAndChangeValidation(userData.last_name, '#lastName', '#lastNameError', '*last name')" placeholder="Enter last name here!!" required>
                            <span id="lastNameError" class="text-danger small"> </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="userName">Username <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="userData.username" id="userName" @keyup="checkAndChangeValidation(userData.username, '#userName', '#userNameError', '*user name')" @change="checkUserNameExist(userData.username)" placeholder="Enter username here!!" required>
                            <span id="userNameError" class="text-danger small"> </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input class="form-control" type="email" v-model="userData.email" id="email" @keyup="checkAndValidateEmail()" @change="checkUserEmailExist(userData.email)" placeholder="Enter last name here!!" required>
                            <span id="emailError" class="text-danger small"> </span>
                        </div>
                    </div>



                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="roles">Roles <span class="required">*</span></label>
                            <select class="form-control" v-model="roles" id="roles">
                                <option value="" disabled>Select A Role</option>
                                <option v-for="a_role in userRoles" :key="a_role.id" :value="a_role.id">{{a_role.name}}</option>
                            </select>
                            <span id="rolesError" class="text-danger small"> </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="roles">Status<span class="required">*</span></label>
                            <select class="form-control" v-model="userData.status">
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Image</label>
                            <input type="file" id="files" ref="files" @change="onLogoFileChange" accept="image/*">
                        </div>
                    </div>

                    <div class="col-md-12" v-if="logo_url">
                        <div class="form-group" >
                            <img class="preview" style="height:250px; width: auto" :src="logo_url"/>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="form-group text-left">
                            <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import axios from "axios";

    export default {

        name: "customerEdit.vue",
        components: {
        },
        props: ['isEditCheck', 'customerId'],

        data() {
            return {
                isMounted           : false,
                success_message     : '',
                error_messages      : [],
                token               : '',
                selectedId          : '',
                userData            : '',
                roles               :'',
                userRoles           :'',

                filter      : {
                    isAdmin         : 1
                },

                validation_error :      {
                    isFirstNameStatus           : true,
                    isLastNameStatus            : true,
                    isUserNameStatus            : true,
                    isEmailStatus               : true,
                    isRoleStatus                : true,
                    isPasswordStatus            : true,
                    isConfirmationStatus        : true,
                } ,

                logo_file               : '',
                logo_url                : '',
            }
        },

        methods: {

            onLogoFileChange(e)
            {
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
            },

            checkUserEmailExist(userInfo)
            {
                let _that = this;
                let user_info  =  userInfo.trim();
                if (user_info == null){
                    $('#userEmailError').html("*email field is required");
                }
                else if (user_info.length > 2 || user_info.length < 100){
                    axios.post('user/email',
                        {
                            email            : this.userData.email,
                        },
                        {
                            headers: {
                                'Authorization': 'Bearer '+localStorage.getItem('authToken')
                            }
                        }).then(function (response) {
                        console.log(response.data)
                        if(response.data.status_code === 400)
                        {
                            _that.success_message           = "";
                            _that.error_message             = "";
                            $('#emailError').html("*"+response.data.error);

                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            },

            checkUserNameExist(userInfo){
                let _that = this;
                let user_info  =  userInfo.trim();
                if (user_info == null){
                    $('#userNameError').html("*username field is required");
                }
                else if (user_info.length > 2 || user_info.length < 100){
                    axios.post('user/username',
                        {
                            id                  : this.userData.id,
                            username            : this.userData.username,
                        },
                        {
                            headers: {
                                'Authorization': 'Bearer '+localStorage.getItem('authToken')
                            }
                        }).then(function (response) {
                        console.log(response)
                        if(response.data.status_code === 400)
                        {
                            _that.success_message           = "";
                            _that.error_message             = "";
                            $('#userNameError').html("*"+response.data.error);

                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            },
            //update button pressed
            validateAndSubmit()
            {
                if (!this.userData.first_name){
                    $('#firstName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#firstNameError').html("*first name field is required");
                }
                if (!this.userData.last_name){

                    $('#lastName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#lastNameError').html("*last name field is required");
                }
                if (!this.userData.username){

                    $('#userName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#userNameError').html("*user name field is required");
                }
                if (!this.userData.email){

                    $('#email').css({
                        'border-color': '#FF7B88',
                    });
                    $('#emailError').html("*email field is required");
                }
                if (!this.userData.password){

                    $('#password').css({
                        'border-color': '#FF7B88',
                    });
                    $('#passwordError').html("*password field is required");
                }
                if (!this.userData.confirm_password){

                    $('#confirmPassword').css({
                        'border-color': '#FF7B88',
                    });
                    $('#confirmPasswordError').css({'color': '#FF7B88'});

                    $('#confirmPasswordError').html("*confirm password field is required");
                }
                if (!this.userData.roles){

                    $('#roles').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rolesError').html("*roles field is required");
                }
                if (this.validation_error.isFirstNameStatus === true &&
                    this.validation_error.isLastNameStatus === true &&
                    this.validation_error.isUserNameStatus === true &&
                    this.validation_error.isEmailStatus === true &&
                    this.validation_error.isRoleStatus === true){
                    console.log(this.validation_error)
                    this.userUpdate();
                }
            },
            //check validation on key up
            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                        if (selected_name === "*first name" ){
                            this.validation_error.isFirstNameStatus = false;
                        }else if (selected_name === "*last name"){
                            this.validation_error.isLastNameStatus = false
                        }else if(selected_name === "*user name"){
                            this.validation_error.isUserNameStatus = false;
                        }

                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*first name" ){
                            this.validation_error.isFirstNameStatus = true;
                        }else if (selected_name === "*last name"){
                            this.validation_error.isLastNameStatus = true
                        }else if(selected_name === "*user name"){
                            this.validation_error.isUserNameStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")
                    if (selected_name === "*first name" ){
                        this.validation_error.isFirstNameStatus = false;
                    }else if (selected_name === "*last name"){
                        this.validation_error.isLastNameStatus = false
                    }else if(selected_name === "*user name"){
                        this.validation_error.isUserNameStatus = false;
                    }
                }
            },

            checkAndValidateEmail()
            {
                if ((this.userData.email).length >0) {
                    if (!this.validEmail(this.userData.email)) {
                        $('#email').css({
                            'border-color': '#FF7B88',
                        });

                        $('#emailError').html("*Valid email required");
                        this.validation_error.isEmailStatus = false;

                    } else {
                        $('#email').css({
                            'border-color': '#ced4da',
                        });
                        $('#emailError').html("");
                        this.validation_error.isEmailStatus = true;
                    }

                } else{
                    $('#email').css({
                        'border-color': '#FF7B88',
                    });
                    $('#emailError').html("*email field is required");
                    this.validation_error.isEmailStatus = false;
                }
            },

            validEmail (email)
            {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },

            //server check user data
            showServerError(errors)
            {
                // span color css
                $('#lastNameError').html("");
                $('#firstNameError').html("");
                $('#userNameError').html("");
                $('#emailError').html("");
                $('#rolesError').html("");
                // border color css
                $('#firstName').css({'border-color': '#ced4da'});
                $('#lastName').css({'border-color': '#ced4da'});
                $('#userName').css({'border-color': '#ced4da'});
                $('#email').css({'border-color': '#ced4da'});
                $('#roles').css({'border-color': '#ced4da'});
                //error array check
                errors.forEach(val=>{
                    if (val.includes("first")==true){
                        $('#firstNameError').html(val)
                        $('#firstName').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("last")==true){
                        $('#lastNameError').html(val)
                        $('#lastName').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("username")==true){
                        $('#userNameError').html(val)
                        $('#userName').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("email")==true){
                        $('#emailError').html(val)
                        $('#email').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("roles")==true){
                        $('#rolesError').html(val)
                        $('#roles').css({'border-color': '#FF7B88'});
                    }
                })
            },
            //user data upload to server after validation
            userUpdate() {
                let _that       = this;
                let userID      = _that.selectedId;

                let formData = new FormData();
                formData.append('id', userID);
                formData.append('avatar', this.logo_file);
                formData.append('username', this.userData.username);
                formData.append('first_name', this.userData.first_name);
                formData.append('last_name', this.userData.last_name);
                formData.append('email', this.userData.email);
                // formData.append('roles', this.userData.roles);
                formData.append('roles', _that.roles);
                formData.append('status', this.userData.status);
                formData.append('_method', 'PUT');

                // console.log(_that.roles)

                axios.post('users/update', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code == 200)
                    {
                        _that.userData                  = '';
                        _that.error_message             = '';
                        _that.success_message           = "User Updated Successfully";

                        _that.$emit('user-slide-close', _that.success_message);
                    }
                    else
                    {
                        _that.success_message           = "";
                        _that.showServerError(response.data.errors);
                    }

                }).catch(function (error) {
                    console.log(error);
                });

            },
            //find user data
            getuserData()
            {
                let _that       = this;
                let userID      = this.selectedId;
                axios.get("users/"+userID,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            // console.log(response.data)
                            _that.userData          = response.data.user_info;
                            _that.logo_url                =  _that.userData.media.url;
                            if ((response.data.user_info.roles).length > 0)
                                _that.roles         = response.data.user_info.roles[0].id;
                        } else {
                            _that.success_message   = "";
                            _that.error_messages    = response.data.error;
                        }
                    })
            },
            getUserRoles()
            {
                let _that   = this;
                axios.get('roles',
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        },
                        params : {
                            isAdmin             : 1,
                            without_pagination  : 1
                        },

                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            _that.userRoles         = response.data.role_list;
                        }
                        else{
                            _that.success_message   = "";
                            _that.error_messages    = response.data.error;
                        }
                    })
            },
        },
        created()
        {
            this.selectedId = this.customerId;
            this.getuserData(this.selectedId);
            this.getUserRoles();
        }
    }
</script>

<style scoped>

</style>
