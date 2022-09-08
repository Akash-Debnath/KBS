<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Role</h2>
                <div v-if="success_message" class="alert alert-success" role="alert">
                    {{ success_message }}
                </div>
                <div v-if="error_message" class="alert alert-danger" role="alert">
                    {{ error_message }}
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="role_name">Role Name <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="roles" id="role_name" placeholder="Enter Role Name" @change="checkRoleNameExist(roles)" @keyup="checkAndChangeValidation(roles, '#role_name', '#roleNameError', '*role name')" required>
                            <span id="roleNameError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label> Add Permissions</label><span id="permissionError" class="small"> </span>
                            <ul class="list-unstyled permission-list m-0 p-0">
                                <li v-for="permission in allPermissions" :key="permission.id" class="text-left pb-2">
                                    <input type="checkbox" v-model="selectedCheckboxes" :value="permission.id" v-bind:id="permission.id" > <label class="pl-2 mb-0"> {{ permission.name }} </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-group text-right">
                    <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import $ from "jquery";
    export default {
        name        : "rolesAdd.vue",
        props       : ['isAddCheck'],
        data() {
            return {
                success_message         : '',
                error_message           : '',
                token                   : '',
                rolesDetails            : '',
                roles_ID                : '',
                allRoles                : '',
                allPermissions          : '',
                roles_info              : '',
                roles                   : '',
                selectedCheckboxes      : [],
                isIndex                 : '',
                validation_error        : {
                    isRoleNameStatus    : false,
                },
                filter                  : {
                    isAdmin             : 1
                }
            }
        },
        methods: {
            checkRoleNameExist(roleInfo)
            {
                let _that = this;
                let role_info  =  roleInfo.trim();
                if (role_info == null){
                    $('#roleNameError').html("*name field is required");
                }
                else if (role_info.length > 2 || role_info.length < 100){
                    axios.post('role/name',
                        {
                            name            : this.roles,
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
                            $('#roleNameError').html("*"+response.data.error);

                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            },
            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                        if (selected_name === "*role name"){
                            this.validation_error.isRoleNameStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*role name" ){
                            this.validation_error.isRoleNameStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*role name" ){
                        this.validation_error.isRoleNameStatus = false;
                    }
                }
            },
            validateAndSubmit()
            {
                if (!this.roles){
                    $('#role_name').css({
                        'border-color': '#FF7B88',
                    });
                    $('#roleNameError').html("*role name field is required");
                }

                if (!this.selectedCheckboxes.length){
                    $('#permissionError').html(" *permission field is required");
                    $('#permissionError').css({
                        'color': '#FF7B88',
                    });
                }

                if (this.validation_error.isRoleNameStatus === true){
                    //console.log(this.validation_error)
                    this.addRole();
                }
            },
            showServerError(errors)
            {
                $('#nameError').html("");

                $('#name').css({'border-color': '#ced4da'});

                console.log(errors);

                errors.forEach(val=>{
                    console.log(val);
                    if (val.includes("name")===true){
                        $('#roleNameError').html(val)
                        $('#role_name').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("permission")==true){
                        $('#permissionError').html(" "+val)
                        $('#permissionError').css({'color': '#FF7B88'});
                    }
                })
            },
            addRole()
            {
                let _that = this;
                axios.post('roles',
                    {
                        name            : this.roles,
                        permission      : this.selectedCheckboxes
                    },
                    {
                        headers: {
                            'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code === 200) {
                        _that.roles                 = '';
                        _that.selectedCheckboxes    = [];
                        _that.success_message       = "Role added successfully with permission!";
                        _that.$emit('role-slide-close', _that.success_message);


                    }else if(response.data.status_code === 400){
                        _that.success_message       = "";
                        _that.error_message         = "";
                        _that.showServerError(response.data.errors);

                    }else{
                        _that.success_message       = "";
                        _that.error_message         = response.data.message;
                    }

                }).catch(function (error) {
                    console.log(error);
                });
            },
            getAllPermission()
            {
                let _that           = this;
                _that.isLoading     = true;
                axios.get('permissions',
                    {
                        headers: {
                            'Authorization' : 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        _that.isLoading                 = false;
                        if (response.data.status_code === 200) {
                            _that.allPermissions        = response.data.permission_list;
                        } else {
                            _that.success_message       = "";
                            _that.error_message         = response.data.error;
                        }
                    })
            },
        },
        created()
        {
            this.getAllPermission();
        }
    }
</script>

<style scoped>

</style>
