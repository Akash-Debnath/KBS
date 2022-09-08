<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">


            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Edit Menu</h2>

                <div class="alert-success" v-if="success_message">
                    <span>{{ success_message }}</span>
                </div>

                <div  v-if="error_message">
                    <span class="text-danger">{{ error_message }}</span>
                </div>

                <div class="row">

                    <div class="col-md-12" v-if="menuId ? menu_id=menuId : ''">
                        <div class="form-group">
                            <label for="menuName">Menu Name <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="menu_name" id="menuName" @keyup="checkAndChangeValidation()" @change="checkMenuNameExist(menu_name)" required>
                            <span  id="menuNameError" class="small text-danger menu_name" role="alert"></span>
                        </div>
                    </div>

                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="menuOrder">Menu Order<span class="required">*</span></label>
                            <input class="form-control" type="number" v-model="menu_order" id="menuOrder" @keyup="checkAndChangeValidation()" required>
                            <span  id="menuOrderError" class="small text-danger menu_order" role="alert"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select A Parent</label>
                            <select class="form-control" v-model="menu_parent_id" id="menuParent">
                                <option  value="0">Select A Menu</option>
                                <option v-for="a_menu in menuList" :value="a_menu.id" :key="a_menu.id">
                                    {{a_menu.name}}
                                </option>
                            </select>
                            <span  id="menuParentError" class="small text-danger menu_parent_id" role="alert"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" v-model="menu_status" >
                                <option  value="1">Enable</option>
                                <option  value="0">Disable</option>
                            </select>
                        </div>
                    </div>

                    <!-- <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Image</label>
                            <input type="file" id="files" ref="files" accept="image/*" @change="onLogoFileChange" >
                        </div>
                    </div> -->
<!-- 
                    <div class="col-md-12" v-if="logo_url">
                        <div class="form-group" >
                            <img class="preview" style="height:250px; width: auto" :src="logo_url"/>
                        </div>
                    </div> -->


                    <!-- <div class="col-md-12" v-if="menuHasParent != true">
                        <label>Roles</label>
                        <CustomRoleEdit v-if="user_roles && menuDetails" :userRoles="user_roles" :banneRoleAccess="menuDetails.role_id" @granted-roles="getPermissionGrantedAccess"/>

                    </div> -->

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

        name: "menuEdit.vue",
        props: ['isEditCheck', 'menuId'],

        components:{
            CustomRoleEdit,
            Multiselect
        },

        data() {
            return {
                menu_id          : '',
                menu_parent_id   : '',
                menu_name        : '',
                menu_order        : '',
                menu_status      : '',
                menuDetails      : '',
                tempmenuList     : '',
                menuList         : [],
                error_message        : '',
                success_message      : '',
                token                : '',
                user_roles : '',
                roleAccess          : [],
                group_value                 : [],
                group_list                  : [],
                menuHasParent : false,

                logo_file        : '',
                logo_url         : '',
            }
        },

        methods: {
            getPermissionGrantedAccess(status)
            {
                this.roleAccess = status.split(',');

                // this.getUserRoles();
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

            onLogoFileChange(e) {
                this.logo_file = e.target.files[0];
                this.logo_url = URL.createObjectURL(this.logo_file);
            },

            checkMenuNameExist(menuInfo){

                let _that = this;
                let menu_info  =  menuInfo.trim();
                if (menu_info == null){
                    $('#menuNameError').html("*name field is required");
                }
                else if (menu_info.length > 2 || menu_info.length < 100){
                    axios.post('menu/name',
                        {
                            id              : this.menuDetails.id,
                            name            : this.menu_name,
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
                        // console.log(error);
                    });
                }
            },

            //keyup validation
            checkAndChangeValidation()
            {
                let _that = this;
                if (!_that.menu_name){

                    $('#menuName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#menuNameError').html("*The menu name is required");
                }
                else if (_that.menu_name && (_that.menu_name).length >2 && (_that.menu_name).length <100){
                    $('#menuName').css({
                        'border-color': '#ced4da',
                    });
                    $('#menuNameError').html("");
                }
                else{
                    $('#menuName').css({
                        'border-color': '#FF7B88',
                    });

                    $('#menuNameError').html("*The name must be between 3 to 100 charecter");

                }



                if (!_that.menu_order){

                    $('#menuOrder').css({
                        'border-color': '#FF7B88',
                    });
                    $('#menuOrderError').html("*The order name is required");
                }
                else if (_that.menu_order && (_that.menu_order).length >0 && (_that.menu_order).length <100){
                    $('#menuOrder').css({
                        'border-color': '#ced4da',
                    });
                    $('#menuOrderError').html("");
                }
                else{
                    $('#menuOrder').css({
                        'border-color': '#FF7B88',
                    });

                    $('#menuOrderError').html("*The order must be between 1 to 100 digit");

                }
            },

            showServerError(errors)
            {
                $('#menuNameError').html("");
                $('#menuName').css({'border-color': '#ced4da'});
                errors.forEach(val => {
                    if (val.includes("name")==true){
                        $('#menuNameError').html(val)
                        $('#menuName').css({'border-color': '#FF7B88'});
                    }
                })
            },

            dataValidate()
            {
                let _that = this;

                if (!_that.menu_name){

                    $('#menuName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#menuNameError').html("*The menu name is required");
                }
                else if (_that.menu_name && (_that.menu_name).length >2 && (_that.menu_name).length <100){
                    $('#menuName').css({
                        'border-color': '#ced4da',
                    });
                    $('#menuNameError').html("");
                    _that.menuUpdate();
                }
                else{
                    $('#menuName').css({
                        'border-color': '#FF7B88',
                    });

                    $('#menuNameError').html("*The name must be between 3 to 100 charecter");

                }




                if (!_that.menu_order){

                    $('#menuOrder').css({
                        'border-color': '#FF7B88',
                    });
                    $('#menuOrderError').html("*The menu order is required");
                }
                else if (_that.menu_order && (_that.menu_order).length >0 && (_that.menu_order).length <100){
                    $('#menuOrder').css({
                        'border-color': '#ced4da',
                    });
                    $('#menuOrderError').html("");
                    _that.menuUpdate();
                }
                // else{
                //     $('#menuOrder').css({
                //         'border-color': '#FF7B88',
                //     });

                //     $('#menuOrderError').html("*The menu order must be between 1 to 100 digit");

                // }
            },

            menuUpdate()
            {
                let _that = this;

                let collection;

                collection = _that.roleAccess.join();

                if (!collection) {
                    collection = 1;
                }

                let formData = new FormData();

                formData.append('id', _that.menu_id);
                // formData.append('logo', _that.logo_file);
                formData.append('name', _that.menu_name);
                formData.append('name', _that.menu_order);
                formData.append('status', _that.menu_status);
                formData.append('parent_id', _that.menu_parent_id);
                //formData.append('_method', 'PUT');
                // formData.append('role_id', collection);
                // formData.append('group_list', JSON.stringify(this.group_value));


                axios.put('menus/update',
                {
                    id                  : _that.menu_id,
                    name                : _that.menu_name,
                    order_number        : _that.menu_order,
                    status              : _that.menu_status,
                    parent_id           : _that.menu_parent_id,
                },
                    {   
                        
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    // console.log(response);

                    if (response.data.status_code === 200){
                        _that.menu_name            = '',
                        _that.menu_order           = '',
                            _that.menu_parent_id   = '',
                            _that.error_message    = '';
                        _that.success_message         = "Menu Updated Successfully";
                        _that.$emit('menu-slide-close',_that.success_message);
                    }
                    else if(response.data.status_code === 400)
                    {
                        _that.success_message           = "";
                        _that.error_message             = "";
                        _that.showServerError(response.data.errors);

                    }else{
                        _that.success_message = "";
                        _that.error_message   = "";

                        if ((response.data.error).includes("Parent")){
                            $('#menuParentError').html(  response.data.error );
                            $('#menuParent').css({'border-color': '#FF7B88'});
                        }else {
                            _that.error_message   = response.data.error;
                        }

                    }

                }).catch(function (error) {
                    // console.log(error);
                });

            },
            groupByName ({ name }) {
                return `${name}`
            },
            getMenuList()
            {
                let _that = this;

                axios.get('menus',
                    {
                        headers:
                            {
                                'Authorization': 'Bearer '+localStorage.getItem('authToken')
                            },
                        params :
                            {
                                isAdmin : 1,
                                isRole  : _that.userInformation.roles[0].id
                            },
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            // console.log(response.data.menu_list);
                            _that.menuList   = response.data.menu_list.filter(menu=> menu.id!==_that.menu_id);

                        }
                        else{
                            _that.success_message   = "";
                            _that.error_message    = response.data.error;
                        }
                    })
            },

            getMenuDetails()
            {
                let _that   = this;
                let apiUrl  = "menus/";
                axios.get(apiUrl+_that.menu_id,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            console.log(response.data.menu_info);
                            _that.menuDetails         =  response.data.menu_info;
                            _that.menu_name           =  _that.menuDetails.name;
                            _that.menu_order           =  _that.menuDetails.order_number;
                            _that.menu_status           =  _that.menuDetails.status;
                            _that.menu_parent_id      =  _that.menuDetails.parent_id;
                            if (_that.menu_parent_id !=0){
                                _that.menuHasParent = true;
                            }
                            // _that.logo_url                =  (_that.menuDetails.media).length > 0 ? _that.menuDetails.media[0].url : '';
                            // _that.group_value             = response.data.menu_info.groups


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
                this.menu_id = this.menuId;
                this.getMenuDetails(this.menu_id);
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
