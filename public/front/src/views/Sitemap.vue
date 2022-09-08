<template>
    <div class="page-wrapper">
        <section class="inner-search-area py-20">
            <div class="container">
                <div class="search-input-wrapper d-block d-sm-flex justify-content-between align-items-center">
                    <div class="breadcrumbs mt-10 mt-sm-0">
                        <ul class="list-inline list-unstyled mb-0">
                            <li class="list-inline-item">
                                <router-link class="nav-item" :to="{ name: 'Home'}">
                                    <i class="fa fa-home"></i>
                                </router-link>
                            </li>
                            <li class="list-inline-item">
                                sitemap
                            </li>
                        </ul>
                    </div>
                    <!--                        <button @click="$router.go(-1)" class="btn d-block d-sm-inline-block mt-10 mb-sm-0 btn-primary btn-common-2 position-relative font-18 overflow-hidden ripple-btn text-left py-3 px-30 text-white order-sm-1"><i class="fa fa-angle-double-left"></i> Back</button>-->
                </div>
            </div>
        </section>
        <section class="topics-area py-50 py-md-60">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-lg-8">
                        <h1 class="section-title bottom-bar text-center mb-10 pb-20">URL List</h1>
                    </div>
                </div>

                <ul class="mb-30 mt-30">
                    <li class="p-1" v-for="(a_page,index) in url_list" :key="index">
                        <a :href="a_page" target="_blank" onclick="return false;">{{a_page}}</a>
                    </li>
                </ul>
            </div>
        </section>

    </div>
</template>
<script>
    export default {
        name: "Sitemap",

        components:{
            // Loader
        },

        data(){
            return{
                sitemap_list : '',
                url_list    : '',
                isLoading : false
            }
        },

        methods:{

            getRoutesList(routes, pre) {
                return routes.reduce((array, route) => {
                    const path = `${pre}${route.path}`;

                    if (route.path !== '*') {
                        array.push(path);
                    }

                    if (route.children) {
                        array.push(this.getRoutesList(route.children, `${path}/`));
                    }

                    return array;
                }, []);
            }
        },

        created() {
            this.url_list= this.getRoutesList(this.$router.options.routes, 'https://kbs.com');
        }
    }
</script>

<style scoped>

</style>