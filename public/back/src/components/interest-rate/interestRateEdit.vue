<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">


            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Edit Interest Rate</h2>

                <div class="alert-success" v-if="success_message">
                    <span>{{ success_message }}</span>
                </div>

                <div  v-if="error_message">
                    <span class="text-danger">{{ error_message }}</span>
                </div>

                <div class="row">

                    <div class="col-md-12" v-if="interestRateId ? interest_rate_id=interestRateId : ''">
                        <div class="form-group">
                            <label for="rateTitle">Title <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="interest_title" id="rateTitle" @keyup="checkAndChangeValidation()" required>
                            <span  id="rateTitleError" class="small text-danger interest_title" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="rateRate">Interest Rate <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="interestRate_rate" id="rateRate" @keyup="checkAndChangeValidation()" required>
                            <span  id="rateRateError" class="small text-danger interestRate_rate" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="interest_status" >
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
        props: ['isEditCheck', 'interestRateId'],

        components:{
            CustomRoleEdit,
            Multiselect
        },

        data() {
            return {
                interest_rate_id : '',
                menu_parent_id   : '',
                interest_title   : '',
                interestRate_rate: '',
                interest_status  : '',
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
                if (!_that.interest_title){

                    $('#rateTitle').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateTitleError').html("*The title is required");
                }
                else if (_that.interest_title && (_that.interest_title).length >2 && (_that.interest_title).length <100){
                    $('#rateTitle').css({
                        'border-color': '#ced4da',
                    });
                    $('#rateTitleError').html("");
                }
                else{
                    $('#rateTitle').css({
                        'border-color': '#FF7B88',
                    });

                    $('#rateTitleError').html("*The Title must be between 3 to 100 charecter");
                }



                if (!_that.interestRate_rate){
                    $('#rateRate').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateRateError').html("*The rate is required");
                }
                else if (_that.interestRate_rate && (_that.interestRate_rate).length >0 && (_that.interestRate_rate).length <100){
                    $('#rateRate').css({
                        'border-color': '#ced4da',
                    });
                    $('#rateRateError').html("");
                }
            },

            showServerError(errors)
            {
                $('#rateTitleError').html("");
                $('#rateTitle').css({'border-color': '#ced4da'});
                errors.forEach(val => {
                    if (val.includes("title")==true){
                        $('#rateTitleError').html(val)
                        $('#rateTitle').css({'border-color': '#FF7B88'});
                    }
                })
            },

            dataValidate()
            {
                let _that = this;

                if (!_that.interest_title){

                    $('#rateTitle').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateTitleError').html("*The title is required");
                }
                else if (!_that.interestRate_rate){

                    $('#rateRate').css({
                        'border-color': '#FF7B88',
                    });
                    $('#rateRateError').html("*The rate is required");
                }
                else{
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

                formData.append('id', _that.interest_rate_id);
                formData.append('title', _that.interest_title);
                formData.append('rate', _that.interestRate_rate);
                formData.append('status', _that.interest_status);



                axios.put('interest-rate/update',
                {
                    id                 : _that.interest_rate_id,
                    title              : _that.interest_title,
                    rate               : _that.interestRate_rate,
                    status             : _that.interest_status,
                    // parent_id       : _that.menu_parent_id,
                },
                    {   
                        
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    // console.log(response);

                    if (response.data.status_code === 200){
                        _that.interest_title       = '',
                        // _that.menu_parent_id    = '',
                        _that.interestRate_rate    = '',
                        _that.error_message        = '';
                        _that.success_message      = "Rate Updated Successfully";
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
            

            getInterestDetails()
            {
                let _that   = this;
                let apiUrl  = "interest-rate/";
                axios.get(apiUrl+_that.interest_rate_id,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            console.log(response.data.rate_info);
                            _that.rateDetails         =  response.data.rate_info;
                            _that.interest_title      =  _that.rateDetails.title;
                            _that.interestRate_rate   =  _that.rateDetails.rate;
                            _that.interest_status     =  _that.rateDetails.status;


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
                this.interest_rate_id = this.interestRateId;
                this.getInterestDetails(this.interest_rate_id);
                // this.getLinkList();
                this.getUserRoles();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
