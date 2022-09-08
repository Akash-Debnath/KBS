<template>
    <div>
        <div class="login-wrapper login-wrapper-with-centered-items positon-relaive h-100vh d-block d-sm-flex justify-content-center align-items-center">
            <div class="centered-login-area position-relative overflow-hidden">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="login-form-wrapper d-flex flex-wrap flex-column py-20 py-sm-40 pl-20 pl-sm-40 pr-20">
                            <div class="gplex-logo text-center">
                                <img :src="static_image['dashboard_logo']" alt="gplex" class="img-fluid">
                            </div>

                            <div class="login-middle-wrapper py-40">
                                <div class="account-type-status text-center py-10">
                                    <span class="acc-type text-uppercase">Knowledge Base System</span>
                                </div>

                                <div style="min-height:39px"><div v-if="checkedCounter > 0" class="small text-danger pt-20" id="loginStatusError"></div></div>

                                <div class="login-form pt-20">
                                    <div class="form-group floating-input with-icon user-icon mb-30 mb-md-50">
                                        <input class="form-control" type="text" v-model="formData.username" id="userName" @keyup="checkAndChangeValidation(formData.username,'#userName','#userNameError','*username')">
                                        <label for="userName">User name</label>
                                        <span class="progress-border userName"></span>
                                        <span id="userNameError" class="small text-danger error-message"></span>
                                    </div>
                                    <div class="form-group floating-input with-icon password-icon mb-30 mb-md-50">
                                        <input class="form-control" type="password" v-model="formData.password" id="userPassword" v-on:keyup.enter="validateAndSubmit()" @keyup="checkAndChangeValidation(formData.password,'#userPassword','#userPasswordError','*password')">
                                        <label for="userPassword">Password</label>
                                        <span class="password-show-hide"></span>
                                        <span class="progress-border userPassword"></span>
                                        <span id="userPasswordError" class="small text-danger error-message"></span>
                                    </div>

                                    <div class="form-group text-center">
                                        <button class="btn btn-primary btn-common-2 position-relative overflow-hidden ripple-btn btn-with-rightside-icon text-left text-uppercase py-15 px-45" @click="validateAndSubmit()"><span>Sign In</span></button>
                                    </div>
                                </div>
                            </div>

                            <div class="powered-by text-center mt-auto">
                                <!--                <div class="client-logo d-block d-sm-none mb-10 mb-sm-0">
                                                  <img class="img-fluid" src="../assets/img/airtel-logo.png" alt="airtel">
                                                </div>-->
                                <div class="client-info text-center d-block d-sm-none mb-2 mb-sm-0"></div>
                                Powered by gPlex
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 centered-login-bg d-none d-sm-block" :style="{ backgroundImage: 'url(' + require('../assets/img/call-center.png') + ')' }">
                        <div class="information-wrapper d-flex align-items-end flex-column h-100 p-40">
                            <div class="client-logo">
                                <img class="img-fluid " :src="static_image['dashboard_sm_logo']" alt="demo">
                            </div>
                            <div class="client-info mt-auto"><a target="_blank" href="https://www.genuitysystems.com/">Genuity System Ltd.</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="action-modal-wraper top-0" v-if="success_message">
            <span>{{ success_message }}</span>
        </div>

        <div class="action-modal-wraper-error top-0" v-if="error_message">
            <span>{{ error_message }}</span>
        </div>

    </div>
</template>

<script>
import $ from 'jquery'

// $(document).ready(function(){
//     const event = new FocusEvent('focus', {
//         view: window,
//         bubbles: true,
//         cancelable: true
//     });
//     document.getElementById('userName').dispatchEvent(event);
//     document.getElementById('userPassword').dispatchEvent(event);
// });

// Login Form

// Password show hide
$(document).on('click','.password-show-hide',function(){
    $(this).toggleClass("show");
    let x = document.getElementById("userPassword");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
});

import axios from 'axios'

export default {
    name: "login.vue",
    data() {
        return {
            notFound                : false,
            success_message         : '',
            error_message           : '',
            token                   : '',
            static_image            : [],
            checkedCounter          : 0,
            formData          : {
                username            : '',
                password            : '',

            },
            userInfo                : '',
            validation_error :{
                isUserNameStatus    : false,
                isPasswordStatus    : false,
            },
        }
    },
    methods: {

        clearanceAll(){
            if (!this.formData.username || !this.formData.password){
                $(document).on('focus','.form-group input',function(){
                    $(this).parent().addClass('focused');
                });
                $(document).on('blur','.form-group input',function(){
                    let inputValue = $(this).val();
                    if ( inputValue == "" ) {
                        $(this).removeClass('filled');
                        $(this).parents('.form-group').removeClass('focused');
                    } else {
                        $(this).addClass('filled');
                    }
                });
            }else{
                $(document).ready(function(){
                    const event = new FocusEvent('focus', {
                        view: window,
                        bubbles: true,
                        cancelable: true
                    });
                    document.getElementById('userName').dispatchEvent(event);
                    document.getElementById('userPassword').dispatchEvent(event);
                });
            }
        },

        checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
        {
            if (selected_data.length >0) {

                $(selected_id).css({
                    'border-color': '#ced4da',
                });
                $(selected_error_id).html("");
                if(selected_name === "*username"){
                    this.validation_error.isUserNameStatus = true;
                }else if(selected_name === "*user password"){
                    this.validation_error.isPasswordStatus = true;
                }
            }else{
                $(selected_id).css({'border-color': '#FF7B88',});
                $(selected_error_id).html(selected_name+" field is required")
                if(selected_name === "*username"){
                    this.validation_error.isUserNameStatus = false;
                }else if(selected_name === "*user password"){
                    this.validation_error.isPasswordStatus = true;
                }
            }
        },

        validateAndSubmit() {

            if (!this.formData.username){

                $('#userName').css({
                    'border-color': '#FF7B88',
                });
                $('#userNameError').html("*username field is required");

                this.validation_error.isUserNameStatus = false;
            }
            if (!this.formData.password){

                $('#userPassword').css({
                    'border-color': '#FF7B88',
                });
                $('#userPasswordError').html("*password field is required");

                this.validation_error.isPasswordStatus = false;
            }
            if (this.formData.username && this.formData.password){
                this.validation_error.isUserNameStatus = true;
                this.validation_error.isPasswordStatus = true;
            }
            if (this.validation_error.isUserNameStatus === true &&
                this.validation_error.isPasswordStatus === true){
                this.customerLogin();
            }
        },

        setTimeoutElements() {

            // setTimeout(() => this.isLoading = false, 3e3);
            setTimeout(() => this.success_message = "", 2e3);
            setTimeout(() => this.error_message = "", 2e3);
        },

        customerLogin() {
            let _that = this;
            ++_that.checkedCounter;
            axios.post('login', {
                username          : this.formData.username,
                password          : this.formData.password,
            }).then(function (response) {

                if (response.data.status_code === 200){

                    // console.log(response.data.user_info.roles[0].permissions);
                    _that.checkedCounter  = 0;
                    _that.userInfo        = response.data.user_info;
                    _that.token           = response.data.token;
                    _that.error_message   = '';
                    _that.success_message = response.data.messages;

                    localStorage.setItem('authToken', _that.token);
                    localStorage.setItem('userInformation', JSON.stringify(response.data.user_info));
                    localStorage.setItem('userPermissions', JSON.stringify(response.data.user_info.roles[0].permissions));
                    _that.$router.push({ name: 'dashboard', params: { current_status : "Logged in successfully" }});
                }
                else
                {
                    localStorage.removeItem('userInformation');
                    localStorage.removeItem('userPermissions');
                    localStorage.removeItem('bannerInformation');
                    _that.success_message = "";
                    if (response.data.errors){
                        _that.showServerError(response.data.errors)
                    }else{
                        $('#loginStatusError').html(response.data.messages);
                    }
                }
            }).catch(function (error)
            {
                console.log(error);
            });
        },

        showServerError(errors) {

            $('#userNameError').html("");
            $('#userPassword').html("");

            $('#userName').css({'border-color': '#ced4da'});
            $('#userPassword').css({'border-color': '#ced4da'});

            errors.forEach(val=>{
                console.log(val);
                if (val.includes("username")){
                    $('#userNameError').html(val)
                    $('#userName').css({'border-color': '#FF7B88'});
                }
                else if (val.includes("password")){
                    $('#userPasswordError').html(" "+val)
                    $('#userPassword').css({'border-color': '#FF7B88'});
                }
            })
        },
    },
    created() {
        // localStorage.removeItem('query_string');
        this.static_image['dashboard_logo']         = axios.defaults.baseURL.replace('api/','')+'static_media/new-logo.png';
        this.static_image['dashboard_sm_logo']      = axios.defaults.baseURL.replace('api/','')+'static_media/small-logo.png';
        this.clearanceAll();
    /*    if (this.$route.params){
            this.success_message = this.$route.params.message;
            this.setTimeoutElements();
        }*/
    }
}

</script>

<style scoped>
    .error-message {
        position: absolute;
        bottom: -20px;
    }
</style>
