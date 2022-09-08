<template>
    <div class="g-common-page-layouts">
        <div class="g-common-banner">
            <div class="container-fluid ">

            </div>
        </div>


        <div class="container-fluid">
            <div class="g-common-page-area">
                <div class="g-common-page-main py-2">

                    <!--Sidebar / Navigation link-->
                    <div class="g-common-page-aside" v-if="menuPageData.side_menu">
                        <ul>
                            <li><a class="active" href="">Savings account</a></li>
                            <!-- <li><a href="">IFIC Amar Account</a></li> -->
                            <li v-for="item in menuPageData.side_menu" :key="item.id">
                                <router-link :to="{ name: 'pages', params: { slug: item.slug }}" class="nav-link">
                                    {{ item.name }}
                                </router-link>
                            </li>
                            <!-- <li><a href="">Shohoj Account</a></li>
                            <li><a href="">IFIC Savings Account</a></li>
                            <li><a href="">10TK. Savings Account</a></li> -->
                        </ul>
                    </div>
                    <!--End Sidebar / Navigation link-->

                    <!--                    Right Content-->
                    <div class="g-common-page-right">
                        <div class="g-common-banner-area pb-5">
                            <img class="img-fluid" v-if="menuPageData.page" :src="menuPageData.page.banner_img" alt="">
                        </div>

                        <div class="g-common-page-content overflow-hidden">
                            <h4>{{ menuPageData.page ? menuPageData.page.title : '' }}</h4>

                            <p v-if="menuPageData.page">{{ menuPageData.page.short_summary }}</p>

                            <div v-if="menuPageData.page" v-html="menuPageData.page.en_body">

                            </div>
                        </div>

                        <div v-if="menuPageData.page">
                            <div>
                                <p v-if="menuPageData.page.file.length>0"><strong>Find the link of attachements for more
                                    details</strong></p>
                            </div>

                            <td className="p-2 " v-for="item in menuPageData.page.file" :key="item.id">
                                <a :href="item.file_name" target='_blank'
                                   download>{{ item.file_name.slice((item.file_name).indexOf('_') + 1) }}</a>
                            </td>
                        </div>

                    </div>

                    <!--                    End Right Content-->


                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import {ref, computed, onBeforeUpdate, onMounted, onUnmounted, watch} from "vue";
import {useRoute} from 'vue-router'

import {ApiCallMaker} from "../../api/ApiCallMaker";

const activeName = ref('user');
const tabPosition = ref('left');
const menuPageData = ref({});

const route = useRoute();

onMounted(() => {
    console.log(route.params.slug)
    getMenuPageData(route.params.slug);
});

watch(() => route.params, (current, previous) => {
        getMenuPageData(current.slug);
        // console.log(`${previous.slug} and ${current.slug}`);
    },
    {deep: true})

async function getMenuPageData(slug) {
    const response = await ApiCallMaker('GET', 'get-menu-page-data/' + slug, '', '', '');
    if (response && response.data.status_code == 200) {
        menuPageData.value = response.data.menu_page_data;
        //  console.log('sfsdf',contentData.value);
    }
}


</script>

<style scoped lang="scss">
.g-common-page-layouts {
    background-color: #ffffff;
}

.g-common-banner-area {
    img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        -o-object-fit: cover;
    }
}

.g-common-page-main {
    display: flex;
    justify-content: space-between;
    gap: 3rem;
    align-items: flex-start;
    align-content: flex-start;
    min-height: 80vh;
    @media all and (max-width: 768px) {
        flex-wrap: wrap;
    }
}

.g-common-page-aside {
    width: 320px;
    background-color: #e7f0ec;
    padding: 0.8rem;
    flex-shrink: 0;
    border-radius: 10px;
    box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.4);

    ul {
        list-style: none;
        padding: 0;
        margin: 0;

        li {
            margin-bottom: 1px;
            background-color: darken(#e7f0ec, 40%);

            a {
                color: #000000;
                font-size: 16px;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.4s ease-in-out;
                padding: 0.6rem;
                display: block;

                &.active {
                    color: #ffffff;
                    background-color: #00783f;
                }

                &:hover {
                    color: #ffffff;
                    background-color: #a71d2a;
                }
            }
        }
    }
}

</style>























