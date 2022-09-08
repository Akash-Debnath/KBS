<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Customer Password Reset</h2>

                <div class="row" v-if="userData">
                    <div class="col-md-12">
                        <div v-if="success_message" class="alert alert-success" role="alert">
                            {{ success_message }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password <span class="required">*</span></label>
                            <input class="form-control" type="password" v-model="userData.password" id="Password" @keyup="checkAndValidatePassword()" placeholder="Enter password here!!" required>
                            <span id="PasswordError" class="text-danger small"> </span>
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

        name: "ResetPasswordCustomer.vue",
        components: {
        },
        props: ['isResetCheck', 'customerId'],

        data() {
            return {
                isMounted                       : false,
                success_message                 : '',
                error_messages                  : [],
                token                           : '',
                selectedId                      : '',
                userData                        : {
                    password                    : ''
                },
                roles                           : '',
                userRoles                       : '',

                filter                          : {
                    isAdmin                     : 1
                },

                validation_error                :      {
                    isPasswordStatus            : false,
                } ,
            }
        },

        methods: {
            validPassword(password)
            {
                var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{4,})");
                return strongRegex.test(password);
            },

            checkAndValidatePassword(){

                if ((this.userData.password).length >0) {
                    if (!this.validPassword(this.userData.password)) {
                        $('#Password').css({
                            'border-color': '#FF7B88',
                        });
                        $('#PasswordError').html("*password should contain at least a Uppercase, lowecase, numeric and special character");
                        this.validation_error.isPasswordStatus = false;

                    } else if((this.userData.password).length <8) {
                        $('#Password').css({
                            'border-color': '#FF7B88',
                        });
                        $('#PasswordError').html("*password must be 8 characters or longer");
                        this.validation_error.isPasswordStatus = false;
                    }
                    else {
                        $('#Password').css({
                            'border-color': '#ced4da',
                        });
                        $('#PasswordError').html("");
                        this.validation_error.isPasswordStatus = true;
                    }

                } else{
                    $('#Password').css({
                        'border-color': '#FF7B88',
                    });
                    $('#PasswordError').html("*password field is required");
                    this.validation_error.isPasswordStatus = false;
                }

            },

            //update button pressed
            validateAndSubmit()
            {
                if (!this.userData.password){

                    $('#password').css({
                        'border-color': '#FF7B88',
                    });
                    $('#passwordError').html("*password field is required");
                }

                if (this.validation_error.isPasswordStatus === true){
                    this.userUpdate();
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
                axios.put('users/update',
                    {
                        id                      : userID,
                        username                : _that.userData.username,
                        first_name              : _that.userData.first_name,
                        last_name               : _that.userData.last_name,
                        email                   : _that.userData.email,
                        status                  : _that.userData.status,
                        password                : _that.userData.password,
                        roles                   : _that.roles,
                    },
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code == 200)
                    {
                        _that.userData                  = '';
                        _that.error_message             = '';
                        _that.success_message           = "User Password Reset Successfully";

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
                        if (response.data.status_code === 200)
                        {
                            console.log(response.data)
                            _that.userData          = response.data.user_info;
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
