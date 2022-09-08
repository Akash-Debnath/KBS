<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isEmailConfigurationCheck===true">
        <div class="right-sidebar-content-area px-2">

            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Email Configuration</h2>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Mail Driver Type <span class="required">*</span></label>
                            <select v-model="configure_data.type" id="type" class="form-control" required>
                                <option value="smtp">SMTP</option>
                                <option value="mail">MAIL</option>
                            </select> 
                            <span id="typeError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="host">Host<span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="configure_data.host" id="host" placeholder="Enter Mail Host" @keyup="checkvalidation($event)" required>
                            <span id="hostError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="port">Port<span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="configure_data.port" id="port" placeholder="Enter Port" @keyup="checkvalidation($event)" required>
                            <span id="portError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="configure_data.username" id="username" placeholder="Enter Username" @keyup="checkvalidation($event)" required>
                            <span id="usernameError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password <span class="required">*</span></label>
                            <input class="form-control" type="password" v-model="configure_data.password" id="password" placeholder="Enter Password" @keyup="checkvalidation($event)" required>
                            <span id="passwordError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="encryption">Encryption Type</label>
                            <select v-model="configure_data.encryption" id="encryption" class="form-control">
                                <option value="">Select One</option>
                                <option value="ssl">SSL</option>
                                <option value="tls">TLS</option>
                            </select> 
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="primary_email">Primary Email<span class="required">*</span></label>
                            <input class="form-control" type="email" v-model="configure_data.primary_email" id="primary_email" placeholder="Enter Primary Email" @keyup="checkvalidation($event)" required>
                            <span id="primary_emailError" class="text-danger small"></span>
                        </div>
                    </div>


                    
                </div>

                <div class="form-group text-right">
                    <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSave()">Save</button>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
import axios from "axios";
import $ from "jquery";
export default {
    name: "emailConfiguration",
    props: ['isEmailConfigurationCheck'],

    data() {
        return {
            isEditCheck  : false,
            isAddCheck   : false,

            success_message : '',
            error_message   : '',
            token           : '',
            isvalidate      : true,

            is_config_check : false,

            emailConfigInfo : '',

            configure_data : {
                id            : '',
                username      : '',
                password      : '',
                host          : '',
                type          : 'smtp',
                port          : '',
                encryption    : '',
                primary_email : '',
            },

        }
    },

    methods: {


        getEmailConfiguration(pageUrl) {
            
            let _that =this;

            pageUrl = pageUrl == undefined ? 'email-setting' : pageUrl;

            axios.get(pageUrl,
                {
                    headers: {
                        'Authorization': 'Bearer '+localStorage.getItem('authToken')
                    },
                    params :
                        {
                            isAdmin : 1
                        },
                })
                .then(function (response) {
                    console.log(response);
                    if(response.data.status_code === 200){

                        if ( !(response.data.email_config_info)) {

                            _that.isEdit =  false;
                            _that.isAdd  =  true;

                        }
                        else{
                            _that.isEdit =  true;
                            _that.isAdd  =  false;
                            _that.emailConfigInfo = response.data.email_config_info;
                            _that.configure_data.id          =  _that.emailConfigInfo.id;
                            _that.configure_data.username       =  _that.emailConfigInfo.username;
                            _that.configure_data.password    =  _that.emailConfigInfo.password;
                            _that.configure_data.host =  _that.emailConfigInfo.host;
                            _that.configure_data.type =  _that.emailConfigInfo.type;
                            _that.configure_data.port =  _that.emailConfigInfo.port;
                            _that.configure_data.encryption =  _that.emailConfigInfo.encryption;
                            _that.configure_data.primary_email =  _that.emailConfigInfo.primary_email;
                        }

                    } else{

                        _that.success_message = "";
                        _that.error_message   = response.data.error;

                    }
                });

        },

        checkvalidation(e) {
           
            var id = e.target.id;
            var value = e.target.value;
            var errorId = id+"Error";
            if(!value) {
                $('#'+id).css({
                    'border-color': '#FF7B88'
                });
                $('#'+errorId).html("*This field is required");
            } else {
                $('#'+id).css({
                    'border-color': ''
                });
                $('#'+errorId).html("");
            }
        },

         validateAndSave(e) {
             let _that = this;
           if(_that.configure_data.type && _that.configure_data.port &&_that.configure_data.host &&_that.configure_data.username &&_that.configure_data.password &&_that.configure_data.primary_email){
              this.saveEmailConfiguration();
           }else{
               console.log("validation Error");
           }
        },

        saveEmailConfiguration() {
            let _that = this;

            if (this.isAdd === true) {

                let formData = new FormData();
                formData.append('username', this.configure_data.username);
                formData.append('password', this.configure_data.password);
                formData.append('host', this.configure_data.host);
                formData.append('type', this.configure_data.type);
                formData.append('port', this.configure_data.port);
                formData.append('encryption', this.configure_data.encryption);
                formData.append('primary_email', this.configure_data.primary_email);

                axios.post('email-setting', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200) {

                        _that.isAdd = false;
                        _that.error_message    = '';
                        _that.success_message  = "Email Configuration Saved Successfully";
                        //data sends to parent
                        _that.$emit('email-config-close', _that.success_message);
                        // _that.setTimeoutElements();

                    } else {
                        _that.success_message = "";
                        _that.error_message   = response.data.error;
                    }

                }).catch(function (error) {
                    console.log(error);
                });
            }

            if (this.isEdit === true) {

                let formData = new FormData();
                formData.append('id', this.configure_data.id);
                formData.append('username', this.configure_data.username);
                formData.append('password', this.configure_data.password);
                formData.append('host', this.configure_data.host);
                formData.append('type', this.configure_data.type);
                formData.append('port', this.configure_data.port);
                formData.append('encryption', this.configure_data.encryption);
                formData.append('primary_email', this.configure_data.primary_email);
                
                axios.post('email-setting/update', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code === 200) {
                        _that.getEmailConfiguration();
                        _that.error_message    = '';
                        _that.success_message  = "Page Configuration Updated Successfully";
                        _that.$emit('email-config-close', _that.success_message);
                        // _that.setTimeoutElements();

                    } else {

                        _that.success_message = "";
                        _that.error_message   = response.data.error;

                    }

                }).catch(function (error) {
                    console.log(error);
                });
            }

        },

        setTimeoutElements() {
            setTimeout(() => this.success_message = "", 2e3);
            setTimeout(() => this.error_message = "", 2e3);
        },

    },

    created() {
        this.getEmailConfiguration();
        this.is_config_check = this.isEmailConfigurationCheck;
        console.log(this.is_config_check)
    }
}
</script>

<style scoped>

</style>
