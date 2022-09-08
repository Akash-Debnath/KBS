<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Category</h2>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="categoryName">Name <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="categoryData.name" id="categoryName" @change="checkCategoryNameExist(categoryData.name)" @keyup="checkAndChangeValidation(categoryData.name,'#categoryName', '#categoryNameError', '*category name')" placeholder="Enter Category Name" required>
                            <span id="categoryNameError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select A Parent</label>
                            <select class="form-control" v-model="selectedCategory" @change="getParentGroupsAccess()">
                                <option disabled value="">Select A Category</option>
                                <option v-for="a_category in categoryList" :value="a_category.id" :key="a_category.id">
                                    {{a_category.name}}
                                </option>
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
                    <div class="col-md-12" v-if="categoryHasParent != true">
                        <label>Roles</label>
                        <CustomRoleAdd v-if="user_roles" :userRoles="user_roles" @granted-roles="getPermissionGrantedAccess"/>
<!--                        <label>Groups</label>-->

<!--                        <multiselect-->
<!--                                v-model="group_value"-->
<!--                                :options="group_list"-->
<!--                                :multiple="true"-->
<!--                                :close-on-select="true"-->
<!--                                :clear-on-select="true"-->
<!--                                :preserve-search="true"-->
<!--                                placeholder="Pick some"-->
<!--                                :custom-label="groupByName"-->
<!--                                label="name"-->
<!--                                track-by="name"-->
<!--                                :preselect-first="true">-->
<!--                            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} groups selected</span></template>-->
<!--                        </multiselect>-->
<!--                        <span id="groupError" class="text-danger small"></span>-->
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

        name        : "categoryAdd.vue",
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
                categoryList                : '',
                selectedCategory            : '',
                userInfo                    : '',
                logo_file                   : '',
                logo_url                    : '',
                user_roles                  : '',
                roleAccess                  : [],
                parent_role_id              : '',
                categoryHasParent           : false,
                userInformation             : '',
                // group_value                 : [],
                // group_list                  : [],
                categoryData                : {
                    name                    : '',
                },
                validation_error            : {
                    isCategoryNameStatus    : false,
                    isGroupError            : false,
                },
            }
        },
        methods: {
            validateAndSubmit()
            {
                console.log(this.validation_error)
                if (!this.categoryData.name){
                    $('#categoryNameError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#categoryNameError').html("*category name field is required");
                    this.validation_error.isCategoryNameStatus = false;
                }

                // if (this.group_value.length === 0){
                //     $('#groupError').html("*select at least one group");
                //     this.validation_error.isGroupError = false;
                // }

                // if (this.group_value.length > 0){
                //     $('#groupError').html("");
                //     this.validation_error.isGroupError = true;
                // }

                if (this.validation_error.isCategoryNameStatus === true ){
                    //console.log(this.validation_error)
                    this.categoryAdd();
                }

            },
            //client side validation
            // dataValidate()
            // {
            //     // console.log(this.group_value)
            //     let _that = this;
            //     if (_that.group_value.length === 0){
            //         $('#groupError').html("*select at least one group");
            //     }
            //     if (!_that.categoryData.name){
            //         _that.error_messages[0]     = "*The category name is required";
            //     }
            //
            //     else if (_that.categoryData.name && (_that.categoryData.name).length >2 && (_that.categoryData.name).length <100 && _that.group_value.length !== 0){
            //         _that.categoryAdd();
            //     }
            //     else{
            //         _that.error_messages[0]     = "*The name must be between 3 to 100 charecter";
            //     }
            // },
            groupByName ({ name }) {
                return `${name}`
            },
            getParentGroupsAccess()
            {
                let _that   = this;
                let apiUrl  = "categories/";
                axios.get(apiUrl+_that.selectedCategory,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            _that.categoryHasParent = true;
                            _that.group_value = response.data.category_info.groups
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

            // getAllGroups()
            // {
            //     let _that =this;
            //     axios.get('groups',
            //         {
            //             headers: {
            //                 'Authorization': 'Bearer '+localStorage.getItem('authToken')
            //             },
            //             params : {
            //                 isAdmin : 1,
            //                 without_pagination : 1
            //             },
            //         })
            //         .then(function (response) {
            //             if(response.data.status === 200){
            //
            //                 _that.group_list = response.data.group_list;
            //             }
            //         })
            // },
            onLogoFileChange(e)
            {
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
            },
            checkCategoryNameExist(categoryInfo)
            {
                let _that = this;
                let category_info  =  categoryInfo.trim();
                if (category_info == null){
                    $('#categoryNameError').html("*name field is required");
                }
                else if (category_info.length > 2 || category_info.length < 100){
                    axios.post('category/name',
                        {
                            name            : this.categoryData.name,
                        },
                        {
                            headers: {
                                'Authorization': 'Bearer '+localStorage.getItem('authToken')
                            }
                        }).then(function (response) {
                        if(response.data.status_code === 400)
                        {
                            _that.success_message           = "";
                            _that.error_message             = "";
                            $('#categoryNameError').html("*"+response.data.error);

                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
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

                        if (selected_name === "*category name"){
                            this.validation_error.isCategoryNameStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*category name" ){
                            this.validation_error.isCategoryNameStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*category name" ){
                        this.validation_error.isCategoryNameStatus = false;
                    }
                }
            },
            //server validation
            showServerError(errors)
            {

                $('#categoryNameError').html("");
                $('#categoryName').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("name")==true){
                        $('#categoryNameError').html(val)
                        $('#categoryName').css({'border-color': '#FF7B88'});
                    }
                })
            },

            //category add
            categoryAdd()
            {



                let _that = this;
                let formData = new FormData();

                if (_that.roleAccess.length == 0){
                    _that.roleAccess.push(1);
                }

                // console.log(this.logo_file);
                //console.log(_that.group_value)

                formData.append('logo', this.logo_file);
                formData.append('name', this.categoryData.name);
                formData.append('parent_id', this.selectedCategory);

                // formData.append('group_list', JSON.stringify(this.group_value));
                formData.append('role_id', this.roleAccess);
                // formData.append('operation_type', 'insert');

                axios.post('categories', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200){

                        _that.categoryData          = '';
                        _that.selectedCategory      = '';
                        _that.error_messages        = '';
                        _that.success_message       = "Category Added Successfully";

                        _that.$emit('category-slide-close', _that.success_message);
                        // document.body.classList.remove('open-side-slider');
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
            getCategoryList()
            {

                let _that =this;

                axios.get('categories',
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
                            // console.log(response.data);
                            _that.categoryList = response.data.category_list;
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
                this.getCategoryList();
                this.getUserRoles();
                // this.getAllGroups();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
