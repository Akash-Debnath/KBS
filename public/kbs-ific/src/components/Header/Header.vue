<template>
    <!--**********************************
                  Top Header
     ***********************************-->
    <div class="g-header-area">

        <div class="container-fluid">
            <div class="g-header-main"
                 v-bind:style="[isQuiz ? {'justifyContent':'inherit', 'minHeight':''}: {'justifyContent':'center','minHeight':'90px'}]">
                <div class="g-main-logo">
                    <router-link :to="{ name: 'home'}">
                        <img src="@/assets/images/logo.svg" alt="ific" class="img-fluid">
                    </router-link>
                </div>

                <div class="g-top-search" v-bind:style="[isQuiz ? {'display':'initial'}: {'display':'none'}]">
                    <label for="g-top-search">

                        <input type="search" v-model="searchQuery" id="g-top-search" placeholder="Search"
                               @keyup="searchData()">
                        <img src="@/assets/images/search.svg" alt="ific">
                    </label>
                    <div v-if="searchList.length>0 && searchQuery.length>=3" class="card g-top-search-result">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="item in searchList" :key="item.id">
                                <a href="#" @click="showSearchContent(item.slug)">{{ item.en_title }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="g-top-cta">
                    <a href="#">
                        <img class="img-fluid" src="@/assets/images/OneClick.svg" alt="ific">
                    </a>
                </div>


                <div class="g-admin-login-btn" v-bind:style="[isQuiz ? {'display':'initial'}:{'display':'none'}]">
                    <a role="button" href="http://192.168.11.221/kbs/admin" class="btn btn-outline-primary">
                        Admin Login
                    </a>
                </div>

                <div class="g-quiz-portal-login" v-bind:style="[isQuiz ? {'display':'initial'}:{'display':'none'}]">
                    <router-link role="button" :to="{ name: 'login'}"
                                 class="btn btn-outline-primary">Quiz Portal
                    </router-link>
                </div>
            </div>
        </div>

    </div>
    <!--End Top Header-->


    <!-- Modal -->
    <div class="modal fade" id="g-admin-login" data-backdrop="static" data-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="g-form-signin">
                        <div class="text-center">

                            <img class="mb-4" src="@/assets/images/logo.svg" alt="" width="72" height="72">
                            <h1 class="h3 mb-3 font-weight-normal">Sign In</h1>
                        </div>
                        <label for="inputEmail" class="">Email address</label>
                        <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required=""
                               autofocus="">
                        <label for="inputPassword" class="">Password</label>
                        <input type="password" id="inputPassword" class="form-control" placeholder="Password"
                               required="">
                        <div class="checkbox mb-3">
                            <label>
                                <input type="checkbox" value="remember-me"> Remember me
                            </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</template>

<script>
import {ApiCallMaker} from '../../api/ApiCallMaker';

export default {
    name: "Header",
    props: {
        menus: {type: Array},
    },
    data() {
        return {
            searchList: [],
            searchQuery: '',
            searchInterval: '',
        }
    },

    computed: {
        isQuiz() {
            const routeArray = ['quiz', 'QuizTemplate', 'QuizResults', 'DetailsResult'];
            return !routeArray.includes(this.$route.name);
        }
    },
    methods: {

        searchData() {
            console.log(this.searchInterval);
            clearTimeout(this.searchInterval);
            console.log('after clear', this.searchInterval);
            if (this.searchQuery.length >= 3) {
                this.searchInterval = setTimeout(() => {
                    this.getSearchData();
                }, 500)
            }
        },

        async getSearchData() {
            const response = await ApiCallMaker('GET', 'article/search/' + this.searchQuery, '', '', '');
            if (response && response.data.status_code === 200) {
                this.searchList = response.data.article_list.data;
            }
        },

        showSearchContent(slug) {
            this.$router.push({name: 'content', params: {slug: slug}});
            this.searchQuery = '';
        }
    }
}
</script>

<style scoped lang="scss">
.g-header-area {
    background-color: #ffffff;
}

.g-header-main {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    min-height: 80px;
    gap: 2rem;
    @media all and (max-width: 1400px) {
        gap: 1rem;
    }
}

.g-main-logo {
    flex-basis: 25%;
    @media all and (max-width: 768px) {
        width: 15%;
    }
}

.g-top-cta {
    flex-basis: auto;
    @media all and (max-width: 768px) {
        flex-basis: 25%;
    }
}

.g-admin-login-btn {
    flex-basis: auto;
    margin-left: auto;
    text-align: right;
    @media all and (max-width: 768px) {
        width: 25%;
        flex-grow: 1;
    }
}

.g-top-search {
    flex-basis: 25%;
    flex-grow: 1;
    position: relative;
    @media all and (max-width: 768px) {
        width: 15%;
        order: 3;
    }

    label {
        display: block;
        width: 100%;
        position: relative;
        margin-bottom: 0;

        input {
            background-color: #E9F2EE;
            border: none;
            padding: 0.7rem 0.7rem 0.7rem 3rem;
            border-radius: 1rem;
            display: block;
            width: 100%;

            &:focus-visible {
                outline: none;
            }
        }

        img {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    }
}

.g-top-search-result {
    position: absolute;
    margin-top: 5px;
    width: 100%;
    z-index: 3000;
}

</style>
