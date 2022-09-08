<template>
    <div class="right-sidebar-content-wrapper position-relative" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Group</h2>
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
                            <input class="form-control" type="text" v-model="group_name" id="group_name" placeholder="Enter Group Name" @keyup="checkAndChangeValidation(group_name, '#group_name', '#groupNameError', '*group name')" required>
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

                <div class="col-md-12 mt-15">
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
    import axios from "axios";
    import $ from "jquery";
    export default {
        name        : "groupAdd.vue",
        props       : ['isAddCheck'],
        components:{
            Multiselect
        },
        data() {
            return {
                success_message         : '',
                error_message           : '',
                token                   : '',
                group_name              : '',
                group_description       : '',
                group_role_value        : [],
                role_list               : [],
                validation_error        : {
                    isGroupNameStatus   : false,
                    isGroupRolesError   : false,
                },
                filter                  : {
                    isAdmin             : 1
                }
            }
        },
        methods: {
            roleByName ({ name }) {
                return `${name}`
            },
            validateAndSubmit()
            {
                console.log(this.group_role_value)
                if (!this.group_name){
                    $('#group_name').css({
                        'border-color': '#FF7B88',
                    });
                    $('#groupNameError').html("*group name field is required");
                }

                if (this.group_role_value.length === 0){
                    this.validation_error.isGroupRolesError = false;
                    $('#groupRoleError').html("*Select at least one role");
                }

                if (this.group_role_value.length > 0){
                    $('#groupRoleError').html("");
                    this.validation_error.isGroupRolesError = true;
                }

                if (this.validation_error.isRoleNameStatus === true && this.validation_error.isGroupRolesError === true){
                    //console.log(this.validation_error)
                    this.addGroup();
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

                        if (selected_name === "*group name"){
                            this.validation_error.isRoleNameStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*group name" ){
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
            showServerError(errors)
            {
                $('#nameError').html("");
                $('#name').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("name")===true){
                        $('#roleNameError').html(val)
                        $('#role_name').css({'border-color': '#FF7B88'});
                    }
                })
            },
            addGroup()
            {
                let _that = this;
                axios.post('groups',
                    {
                        name                      : this.group_name,
                        description               : this.group_description ? this.group_description : null,
                        role_list                 : this.group_role_value.length > 0 ? this.group_role_value : null,
                    },
                    {
                        headers: {
                            'Authorization'       : 'Bearer ' + localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status === 201) {
                        _that.group_name            = '';
                        _that.success_message       = "Group added successfully!";
                        _that.$emit('group-slide-close', _that.success_message);
                    }else if(response.data.status === 400){
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

                            console.log(response.data.role_list);
                            _that.role_list = response.data.role_list

                        }
                    })
            },
        },

        created() {
            this.getUserRoles();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style scoped>

</style>
