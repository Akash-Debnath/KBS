<template>
    <div class="">
        <header class="header">
            <nav class="navbar navbar-expand-md navbar-light bg-light">
                <div class="container position-relative">
                    <div class="logo">
                        <router-link class="nav-item" :to="{ name: 'Home'}">
                            <!--              this.static_image['newlogo']-->
                            <img :src="frontPageData ? frontPageData.logo : this.static_image['newlogo']" alt="logo">

                        </router-link>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="mainNavigation">
                        <ul class="navbar-nav ml-auto align-items-center">
                            <li class="nav-item" v-if="userInformation">
                                <router-link class="nav-link px-0 py-0" :to="{ name: 'CategoryList', params: { categorySlug: recentCategory.slug }}">
                                    <span>CATEGORIES</span>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="{ name: 'Quiz'}">
                                    <span>QUIZ</span>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link class="nav-link" :to="{ name: 'Faq'}">
                                    <span>FAQ</span>
                                </router-link>
                            </li>




                            <li class="nav-item dropdown" v-if="isAuthinticate">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"> <span class="text-uppercase" v-if="userInformation"> {{userInformation.username}} </span> <i class="fa fa-user"></i></a>

                                <div class="dropdown-menu slideDownIn">
<!--                                    <span class="dropdown-item" data-toggle="modal" data-target="#profileDetails" @click="isProfile=true">Profile</span>-->

                                    <span class="dropdown-item profile-view-btn">
                                        <router-link :to="{ name: 'UserQuizHistory'}"><span style="color:#868e96">Quiz Histories</span></router-link>
                                    </span>
                                    <a class="dropdown-item" href="#" @click.prevent="userLogOff">Logout</a>
                                </div>
                            </li>

                            <li class="nav-item" v-else><span  @click="isHidden = !isHidden" class="nav-link"><span>LOGIN</span></span></li>
                        </ul>
                    </div>
                    <Login v-if="isHidden" :isHidden="isHidden" @authorised="getDataFromLogin"/>
                </div>
            </nav>
        </header>
        <div class="totop">
            <i class="fa fa-angle-up" aria-hidden="true"></i>
        </div>
<!--        <ProfileDetails v-if="isProfile" @authorised="getDataFromLogin" />-->
    </div>
</template>
<script>
    import $ from 'jquery'
    import axios from "axios";
    import Login from "@/components/Login";
    // import ProfileDetails from "@/components/ProfileDetails";

    // window scroll
    $(window).scroll(function() {
        let scroll = $(window).scrollTop();
        // Scroll to top
        if (scroll) {
            $('.header').addClass('fixed-header');
        } else {
            $('.header').removeClass('fixed-header');
        }

        if (scroll > 500) {
            $('.totop').css('bottom', '60px');
        } else {
            $('.totop').css('bottom', '-50px');
        }
    });

    $(document).on('click', '.totop', function(){
        $('html,body').animate({
            scrollTop: 0
        }, 1000);
    });

    $(document).ready(function() {
        $('.profile-view-btn').on('click', ()=> {
            $("#profileDetails").modal();
        });
    });

    // Responsive menu
    $(document).on('click', '#mainNavigation .nav-link', () => {
        if($(window).width() < 767){
            $('.navbar-toggler').trigger('click');
        }
    });

    export default {
        name: "Menu",
        components : {
            Login,
            // ProfileDetails
        },
        data(){
            return{
                frontPageData : '',
                userInformation : '',
                bannerInformation : '',
                recentCategory : '',
                userToken: '',
                isAuthinticate : false,
                isHidden: false,
                isProfile : false,
                static_image : []
            }
        },

        methods: {
            userLogOff(){
                let _that = this;
                let formData = new FormData();
                formData.append('id',  _that.userInformation.id);
                formData.append('tokenId',  _that.userToken);

                axios.post('logout',formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+_that.userToken
                        }
                    }
                ).then(function (response) {

                    if (response.data.status_code === 200){
                        _that.isAuthinticate = false;
                        sessionStorage.removeItem('userInformation')
                        sessionStorage.removeItem('bannerInformation')
                        sessionStorage.removeItem('userToken')
                        location.reload()
                        _that.$router.push({name : 'Home'})
                    }else{
                        _that.success_message           = "";
                        _that.error_message             = response.data.message;
                    }
                });
            },
            getDataFromLogin(status){
                console.log(status);
                this.isHidden = false;
                this.isAuthinticate = true;
            },
            getPageDecorationData()
            {
                let _that =this;
                axios.get('front-page-config', { cache: false })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            _that.frontPageData = response.data.page_config_info;
                        }
                    })
            },
            getLatestCategory(){
                let _that = this;
                axios.get('latest-category', { cache: false })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response)
                            _that.recentCategory = response.data.category_info;
                        }
                    })
            },
            getStaticMedia()
            {
                // this.static_image['category'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
                // this.static_image['article'] = axios.defaults.baseURL.replace('api/','')+'static_media/no-image.png';
                // this.static_image['banner'] = axios.defaults.baseURL.replace('api/','')+'static_media/banner.png';
                this.static_image['newlogo'] = axios.defaults.baseURL.replace('api/','')+'static_media/new-logo.png';
                this.static_image['smalllogo'] = axios.defaults.baseURL.replace('api/','')+'static_media/small-logo.png';
            },
        },
        created() {
            this.getPageDecorationData();
            this.getStaticMedia();

            if (sessionStorage.userInformation){
                // console.log(sessionStorage.visitorID);
                this.getLatestCategory();
                this.isAuthinticate = true;
                this.userInformation = JSON.parse(sessionStorage.userInformation);
                this.bannerInformation = JSON.parse(sessionStorage.bannerInformation);
                this.userToken = sessionStorage.userToken;
            }
            // console.log(this.isAuthinticate);
        }
    }
</script>
<style scoped>
    .nav-link,
    .profile-view-btn {
        cursor: pointer;
        transition: all 0.4s;
    }
    .dropdown-item{
        cursor: pointer;
    }
</style>
