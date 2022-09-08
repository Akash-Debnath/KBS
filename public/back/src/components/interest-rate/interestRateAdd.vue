<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Rate</h2>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Title<span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="rateData.title" id="rateTitle"  @keyup="checkAndChangeValidation(rateData.title,'#rateTitle', '#rateTitleError', '*title')" placeholder="Enter rate Title" required>
                            <span id="rateTitleError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="rateRate">Interest Rate(%)<span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="rateData.rate" id="rateRate"  @keyup="checkAndChangeValidation(rateData.rate,'#rateRate', '#rateRateError', '*interest rate')" placeholder="Enter interest rate" required>
                            <span id="rateRateError" class="text-danger small"></span>
                        </div>
                    </div>
                    

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="rateData.status" >
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

        name        : "interestRateAdd.vue",
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
                interestRate                : '',
                userInfo                    : '',
                logo_file                   : '',
                logo_url                    : '',
                user_roles                  : '',
                roleAccess                  : [],
                userInformation             : '',

                rateData : {
                    title                  : '',
                    rate                   : '',
                    status                 : '1',
                },
                validation_error    : {
                    israteTitleStatus   : false,
                    israteRateStatus    : false,
                },
            }
        },
        methods: {
            validateAndSubmit()
            {
                console.log('data is',this.validation_error.israteRateStatus)
                if (!this.rateData.rate){
                    $('#rateRateError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateRateError').html("*interest rate field is required");
                    this.validation_error.israteRateStatus = false;
                }



                if (!this.rateData.title){
                    $('#rateTitleError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateTitleError').html("*title field is required");
                    this.validation_error.israteTitleStatus = false;
                }

                if ( this.validation_error.israteTitleStatus === true){
                    this.interestRateAdd();
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

                // this.getUserRoles();
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
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                        if (selected_name === "*title"){
                            this.validation_error.israteTitleStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*title" ){
                            this.validation_error.israteTitleStatus = true;
                        }
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






                if (selected_data.length >0) {
                    if (selected_data.length <1){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 1 digit");

                        if (selected_name === "*interest rate"){
                            this.validation_error.israteRateStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*interest rate" ){
                            this.validation_error.israteRateStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*interest rate" ){
                        this.validation_error.israteRateStatus = false;
                    }
                }
            },
            //server validation
            showServerError(errors)
            {

                $('#rateTitleError').html("");
                $('#rateTitle').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("title")==true){
                        $('#rateTitleError').html(val)
                        $('#rateTitle').css({'border-color': '#FF7B88'});
                    }
                })
            },

            //category add
            interestRateAdd()
            {
                console.log('i am from add', this.rateData);
    
                let _that = this;
                let formData = new FormData();

                formData.append('title', _that.rateData.title);
                formData.append('rate', _that.rateData.rate);
                formData.append('status', _that.rateData.status);

                formData.append('role_id', this.roleAccess);

                axios.post('interest-rate', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200){

                        _that.rateData          = '';
                        _that.error_messages    = '';
                        _that.success_message   = "Interest Rate Added Successfully";

                        _that.$emit('rate-slide-close', _that.success_message);
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
            getInterestRate()
            {

                let _that =this;

                axios.get('interest-rate',
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
                            _that.interestRate = response.data.interest_rate;
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
                this.getInterestRate();
                this.getUserRoles();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
