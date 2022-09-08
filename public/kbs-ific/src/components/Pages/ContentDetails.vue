<template>
    <div class="g-common-page-layouts">
        <div class="g-common-banner">
            <div class="container-fluid ">
                <div class="g-common-banner-area py-5">
                    <img class="img-fluid" v-if="contentData.banner_img" :src="contentData.banner_img" alt="">
                </div>
            </div>
        </div>


        <div class="container-fluid mt-5">
            <div class="g-blogs-area">
                <div class="g-blogs-main">
                    <h4>{{ contentData.en_title }}</h4>
                    <div v-for="content in contentData.contents" :key="content.id">
                        <div v-html="content.en_body"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mr-auto">
                            <div v-if="contentData.media">
                                <p><strong>Find the link of attachments for more details</strong></p>
                                <ul class="list-group list-group-flush">

                                    <li className="p-2 list-group-item" v-for="item in contentData.media " :key="item.id">
                                        <a class="text-decoration-none" download :href="item.url"
                                           target='_blank'>{{ item.url.slice((item.url).indexOf('_') + 1) }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</template>

<script setup>
import {ref, computed, onBeforeUpdate, onMounted, watch} from "vue";
import {useRoute} from 'vue-router'

import {ApiCallMaker} from "../../api/ApiCallMaker";

const activeName = ref('user');
const tabPosition = ref('left');
const contentData = ref({});
const route = useRoute();


onMounted(() => {
    console.log(route.params.slug)
    getContentDetails(route.params.slug);
});

watch(() => route.params, (current, previous) => {
        getContentDetails(current.slug);
    },
    {deep: true})

async function getContentDetails(slug) {
    const response = await ApiCallMaker('GET', 'article-details/' + slug, '', '', '');
    if (response && response.data.status_code == 200) {
        contentData.value = response.data.article_info;
        //  console.log('sfsdf',contentData.value);
    }
}


</script>

<style scoped lang="scss">
.g-common-page-layouts {
    background-color: #ffffff;
    overflow: hidden;
}

.g-common-banner-area {
    img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        -o-object-fit: cover;
    }
}

.g-left-sidebar {
    background-color: #E9F2EE;
    padding: 1.5rem;

    h5 {
        color: rgba(0, 0, 0, 0.6);
        font-weight: 600;
    }

    ul {
        margin: 0;
        display: block;

        li {
            font-size: 1rem;
            line-height: 2em;
            display: list-item;
            margin: 1rem 0;
        }
    }
}

.g-blogs-area {
    padding: 3rem;
    background-color: #f4f4f4;
    border-radius: 0.5rem;
    margin-bottom: 2rem;
    overflow: hidden;
    @media (max-width: 768px) {
        padding: 1rem;
    }
}

.g-blogs-main {
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
    overflow: hidden;

    h1, h2, h3, h4, h5, h6 {
        color: #00783f;
        font-weight: 600;
        margin-bottom: 1rem;
        text-transform: capitalize;
        position: relative;
        padding-left: 25px;

        &::before {
            content: "";
            width: 12px;
            height: 12px;
            background-color: #00783f;;
            display: inline-block;
            position: absolute;
            bottom: 50%;
            transform: translateY(50%);
            vertical-align: middle;
            left: 0;
        }
    }

    @media (max-width: 768px) {
        padding: 1rem;
    }
}

.g-list-item-area {
    margin: 2rem auto;

    ul.g-list-item {
        margin: 0;
        display: block;

        li {
            font-size: 1rem;
            line-height: 1.8em;
            display: list-item;
        }
    }
}

</style>

























