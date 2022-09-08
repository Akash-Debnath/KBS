<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Menu</h2>
                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="menuName">Menu Name <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="menuData.name" id="menuName" @change="checkMenuNameExist(menuData.name)" @keyup="checkAndChangeValidation(menuData.name,'#menuName', '#menuNameError', '*menu name')" placeholder="Enter Menu Name" required>
                            <span id="menuNameError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select A Parent</label>
                            <select class="form-control" v-model="selectedMenu" >
                                <option disabled value="">Select A Menu</option>
                                <option v-for="a_menu in menuList" :value="a_menu.id" :key="a_menu.id">
                                    {{a_menu.name}}
                                </option>
                            </select>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="menuOrder">Menu Order <span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="menuData.order_number" id="menuOrder" @keyup="checkAndChangeValidation(menuData.order_number,'#menuOrder', '#menuOrderError', '*order no')" placeholder="Enter Order Number" required>
                            <span id="menuOrderError" class="text-danger small"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="menuData.status" >
                                <option  value="1">Enable</option>
                                <option  value="0">Disable</option>
                            </select>
                        </div>
                    </div>

                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Image</label>
                            <input type="file" id="files" ref="files" @change="onLogoFileChange" accept="image/*">
                        </div>
                    </div> -->
                    <!-- <div class="col-md-12" v-if="logo_url">
                        <div class="form-group" >
                            <img class="preview" style="height:250px; width: auto" :src="logo_url"/>
                        </div>
                    </div> -->
                    <!-- <div class="col-md-12" v-if="categoryHasParent != true">
                        <label>Roles</label>
                        <CustomRoleAdd v-if="user_roles" :userRoles="user_roles" @granted-roles="getPermissionGrantedAccess"/>
                    </div> -->
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

        name        : "menuAdd.vue",
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
                menuList                    : '',
                selectedMenu                : '',
                userInfo                    : '',
                logo_file                   : '',
                logo_url                    : '',
                user_roles                  : '',
                roleAccess                  : [],
                parent_role_id              : '',
                categoryHasParent           : false,
                userInformation             : '',

                menuData   : {
                    name                    : '',
                    order_number            : '',
                    status                  : '1',
                },
                validation_error  : {
                    ismenuNameStatus        : false,
                    ismenuOrderStatus       : false,
                    isGroupError            : false,
                },
            }
        },
        methods: {
            validateAndSubmit()
            {
                // console.log(this.validation_error)
                if (!this.menuData.name){
                    $('#menuNameError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#menuNameError').html("*menu name field is required");
                    this.validation_error.ismenuNameStatus = false;
                }


                if (!this.menuData.order_number){
                    $('#menuOrderError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#menuOrderError').html("*menu order field is required");
                    this.validation_error.ismenuOrderStatus = false;
                }

                // if (this.group_value.length === 0){
                //     $('#groupError').html("*select at least one group");
                //     this.validation_error.isGroupError = false;
                // }

                // if (this.group_value.length > 0){
                //     $('#groupError').html("");
                //     this.validation_error.isGroupError = true;
                // }

                if (this.validation_error.ismenuNameStatus === true && this.validation_error.ismenuOrderStatus === true){
                    //console.log(this.validation_error)
                    this.menuAdd();
                }

            },
           
            groupByName ({ name }) {
                return `${name}`
            },

            // getParentGroupsAccess()
            // {
            //     let _that   = this;
            //     let apiUrl  = "categories/";
            //     axios.get(apiUrl+_that.selectedMenu,
            //         {
            //             headers: {
            //                 'Authorization': 'Bearer ' + localStorage.getItem('authToken')
            //             },
            //         })
            //         .then(function (response) {
            //             if (response.data.status_code === 200) {
            //                 _that.categoryHasParent = true;
            //                 _that.group_value = response.data.category_info.groups
            //             }
            //         })
            // },
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
            checkMenuNameExist(menuInfo)
            {
                let _that = this;
                let menu_info  =  menuInfo.trim();
                if (menu_info == null){
                    $('#menuNameError').html("*name field is required");
                }
                else if (menu_info.length > 2 || menuInfo.length < 100){
                    axios.post('menu/name',
                        {
                            name            : this.menuData.name,
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
                            $('#menuNameError').html("*"+response.data.error);

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

                        if (selected_name === "*menu name"){
                            this.validation_error.ismenuNameStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*menu name" ){
                            this.validation_error.ismenuNameStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*menu name" ){
                        this.validation_error.ismenuNameStatus = false;
                    }
                }





                if (selected_data.length >0) {
                    if (selected_data.length <1){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 1 digit");

                        if (selected_name === "*order no"){
                            this.validation_error.ismenuOrderStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*order no" ){
                            this.validation_error.ismenuOrderStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*order no" ){
                        this.validation_error.ismenuOrderStatus = false;
                    }
                }
            },
            //server validation
            showServerError(errors)
            {
                console.log('errros from server error', errors);
                $('#menuNameError').html("");
                $('#menuName').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("name")==true){
                        $('#menuNameError').html(val)
                        $('#menuName').css({'border-color': '#FF7B88'});
                    } 

                    if (val.includes("order number")==true){
                        $('#menuOrderError').html(val)
                        $('#menuOrder').css({'border-color': '#FF7B88'});
                    }

                })
            },

            //category add
            menuAdd()
            {

                

                let _that = this;
                let formData = new FormData();

                if (_that.roleAccess.length == 0){
                    _that.roleAccess.push(1);
                }

                // console.log(this.logo_file);
                // console.log(_that.group_value)

                //formData.append('logo', this.logo_file);
                formData.append('name', this.menuData.name);
                formData.append('order_number', this.menuData.order_number);
                formData.append('status', this.menuData.status);
                formData.append('parent_id', this.selectedMenu);

                // formData.append('group_list', JSON.stringify(this.group_value));
                formData.append('role_id', this.roleAccess);
                // formData.append('operation_type', 'insert');

                axios.post('menus', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200){

                        _that.menuData          = '';
                        _that.selectedMenu      = '';
                        _that.error_messages        = '';
                        _that.success_message       = "Menu Added Successfully";

                        _that.$emit('menu-slide-close', _that.success_message);
                        // document.body.classList.remove('open-side-slider');
                    }else if(response.data.status_code === 400){

                        _that.success_message       = "";
                        _that.error_message         = "";
                        _that.showServerError(response.data.errors);
                        // console.log(response.data.errors);
                    }else{

                        _that.success_message       = "";
                        _that.error_message         = response.data.message;
                    }
                }).catch(errors => console.log(errors));

            },
            //category list
            getMenuList()
            {

                let _that =this;

                axios.get('menus',
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
                            _that.menuList = response.data.menu_list;
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
                this.getMenuList();
                this.getUserRoles();
                // this.getAllGroups();
            }
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
