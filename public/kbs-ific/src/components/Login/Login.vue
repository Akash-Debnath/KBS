<template>
    <div class="kbs-login-area">
        <!--**********************************
                     Login Header
         ***********************************-->

        <div class="container">
            <div class="kbs-login-header">
                <div class="kbs-login-logo">
                    <a href="/">
                        <img src="@/assets/images/logo.svg" alt="ific logo">
                    </a>
                </div>

                <div class="kbs-one-click">
                    <a href="">
                        <img src="@/assets/images/one_click.svg" alt="ific_oneclick">
                    </a>
                </div>
            </div>
        </div>
        <!--      end login header-->


        <!--**********************************
                      Login body
         ***********************************-->
        <div class="container">
            <div class="kbs-login-body">
                <div class="kbs-login-form">
                    <div class="text-center text-md-left"><h3>Login</h3>
                        <p>Welcome to IFIC Knowledge Portal <br/>
                            Please put your login credentials below</p></div>
                    <form action="">
                        <center><span id="loginError" class="font-weight-bold  text-danger"></span></center>
                        <label for="kbs-uname">
                            <input type="text"  v-model="userCredentials.username" required autocomplete="off" id="kbs-uname" placeholder="username" >
                            <img src="../../assets/images/user.png" alt="">
                        </label>
                        <br/>
                        <label for="kbs-password">
                            <input type="password"  v-model="userCredentials.password" required autocomplete="off" id="kbs-password" placeholder="password">
                            <img src="../../assets/images/key.png" alt="">
                        </label>

                        <!-- <a href="">Forgot password?</a> -->
                        <button class="mt-4 mb-2" type="button" @click="isAuthinticate = false,userLogin()">Log in</button>
                        <!-- <label for="remember-me">
                            <input type="checkbox" name="" id="remember-me">
                            Remember Me
                        </label> -->

                    </form>
                </div>

                <div class="kbs-right-image">
                    <img src="../../assets/images/login-right-image.svg" alt="">
                </div>
            </div>
        </div>
        <!--      end login body-->


        <!--**********************************
                     Copyright
         ***********************************-->
        <div class="container">
            <div class="kbs-footer-copyright">
                <small> &copy; 2022 IFIC All Right Reserved.</small>
            </div>
        </div>
        <!--        end copyright-->
    </div>

    <div class="footer-svg">
        <FooterBG/>
    </div>
</template>

<script >
    import $ from 'jquery'
    import {ref, computed, onBeforeUpdate, onMounted,defineComponent} from "vue";
    import {ApiCallMaker} from "../../api/ApiCallMaker";
    import FooterBG from "../icons/footerBg.vue";
    import { useRoute } from 'vue-router';


    export default defineComponent({
        components: {FooterBG},
        setup() {
            const userCredentials = ref({
                username:'',
                password:''
            });
            const isAuthinticate = ref(false);


            async function userLogin() {

                const response = await ApiCallMaker('POST', 'visitor-login',userCredentials.value, '', '');
                if (response && response.data.status_code == 200) {
                     sessionStorage.setItem("userToken", response.data.token);
                     sessionStorage.setItem("userInformation", JSON.stringify(response.data.user_info));
                    //  this.route.push({name : 'quiz'}).catch(() => {})
                    this.$router.push({name : 'quiz'}).catch(() => {});

                } else {
                     //console.log(response.data.messages);
                      $('#loginError').html(response.data.messages);
                      setTimeout(()=>{
                            $('#loginError').html('')
                        },2e3);
                }
            }
            return {userCredentials, userLogin}
        }
    });
</script>

<style lang="scss">
    .kbs-login-area {
        min-height: 100vh;
        width: 100vw;
        background-image: url("src/assets/images/bg.svg");
        background-size: cover;
        background-position: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        overflow-y: auto;
    }

    .kbs-login-header {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        position: relative;
        margin-top: 50px;
        overflow:hidden;
        @media all and (max-width: 768px){
            margin-top: 10px;
            justify-content: center;
            align-items: center;
            gap:20px;
        }
    }

    .kbs-one-click{
        @media all and (max-width: 768px){
            display: none;
        }
    }

    .kbs-login-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .kbs-right-image {
        img {
            max-width: 500px;
            height: auto;
        }
        @media all and (max-width: 600px){
            display: none;
        }
    }

    .kbs-login-form {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        flex-grow: 1;
        @media all and (max-width: 768px) {
            align-items: center;
        }

        h3 {
            font-weight: 600;
            color: #000000;
            padding-left: 0.5rem;
            font-size: 32px;
        }

        p {
            color: #474747;
            padding-left: 0.5rem;
        }

        form {
            max-width: 450px;
            width: 100%;

            label[for="kbs-uname"], label[for="kbs-password"] {
                position: relative;
                display: block;
                width: 100%;

                input {
                    display: block;
                    background-color: #ffffff;
                    padding: 1rem 1rem 1rem 3rem;
                    border: 1px solid #00793F;
                    width: 100%;
                    border-radius: 3rem;

                    &:focus-visible {
                        outline: none;
                    }
                    /* Change the white to any color */
                    &:-webkit-autofill,
                    &:-webkit-autofill:hover,
                    &:-webkit-autofill:focus,
                    &:-webkit-autofill:active{
                        -webkit-box-shadow: 0 0 0 30px white inset !important;
                    }
                }

                img {
                    position: absolute;
                    left: 15px;
                    top: 50%;
                    transform: translateY(-50%);
                }
            }


            label[for="remember-me"] {
                display: flex;
                align-items: center;
                gap: 10px;
                color: #00793F;
                padding-left: 0.5rem;

                input {
                    display: inline-block;
                    width: auto;
                    margin: 0;
                    accent-color: #00793F;
                    border: 1px solid #00793F;
                }
            }

            a {
                display: block;
                text-align: right;
                color: #00793F;
                margin: 1rem 0;
                font-weight: 500;
                font-size: 13px;
                padding-right: 0.5rem;
            }

            button {
                display: block;
                background-color: #00793F;
                border: none;
                width: 100%;
                color: #ffffff;
                border-radius: 1rem;
                padding: 0.9rem;
                font-size: 1rem;
                font-weight: bold;
                margin-bottom: 1rem;
            }
        }
    }


    .kbs-footer-copyright {
        position: relative;
        bottom: 100px;

        small {
            color: #a3a3a3;
        }
    }

    .footer-svg {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 2050;
    }
</style>






















