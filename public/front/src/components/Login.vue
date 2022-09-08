<template>
    <div class="login-popup-wrapper" v-if="isHidden">
        <h2 class="signin-title py-10 px-15">Login</h2>
        <div class="login-box py-10 px-15">
            <div class="form-group mb-20">
              <input type="text" v-model="formData.username" class="form-control" placeholder="Username">
          </div>
          <div class="form-group mb-20">
              <input type="password" v-model="formData.password" class="form-control" placeholder="Password">
              <small id="loginError" class="text-lowercase text-small text-danger"></small>
          </div>
          <div class="form-group text-center mb-10">
              <button class="btn btn-primary d-block w-100 rounded-pill px-35 text-white" type="button" @click="isAuthinticate = false,userLogin()">Login</button>
          </div>
        </div>
    </div>
</template>

<script>
    import $ from 'jquery'
    import axios from "axios";

    export default {
        name: "Login",

        props: ['isHidden'],

        data() {
            return {
                formData: {
                    username : 'admin',
                    password : '123456',
                    // visitor : 1
                },
                userInformation : '',
                bannerInformation : '',
                isAuthinticate : false
            }
        },
        methods:{
            userLogin(){
                let _that = this;

                axios.post('visitor-login',
                {
                    username          : this.formData.username,
                    password          : this.formData.password,
                    // visitor          : this.formData.visitor,
                }).then(function (response){
                    console.log(response.data);
                    if (response.data.status_code ==200){
                        // window.location.reload()

                        _that.userInformation = JSON.stringify(response.data.user_info);
                        _that.bannerInformation = JSON.stringify(response.data.banner_info);
                        _that.$emit('authorised',_that.isHidden)


                        sessionStorage.setItem("userToken", response.data.token)
                        sessionStorage.setItem("userInformation", _that.userInformation);
                        sessionStorage.setItem("bannerInformation", _that.bannerInformation);
                        // sessionStorage.setItem("visitorRoles", response.data.user_info.roles[0].id)

                        location.reload()
                        _that.$router.push({name : 'Home'}).catch(() => {});
                        // this.$router.go(_that.$router.currentRoute)
                        // _that.$route.reload();
                    }else{
                        console.log(response.data.messages);
                        $('#loginError').html(response.data.messages)

                        setTimeout(()=>{
                            $('#loginError').html('')
                        },2e3);
                    }
                })
            }
        },
        created() {
            console.log(this.isHidden);
            this.isAuthinticate = this.isHidden;
        }
    }
</script>

<style scoped>
    .login-popup-wrapper {
        position: absolute;
        right: 0;
        top: calc(100% + 10px);
        background: #fff;
        border-radius: 3px;
        box-shadow: rgba(9, 30, 66,0.25) 0px 4px 8px -2px, rgba(9, 30, 66,0.31) 0px 0px 1px;
        overflow: auto;
        min-width: 200px;
        z-index: 20;
    }

    .signin-title {
        background: #dedede;
        color: #000;
        font-size: 18px;
        font-weight: 600;
        border-radius: 3px 3px 0 0;
    }
</style>
