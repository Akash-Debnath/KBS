<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">


            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Edit Exchange Rate</h2>

                <div class="alert-success" v-if="success_message">
                    <span>{{ success_message }}</span>
                </div>

                <div  v-if="error_message">
                    <span class="text-danger">{{ error_message }}</span>
                </div>

                <div class="row">

                    <div class="col-md-12" v-if="exchangeRateId ? exchange_rate_id=exchangeRateId : ''">
                        <div class="form-group">
                            <label for="rateCurrencyName">Currency Name<span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="exchange_currency_name" id="rateCurrencyName" @keyup="checkAndChangeValidation()" required>
                            <span  id="rateCurrencyNameError" class="small text-danger exchange_currency_name" role="alert"></span>
                        </div>
                    </div>

                    <div class="col-md-12" v-if="exchangeRateId ? exchange_rate_id=exchangeRateId : ''">
                        <div class="form-group">
                            <label for="rateCurrency">Currency <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="exchange_currency_code" id="rateCurrency" @keyup="checkAndChangeValidation()" required>
                            <span  id="rateCurrencyError" class="small text-danger exchange_currency_code" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="buyingRate">Buying Rate <span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="exchange_buyingRate" id="buyingRate" @keyup="checkAndChangeValidation()" required>
                            <span  id="buyingRateError" class="small text-danger exchange_buyingRate" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="sellingRate">Selling Rate <span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="exchange_sellingRate" id="sellingRate" @keyup="checkAndChangeValidation()" required>
                            <span  id="sellingRateError" class="small text-danger exchange_sellingRate" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="exchange_status" >
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

        name: "interestRateEdit.vue",
        props: ['isEditCheck', 'exchangeRateId'],

        components:{
            CustomRoleEdit,
            Multiselect
        },

        data() {
            return {
                exchange_rate_id : '',
                menu_parent_id   : '',
                exchange_currency_code   : '',
                exchange_currency_name   : '',
                exchange_buyingRate: '',
                exchange_sellingRate : '',
                exchange_status  : '',
                rateDetails      : '',
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
                if (!_that.exchange_currency_name){

                    $('#rateCurrencyName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateCurrencyNameError').html("*Currency name is required");
                }
                else{
                    $('#rateCurrencyName').css({
                        'border-color': '#ced4da',
                    });
                    $('#rateCurrencyNameError').html("");
                }



                if (!_that.exchange_currency_code){

                    $('#rateCurrency').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateCurrencyError').html("*Currency is required");
                }
                else{
                    $('#rateCurrency').css({
                        'border-color': '#ced4da',
                    });
                    $('#rateCurrencyError').html("");
                }



                if (!_that.exchange_buyingRate){
                    $('#buyingRate').css({
                        'border-color': '#FF7B88',
                    });
                    $('#buyingRateError').html("*Buying rate is required");
                }
                else{
                    $('#buyingRate').css({
                        'border-color': '#ced4da',
                    });
                    $('#buyingRateError').html(""); 
                }



                if (!_that.exchange_sellingRate){
                    $('#sellingRate').css({
                        'border-color': '#FF7B88',
                    });
                    $('#sellingRateError').html("*Selling rate is required");
                }

                else{
                    $('#sellingRate').css({
                        'border-color': '#ced4da',
                    });
                    $('#sellingRateError').html("");
                }
            },

            showServerError(errors)
            {
                $('#rateCurrencyError').html("");
                $('#rateCurrency').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("currency code")==true) {
                        $('#rateCurrencyError').html(val)
                        $('#rateCurrency').css({'border-color': '#FF7B88'});
                    }

                    if (val.includes("currency name")==true){
                        console.log('i am 2');
                        $('#rateCurrencyNameError').html(val)
                        $('#rateCurrencyName').css({'border-color': '#FF7B88'});
                    }
                })
            },

            dataValidate()
            {
                let _that = this;

                if (!_that.exchange_currency_code){

                    $('#rateCurrency').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateCurrencyError').html("*Currency is required");
                    
                } else if (!_that.exchange_currency_name){

                    $('#rateCurrencyName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateCurrencyNameError').html("*Currency name is required");

                } else if (!_that.exchange_buyingRate){

                    $('#buyingRate').css({
                        'border-color': '#FF7B88',
                    });
                    $('#buyingRateError').html("*Buying rate is required");

                } else{
                    _that.rateUpdate();
                }
            },

            rateUpdate()
            {
                let _that = this;

                let collection;

                collection = _that.roleAccess.join();

                if (!collection) {
                    collection = 1;
                }

                let formData = new FormData();

                formData.append('id', _that.exchange_rate_id);
                formData.append('currency_name', _that.exchange_currency_name);
                formData.append('currency_code', _that.exchange_currency_code);
                formData.append('buying_rate', _that.exchange_buyingRate);
                formData.append('selling_rate', _that.exchange_sellingRate);
                formData.append('status', _that.exchange_status);



                axios.put('exchange-rate/update',
                {
                    id                 : _that.exchange_rate_id,
                    currency_name      : _that.exchange_currency_name,
                    currency_code      : _that.exchange_currency_code,
                    buying_rate        : _that.exchange_buyingRate,
                    selling_rate       : _that.exchange_sellingRate,
                    status             : _that.exchange_status,
                },
                    {   
                        
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {

                    if (response.data.status_code === 200){
                        _that.exchange_currency_name    = '',
                        _that.exchange_currency_code    = '',
                        _that.exchange_buyingRate       = '',
                        _that.exchange_sellingRate      = '',
                        _that.error_message             = '';
                        _that.success_message           = "Exchange Rate Updated Successfully";
                        _that.$emit('rate-slide-close',_that.success_message);
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
            

            getExchangeDetails()
            {
                let _that   = this;
                let apiUrl  = "exchange-rate/";
                axios.get(apiUrl+_that.exchange_rate_id,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            console.log(response.data.rate_info);
                            _that.rateDetails         =  response.data.rate_info;
                            _that.exchange_currency_name      =  _that.rateDetails.currency_name;
                            _that.exchange_currency_code      =  _that.rateDetails.currency_code;
                            _that.exchange_buyingRate   =  _that.rateDetails.buying_rate;
                            _that.exchange_sellingRate   =  _that.rateDetails.selling_rate;
                            _that.exchange_status     =  _that.rateDetails.status;


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
                this.exchange_rate_id = this.exchangeRateId;
                this.getExchangeDetails(this.exchange_rate_id);
                this.getUserRoles();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
