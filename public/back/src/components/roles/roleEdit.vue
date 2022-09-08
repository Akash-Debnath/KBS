<template>
  <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isEditCheck">
    <div class="right-sidebar-content-area px-2">

      <div class="form-wrapper">
        <h2 class="section-title text-uppercase mb-20">Role Update</h2>

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
              <input class="form-control" type="text" v-model="storeName" id="role_name" placeholder="Enter Role Name" @change="checkRoleNameExist(storeName)" @keyup="checkAndChangeValidation(storeName, '#role_name', '#roleNameError', '*role name')" required>
              <span id="roleNameError" class="text-danger small"></span>
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label> Edit Permissions</label>
              <ul class="list-unstyled permission-list m-0 p-0">
                <li  class="text-left pb-2" v-for="permission in allPermissions" :key="permission.id">
                  <div v-if="allSelectedPermissionIds.includes(permission.id)" class="d-flex align-items-center">
                    <input  checked type="checkbox" :value="permission.id"  v-model="selectedCheckboxes" @change="showSelectedItem()"> <label class="mb-0 ml-2"> {{ permission.name }} </label>
                  </div>
                  <div v-else class="d-flex align-items-center">
                    <input type="checkbox" v-model="selectedCheckboxes" :value="permission.id"  @change="showSelectedItem()"> <label class="mb-0 ml-2"> {{ permission.name }} </label>
                  </div>
                </li>
              </ul>

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
  import axios from "axios";
  import $ from "jquery";

  export default {
    name: "rolesEdit.vue",
    props: ['isEditCheck', 'roleId'],

    data() {
      return {
        success_message : '',
        error_message   : '',
        token           : '',
        rolesDetails    : '',
        role_id         :  '',
        allRoles  :'',
        allPermissions  : [],
        roles_info      :'',
        selectedCheckboxes :[],
        allSelectedPermissionIds : [],
        roles  :'',
        allAccess : [],
        storeName :'',

        validation_error :{
          isRoleNameStatus : true,
        },

        filter : {
          isAdmin : 1
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
                    id              : this.role_id,
                    name            : this.storeName,
                  },
                  {
                    headers: {
                      'Authorization': 'Bearer '+localStorage.getItem('authToken')
                    }
                  }).then(function (response) {
            console.log(response)
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

      showSelectedItem()
      {
        console.log(this.selectedCheckboxes);
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

        if (!this.storeName){
          $('#role_name').css({
            'border-color': '#FF7B88',
          });
          $('#roleNameError').html("*role name field is required");
        }

        if (this.validation_error.isRoleNameStatus === true){
          this.updateRole();
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

      updateRole()
      {
        let _that       = this;
        let rolesID     = this.role_id;

        // console.log(rolesID+" "+ _that.storeName+" "+_that.selectedCheckboxes);

        axios.put('roles/update',
                {
                  id          : rolesID,
                  name        : this.storeName,
                  permission  : this.selectedCheckboxes
                },
                {
                  headers: {
                    'Authorization': 'Bearer '+localStorage.getItem('authToken')
                  }
                }).then(function (response) {
          if (response.data.status_code == 200)
          {
            _that.storeName                     = '';
            _that.selectedCheckboxes            = [];
            _that.error_message                 = '';
            _that.success_message               = "Role updated successfully with permission!";

            _that.$emit('role-slide-close', _that.success_message);
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
        let _that       = this;
        let rolesID     = this.role_id;
        axios.get("roles/"+rolesID,
                {
                  headers: {
                    'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                  },
                })
                .then(function (response) {
                  console.log(response)
                  if (response.data.status_code === 200) {
                    _that.rolesDetails          = response.data.role_info;
                    _that.roles                 = _that.rolesDetails.id;
                    _that.storeName             = _that.rolesDetails.name;
                    _that.allAccess             = _that.rolesDetails.permissions;
                    // console.log(_that.allAccess);
                    _that.allAccess.forEach( anAccessId =>
                    {
                      _that.allSelectedPermissionIds.push(anAccessId.id)
                      console.log(_that.allSelectedPermissionIds);
                    });
                    _that.selectedCheckboxes    = _that.allSelectedPermissionIds;
                    console.log(_that.selectedCheckboxes);
                  } else {
                    _that.success_message       = "";
                    _that.error_message         = response.data.error;
                  }
                })
      },

      getAllPermission()
      {
        let _that   = this;
        axios.get('permissions',
                {
                  headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('authToken')
                  },

                })
                .then(function (response) {
                  if (response.data.status_code === 200) {
                    //_that.allPermissions = response.data.permission_list;

                    _that.allPermissions    = response.data.permission_list;
                    /*console.log("before splice");
                    console.log( _that.allPermissions);

                    (_that.allPermissions).forEach( aRole => {

                      (_that.allAccess).forEach( anAccess => {
                        if ( anAccess.id == aRole.id)
                        {
                          console.log("in for each loop")
                          _that.allPermissions.splice(_that.allPermissions.indexOf(aRole.id), 1);
                        }
                      });

                    });*/

                    // console.log("after splice");
                    // console.log( _that.allPermissions);
                    //  _that.allPermissions    = tempRoleData;
                  } else {
                    _that.success_message = "";
                    _that.error_message = response.data.error;
                  }
                })
      },

      getRolesList()
      {
        let _that       = this;
        axios.get('roles',
                {
                  headers: {
                    'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                  },

                })
                .then(function (response) {
                  if(response.data.status_code === 200){
                    //_that.allRoles = response.data.role_list;
                  }
                  else{
                    _that.success_message   = "";
                    _that.error_message     = response.data.error;
                  }
                })
      }
    },
    created() {
      this.category_id = this.categoryId;
      this.role_id = this.roleId;
      console.log(this.role_id);
      this.getRolesList();
      this.geRolesDetails();
      this.getAllPermission();
    }
  }
</script>

<style scoped>

</style>
