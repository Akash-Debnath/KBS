<template>
    <div class="user-profile-bar-wrapper fixed-top px-15 py-30 py-md-50">
        <div class="user-profile-logo text-center mb-25">
            <img class="img-fluid" :src="user_info.media ? user_info.media.url : '@/assets/img/user-image.jpg'" alt="root@ccpro">
        </div>
        <h3 data-toggle="tooltip" title="username" class="user-title m-0 text-center">{{ user_info.username }}</h3>
        <!-- upb-section-wrapper -->
        <div class="upb-section-wrapper pt-20 pt-md-25">
            <div data-toggle="tooltip" title="full name" class="upb-section-title text-uppercase mb-20 md-md-50">{{user_info.first_name+" "+user_info.last_name}}</div>
            <ul class="upb-section-items">
                <li><a href="#" @click.prevent class="close-user-profile" data-toggle="modal" data-target="#staticBackdrop"><i class="fas fa-unlock-alt"></i> <span>Change Password</span></a></li>
                <li><a href="#" @click="logout"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        <!-- upb-section-wrapper end -->


        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Change Password</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!--                            <div class="col-md-12">-->
                            <!--                                <div class="form-group">-->
                            <!--                                    <label for="password">Old Password <span class="required">*</span></label>-->
                            <!--                                    <input class="form-control" type="text" v-model="userData.old_password" id="oldPassword" @keyup="checkAndValidatePassword()" placeholder="Enter old password here!!" required>-->
                            <!--                                    <span id="oldPasswordError" class="text-danger small"> </span>-->
                            <!--                                </div>-->
                            <!--                            </div>-->

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password">New Password <span class="required">*</span></label>
                                    <input class="form-control" type="password" v-model="userData.password" id="password" @keyup="checkAndValidatePassword()" placeholder="Enter new password here!!" required>
                                    <span id="passwordError" class="text-danger small"> </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm Password <span class="required">*</span></label>
                                    <input class="form-control" type="password" @keyup.enter="validateAndSubmit" v-on:keyup="checkPasswordMatch()" v-model="userData.confirm_password" id="confirmPassword" placeholder="Enter confirm password again!!" required>
                                    <span id="confirmPasswordError" class="small"> </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="closeModal" class="btn btn-danger rounded btn-md m-1 text-white" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary rounded btn-md m-1 text-white" @click="validateAndSubmit">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-modal-wraper" v-if="success_message">
            <span>{{ success_message }}</span>
        </div>

        <div class="action-modal-wraper-error" v-if="error_message">
            <span>{{ error_message }}</span>
        </div>
    </div>
</template>

<script>
import $ from 'jquery'
import axios from "axios";

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});

$(document).on('click', '.close-user-profile', function () {
    $('.user-profile-bar-wrapper').toggleClass('user-profile-bar-show');
});

export default {
    name: "UserProfileBar",
    data() {
        return {
            success_message : '',
            error_message   : '',
            token: '',
            user_info : '',

            userData : {
                // old_password:'',
                // id                      : '',
                password                : '',
                confirm_password        : ''
            },
            validation_error : {
                isPasswordStatus        : '',
                isConfirmationStatus    :''
            }
        }
    },
    methods :{
        setTimeoutElements()
        {
            // setTimeout(() => this.isLoading = false, 3e3);
            setTimeout(() => this.success_message = "", 2e3);
            setTimeout(() => this.error_message = "", 2e3);
        },
        showServerError(errors)
        {
            $('#passwordError').html("");
            // $('#confirmPasswordError').html("");

            $('#password').css({'border-color': '#ced4da'});
            // $('#confirmPassword').css({'border-color': '#ced4da'});

            errors.forEach(val=>{
                if (val.includes("password")==true){
                    $('#passwordError').html(val)
                    $('#password').css({'border-color': '#FF7B88'});
                }
            })
        },
        userChangePassword(){
            console.log("okay");
            let _that = this;
            axios.post('user/update-password',
                {
                    id                  : this.user_info.id,
                    password            : this.userData.password,
                    confirm_password    : this.userData.confirm_password,
                },
                {
                    headers: {
                        'Authorization': 'Bearer '+localStorage.getItem('authToken')
                    }
                }).then(function (response) {
                console.log(response.data)
                if (response.data.status_code === 200)
                {
                    _that.userData                  = '';
                    _that.error_message             = '';
                    _that.success_message           = "Password updated successfully";
                    _that.setTimeoutElements();
                    $('#closeModal').click();
                    $('#confirmPasswordError').html("");

                }else if(response.data.status_code === 400){
                    _that.success_message           = "";
                    _that.error_message             = "";
                    _that.showServerError(response.data.errors);

                }else{
                    _that.success_message           = "";
                    _that.error_message             = response.data.message;
                }
            }).catch(function (error) {
                console.log(error);
            });
        },

        logout()
        {
            let _that = this;
            axios.post('logout',
                {
                    id          : this.user_info.id,
                    tokenId     : localStorage.getItem('authToken')
                },
                {
                    headers: {
                        'Authorization': 'Bearer '+localStorage.getItem('authToken')
                    }
                }
            ).then(function (response) {

                if (response.data.status_code === 200){
                    localStorage.clear();
                    _that.$router.push({ name: 'Login', params: { message : response.data.message }});
                }else{
                    _that.success_message           = "";
                    _that.error_message             = response.data.message;
                }
            });
        },

        validateAndSubmit()
        {
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
            if (this.validation_error.isPasswordStatus === true && this.validation_error.isConfirmationStatus === true){
                this.userChangePassword();
            }
        },

        checkAndValidatePassword(){
            if ((this.userData.password).length >0) {
                if (!this.validPassword(this.userData.password)) {
                    $('#password').css({
                        'border-color': '#FF7B88',
                    });
                    $('#passwordError').html("*password should contain at least a Uppercase, lowecase, numeric and special character");
                    this.validation_error.isPasswordStatus = false;

                } else if((this.userData.password).length <8) {
                    $('#password').css({
                        'border-color': '#FF7B88',
                    });
                    $('#passwordError').html("*password must be 8 characters or longer");
                    this.validation_error.isPasswordStatus = false;
                }
                else {
                    $('#password').css({
                        'border-color': '#ced4da',
                    });
                    $('#passwordError').html("");
                    this.validation_error.isPasswordStatus = true;
                }

            } else{
                $('#password').css({
                    'border-color': '#FF7B88',
                });
                $('#passwordError').html("password field is required");
                this.validation_error.isPasswordStatus = false;
            }

        },

        checkPasswordMatch()
        {
            let _that = this;
            if (_that.userData.password === _that.userData.confirm_password && _that.userData.password.length == _that.userData.confirm_password.length){
                $('#confirmPassword').css({
                    'border-color': '#ced4da',
                });
                $('#confirmPasswordError').css({'color': 'green'});
                $('#confirmPasswordError').html("password matched!!");
                _that.validation_error.isConfirmationStatus = true;
            }else if(!_that.userData.confirm_password){
                $('#confirmPassword').css({
                    'border-color': '#FF7B88',
                });
                $('#confirmPasswordError').html("*confirm password field is required")
                $('#confirmPasswordError').css({'color': '#FF7B88'});
                _that.validation_error.isConfirmationStatus = false;

            }
            else{
                $('#confirmPassword').css({
                    'border-color': '#FF7B88',
                });
                $('#confirmPasswordError').html("*password not matched")
                $('#confirmPasswordError').css({'color': '#FF7B88'});
                _that.validation_error.isConfirmationStatus = false;
            }
        },

        validPassword(password)
        {
            var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{4,})");
            return strongRegex.test(password);
        },

    },
    created() {
        this.user_info = JSON.parse(localStorage.getItem("userInformation"));
    }
}
</script>

<style scoped>

</style>
