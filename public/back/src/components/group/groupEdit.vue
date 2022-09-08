<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">

            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Group Edit</h2>

                <div v-if="success_message" class="alert alert-success" role="alert">
                    {{ success_message }}
                </div>
                <div v-if="error_message" class="alert alert-danger" role="alert">
                    {{ error_message }}
                </div>


                <div class="row">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="group_name">Group Name <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="group_name" id="group_name" placeholder="Enter Group Name"
                                   @keyup="checkAndChangeValidation(group_name, '#group_name', '#groupNameError', '*group name')" required>
                            <span id="groupNameError" class="text-danger small"></span>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="group_name">Group Description</label>
                            <textarea class="form-control" rows="5" v-model="group_description"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="group_name">Role List</label>
                            <multiselect
                                    v-model="group_role_value"
                                    :options="role_list"
                                    :multiple="true"
                                    :close-on-select="true"
                                    :clear-on-select="true"
                                    :preserve-search="true"
                                    placeholder="Pick some"
                                    :custom-label="roleByName"
                                    label="name"
                                    track-by="name"
                                    :preselect-first="true">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} roles selected</span></template>
                            </multiselect>
                            <span id="groupRoleError" class="text-danger small"></span>
                        </div>
                    </div>

                </div>

                <div class="form-group text-right">
                    <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Update</button>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    import axios from "axios";
    import $ from "jquery";

    export default {
        name: "groupEdit.vue",
        props: ['isEditCheck', 'roleId'],

        components:{
            Multiselect
        },

        data() {
            return {
                success_message         : '',
                error_message           : '',
                group_name              : '',
                group_description       : '',
                token                   : '',
                groupDetails            : '',
                group_id                :  '',
                group_role_value        : [],
                role_list               : [],
                validation_error        : {
                    isGroupNameStatus   : true,
                    isGroupRolesError   : true,
                },
                filter                  : {
                    isAdmin : 1
                }
            }
        },

        methods: {

            roleByName ({ name }) {
                return `${name}`
            },

            validateAndSubmit()
            {

                if (!this.group_name){
                    $('#group_name').css({
                        'border-color': '#FF7B88',
                    });
                    $('#groupNameErrorNameError').html("*group name field is required");
                }

                if (this.group_role_value.length === 0){
                    this.validation_error.isGroupRolesError = false;
                    $('#groupRoleError').html("*Select at least one role");
                }

                if (this.group_role_value.length > 0){
                    this.validation_error.isGroupRolesError = true;
                }

                if (this.validation_error.isGroupNameStatus === true && this.validation_error.isGroupRolesError === true){
                    // console.log(this.validation_error)
                    this.updateGroup();
                }
            },
            showServerError(errors)
            {
                $('#nameError').html("");

                $('#name').css({'border-color': '#ced4da'});

                errors.forEach(val=>{
                    console.log(val);
                    if (val.includes("name")===true){
                        $('#groupNameError').html(val)
                        $('#group_name').css({'border-color': '#FF7B88'});
                    }

                })
            },

            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                        if (selected_name === "*group name"){
                            this.validation_error.isGroupNameStatus = false;
                        }

                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*group name" ){
                            this.validation_error.isGroupNameStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*group name" ){
                        this.validation_error.isGroupNameStatus = false;
                    }
                }
            },


            showServerError(errors)
            {
                $('#nameError').html("");

                $('#name').css({'border-color': '#ced4da'});

                errors.forEach(val=>{
                    console.log(val);
                    if (val.includes("name")===true){
                        $('#roleNameError').html(val)
                        $('#role_name').css({'border-color': '#FF7B88'});
                    }

                })
            },

            updateGroup()
            {
                let _that       = this;

                axios.put('groups/'+_that.group_id,
                    {
                        name                : this.group_name,
                        description         : this.group_description,
                        role_list           : this.group_role_value.length > 0 ? this.group_role_value : null,
                    },
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status== 200)
                    {
                        _that.group_name                     = '';
                        _that.error_message                 = '';
                        _that.success_message               = "Group updated successfully!";

                        _that.$emit('group-slide-close', _that.success_message);
                    }
                    else
                    {
                        _that.success_message = "";
                        _that.error_message   = "";
                        _that.showServerError(response.data.errors);
                    }

                }).catch(function (error) {
                    console.log(error);
                });

            },

            geRolesDetails()
            {
                let _that                               = this;
                let rolesID                             = this.group_id;
                axios.get("groups/"+rolesID,
                    {
                        headers: {
                            'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status === 200) {
                            _that.group_name            = response.data.group_info.name
                            _that.group_description     = response.data.group_info.description
                            _that.group_role_value      = response.data.group_info.roles
                        } else {
                            _that.success_message       = "";
                            _that.error_message         = response.data.error;
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

                            // console.log(response.data.role_list);
                            _that.role_list = response.data.role_list

                        }
                    })
            },
        },
        created() {
            this.category_id = this.categoryId;
            this.group_id = this.roleId;
            this.geRolesDetails();
            this.getUserRoles();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
