<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Rate</h2>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Currency Name<span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="rateData.currency_name" id="rateCurrencyName"  @keyup="checkAndChangeValidation(rateData.currency_name,'#rateCurrencyName', '#rateCurrencyNameError', '*currency name')" placeholder="Enter currency name" required>
                            <span id="rateCurrencyNameError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Currency<span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="rateData.currency_code" id="rateCurrency"  @keyup="checkAndChangeValidation(rateData.currency_code,'#rateCurrency', '#rateCurrencyError', '*currency')" placeholder="Enter exchange rate currency" required>
                            <span id="rateCurrencyError" class="text-danger small"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="buyingRate">Buying Rate<span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="rateData.buying_rate" id="buyingRate"  @keyup="checkAndChangeValidation(rateData.buying_rate,'#buyingRate', '#buyingRateError', '*buying rate')" placeholder="Enter buying rate" required>
                            <span id="buyingRateError" class="text-danger small"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sellingRate">Selling Rate<span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="rateData.selling_rate" id="sellingRate"  @keyup="checkAndChangeValidation(rateData.selling_rate,'#sellingRate', '#sellingRateError', '*selling rate')" placeholder="Enter selling rate" required>
                            <span id="sellingRateError" class="text-danger small"></span>
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

        name        : "exchangeRateAdd.vue",
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
                exchangeRate                : '',
                userInfo                    : '',
                logo_file                   : '',
                logo_url                    : '',
                user_roles                  : '',
                roleAccess                  : [],
                userInformation             : '',

                rateData : {
                    currency_name             : '',
                    currency_code                  : '',
                    buying_rate               : '',
                    selling_rate              : '',
                    status                    : '1',
                },
                validation_error    : {
                    israteCurrencyStatus   : false,
                    israteCurrencyNameStatus   : false,
                    isbuyingRateStatus     : false,
                    issellingRateStatus    : false,
                },
            }
        },
        methods: {
            validateAndSubmit()
            {
                console.log('data is',this.validation_error.isbuyingRateStatus)
                if (!this.rateData.buying_rate){
                    $('#buyingRateError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#buyingRateError').html("*buying rate field is required");
                    this.validation_error.isbuyingRateStatus = false;
                }



                if (!this.rateData.selling_rate){
                    $('#sellingRateError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#sellingRateError').html("*selling rate field is required");
                    this.validation_error.issellingRateStatus = false;
                }



                if (!this.rateData.currency_code){
                    $('#rateCurrencyError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateCurrencyError').html("*currency field is required");
                    this.validation_error.israteCurrencyStatus = false;
                }

                // if ( this.validation_error.israteCurrencyStatus === true){
                //     this.exchangeRateAdd();
                // }



                if (!this.rateData.currency_name){
                    $('#rateCurrencyNameError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateCurrencyNameError').html("*currency name field is required");
                    this.validation_error.israteCurrencyNameStatus = false;
                }

                if ( this.validation_error.israteCurrencyNameStatus === true && this.validation_error.israteCurrencyStatus === true){
                    this.exchangeRateAdd();
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

                    if (selected_name === "*currency name" ){
                        this.validation_error.israteCurrencyNameStatus = true;
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*currency name" ){
                        this.validation_error.israteCurrencyNameStatus = false;
                    }
                }


                if (selected_data.length >0) {
                    $(selected_id).css({
                        'border-color': '#ced4da',
                    });
                    $(selected_error_id).html("");

                    if (selected_name === "*currency" ){
                        this.validation_error.israteCurrencyStatus = true;
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*currency" ){
                        this.validation_error.israteCurrencyStatus = false;
                    }
                }



                if (selected_data.length >0) {
                      if (selected_name === "*buying rate" ){
                            this.validation_error.isbuyingRateStatus = true;
                        }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*buying rate" ){
                        this.validation_error.isbuyingRateStatus = false;
                    }
                }



                if (selected_data.length >0) {
                    if (selected_name === "*selling rate" ){
                            this.validation_error.issellingRateStatus = true;
                        }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*selling rate" ){
                        this.validation_error.issellingRateStatus = false;
                    }
                }
            },
            //server validation
            showServerError(errors)
            {

                $('#rateCurrencyError').html("");
                $('#rateCurrency').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("currency code")==true){
                        $('#rateCurrencyError').html(val)
                        $('#rateCurrency').css({'border-color': '#FF7B88'});
                    }

                    if (val.includes("currency name")==true){
                        $('#rateCurrencyNameError').html(val)
                        $('#rateCurrencyName').css({'border-color': '#FF7B88'});
                    }
                })

                // $('#rateCurrencyNameError').html("");
                // $('#rateCurrencyName').css({'border-color': '#ced4da'});
                // errors.forEach(val=>{
                //     if (val.includes("currency name")==true){
                //         $('#rateCurrencyNameError').html(val)
                //         $('#rateCurrencyName').css({'border-color': '#FF7B88'});
                //     }
                // })
            },

            //category add
            exchangeRateAdd()
            {
                console.log('i am from add', this.rateData);
    
                let _that = this;
                let formData = new FormData();


                formData.append('currency_name', _that.rateData.currency_name);
                formData.append('currency_code', _that.rateData.currency_code);
                formData.append('buying_rate', _that.rateData.buying_rate);
                formData.append('selling_rate', _that.rateData.selling_rate);
                formData.append('status', _that.rateData.status);

                formData.append('role_id', this.roleAccess);

                axios.post('exchange-rate', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200){

                        _that.rateData          = '';
                        _that.error_messages    = '';
                        _that.success_message   = "Exchange Rate Added Successfully";

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
            getExchangeRate()
            {

                let _that =this;

                axios.get('exchange-rate',
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
                            _that.ExchangeRate = response.data.exchange_rate;
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
                this.getExchangeRate();
                this.getUserRoles();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
