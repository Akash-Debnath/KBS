<template>
    <!--**********************************
                   Top Navigation
      ***********************************-->
    <div class="g-navigation bg-primary" v-on:scroll="handleScroll">
        <div class="container-fluid p-0">
            <nav id="navbar_top" class="navbar navbar-expand-lg navbar-dark bg-primary">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                        aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <router-link :to="{ name: 'home'}" class="nav-link">Home <span
                                class="sr-only">(current)</span></router-link>
                        </li>
                        <!-- <li class="nav-item" v-for="item in menus" :key="item.id"><a class="nav-link" href="/deposits">{{item.name}}</a></li> -->
                        <span v-for="item in menus" :key="item.id">


                                <li class="nav-item dropdown" v-if="item.children!=''">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                                       aria-expanded="false">{{ item.name }}</a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <!-- <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a> -->
                                        <router-link v-for="subitem in item.children" :key="subitem.id"
                                                     :to="{ name: 'pages', params: { slug: subitem.slug }}"
                                                     class="dropdown-item">{{ subitem.name }}</router-link>
                                    </div>
                                </li>

                                <li class="nav-item" v-else>
                                    <router-link :to="{ name: 'pages', params: { slug: item.slug }}"
                                                 class="nav-link">{{ item.name }}</router-link>
                                </li>
                            </span>


                    </ul>

                    <div class="ml-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <router-link :to="{ name: 'faq'}" class="nav-link">FAQ</router-link>
                            </li>
                            <!-- <li class="nav-item">
                                <router-link :to="{ name: 'quiz'}" class="nav-link">Quiz</router-link>
                            </li> -->

                            <li class="nav-item">

                            </li>
                        </ul>
                    </div>

                </div>
            </nav>
        </div>
    </div>
    <!--End top Navigation-->
</template>

<script>
export default {
    name: "Navigation",

    props: {
        menus: {type: Array},
    },
    /*
    * @mominriyadh
    * @Desc : Navigation Kept Fixed Top on scroll
    * Date :  25/8/2022
    * */
    created() {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed() {
        window.addEventListener('scroll', this.handleScroll);
    },
    methods: {
        handleScroll(e) {
            if (window.scrollY > 200) {
                document.getElementById('navbar_top').classList.add('fixed-top');
                // add padding top to show content behind navbar
                let navbar_height = document.querySelector('.navbar').offsetHeight;
                document.body.style.paddingTop = navbar_height + 'px';
            } else {
                document.getElementById('navbar_top').classList.remove('fixed-top');
                // remove padding top from body
                document.body.style.paddingTop = '0';
            }
        }
    }
}
</script>

<style scoped lang="scss">
.fixed-top {
    top: -200px;
    transform: translateY(200px);
    transition: transform .4s ease-in-out;
    box-shadow: 0 15px 10px -15px rgba(0, 0, 0, 0.8);
}
.dropdown-item{
    border-bottom: 1px solid #cbe6d8;
    &:last-child{
        border-bottom: none;
    }
}
</style>
