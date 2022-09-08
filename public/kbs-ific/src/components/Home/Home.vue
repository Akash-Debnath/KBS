<template>

    <!--**********************************
              Slider and Table Part
     ***********************************-->
    <div class="g-slider-table-area py-2 py-md-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-xl-2 order-1 order-md-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm g-exrate-table">
                            <thead class="bg-primary">
                            <tr align="center">
                                <th colspan="3">
                                    Exchange Rate
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td><small>Currency</small></td>
                                <td><small>Buying</small></td>
                                <td><small>Selling</small></td>
                            </tr>
                            <tr v-for="item in exchangeRateList" :key="item.id">
                                <td><small>{{ item.currency_code }}</small></td>
                                <td><small>{{ item.buying_rate }}</small></td>
                                <td><small>{{ item.selling_rate }}</small></td>
                            </tr>
                            </tbody>


                            <tfoot class="bg-primary">
                            <tr align="center">
                                <th colspan="3">
                                    <a href="#" id="exchange_rate" @click="getAllExchangeRate()">Click for more</a>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-8">
                    <MainSlider :banner-list="bannerList"/>
                </div>
                <div class="col-lg-3 col-xl-2 pt-3 pt-md-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm g-interest-table">
                            <thead class="bg-primary">
                            <tr align="center">
                                <th colspan="2">
                                    Interest Rate
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="item in interestRateList" :key="item.id">
                                <td><small>{{ item.title }}</small></td>
                                <td><small>{{ item.rate }}</small></td>
                            </tr>
                            </tbody>


                            <tfoot class="bg-primary">
                            <tr align="center">
                                <th colspan="3">
                                    <a href="#" id="interest_rate" @click="getAllInterestRate()">Click For More</a>
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    End Slider and Table Part-->


    <!--**********************************
                 Services Thumbnail
     ***********************************-->

    <div class="g-services-thumbnail-area">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 mb-4" v-for="item in articleList" :key="item.id">
                    <div class="g-services-thumb-single">
                        <img v-if="item.thumbnail_img" class="img-fluid" :src="item.thumbnail_img" alt="">
                        <img v-else class="img-fluid" src="" alt="">
                        <div class="g-services-thumb-meta">
                            <p>{{ item.en_title }}</p>
                            <!-- <a href="/deposits">Click For More</a> -->

                        </div>
                        <router-link :to="{ name: 'content', params: { slug: item.slug }}" class="nav-link">
                            Click for more
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More Article-->
        <div class="text-center w-100 mt-3" id="show_article_list">
            <div class="show-article-list">
                <button id="article_list" role="button" class="btn btn-outline-success text-center"
                        @click="getAllArticleList()">Show More
                </button>
            </div>
        </div>
        <!-- End Load More Article-->

    </div>
    <!--   End Services Thumbnail-->


</template>

<script>

import MainSlider from "../MainSlider.vue";
import {ApiCallMaker} from "../../api/ApiCallMaker";
import {defineComponent} from "vue";
//import {ref, computed} from 'vue';
import {ref, computed, onBeforeUpdate, onMounted} from "vue";
import $ from 'jquery'

export default defineComponent({
    name: "Home",
    components: {
        MainSlider
    },
    setup() {
        const bannerList = ref([]);
        const articleList = ref([]);
        const allArticleList = ref([]);
        const allInterestRateList = ref([]);
        const interestRateList = ref([]);
        const allExchangeRateList = ref([]);
        const exchangeRateList = ref([]);
        const showAllListStatus = ref({
            interest: false,
            exchange: false,
        });

        onMounted(() => {
            getBannerList();
            getArticleList();
            getInterestRateList();
            getExchangeRateList();
        });

        async function getBannerList() {
            const response = await ApiCallMaker('GET', 'home-banners', '', '', '');
            //  console.log('response',response);
            if (response && response.data.status_code == 200) {
                bannerList.value = response.data.banner_list;
            }
        }

        async function getArticleList() {
            const response = await ApiCallMaker('GET', 'article-list', '', '', '');
            if (response && response.data.status_code == 200) {
                allArticleList.value = response.data.article_list;
                articleList.value = allArticleList.value.slice(0, 12);
                if (allArticleList.value.length <= 12) {
                    $("#show_article_list").html("");
                }
            }
        }

        async function getAllArticleList() {

            showAllListStatus.value.article = !showAllListStatus.value.article;

            if (showAllListStatus.value.article === true) {
                articleList.value = allArticleList.value;
                $('#article_list').html('Show Less')
            } else {
                articleList.value = allArticleList.value.slice(0, 12);
                $('#article_list').html('Show More')
            }

            // articleList.value = allArticleList.value;
            //   $('#article_list').html("");
        }

        async function getInterestRateList() {
            const response = await ApiCallMaker('GET', 'all-interest-rate', '', '', '');
            if (response && response.data.status_code === 200) {
                allInterestRateList.value = response.data.interest_rate_list;
                interestRateList.value = allInterestRateList.value.slice(0, 6);
            }
        }

        async function getExchangeRateList() {
            const response = await ApiCallMaker('GET', 'exchange-rate-list', '', '', '');
            if (response && response.data.status_code === 200) {
                allExchangeRateList.value = response.data.exchange_rate_list;
                exchangeRateList.value = allExchangeRateList.value.slice(0, 5);
            }
        }

        function getAllInterestRate() {
            showAllListStatus.value.interest = !showAllListStatus.value.interest;

            if (showAllListStatus.value.interest === true) {
                interestRateList.value = allInterestRateList.value;
                $('#interest_rate').html('Show less')
            } else {
                interestRateList.value = allInterestRateList.value.slice(0, 6);
                $('#interest_rate').html('Click for more')
            }
        }

        function getAllExchangeRate() {
            showAllListStatus.value.exchange = !showAllListStatus.value.exchange;
            console.log(showAllListStatus.value);
            if (showAllListStatus.value.exchange === true) {
                exchangeRateList.value = allExchangeRateList.value;
                $('#exchange_rate').html('Show less')
            } else {
                exchangeRateList.value = allExchangeRateList.value.slice(0, 5);
                $('#exchange_rate').html('Click for more')
            }

        }


        return {
            bannerList,
            articleList,
            interestRateList,
            exchangeRateList,
            getAllExchangeRate,
            getAllInterestRate,
            getAllArticleList
        }
    },


});

</script>

<style scoped lang="scss">
.g-slider-table-area {
    background-color: #ffffff;
}


.table-bordered.g-exrate-table {
    tr > th {
        color: #ffffff;
    }

    tbody > tr:first-child > td {
        small {
            font-size: 13px;
            font-weight: bold;
        }

    }

    tbody > tr > td:first-child {
        background-color: #EAFAF2;
    }

    tfoot > tr > th > a {
        color: #ffffff;
    }
}

.g-interest-table {
    tr > th {
        color: #ffffff;
    }

    tbody > tr > td:first-child {
        background-color: #EAFAF2;
    }

    tfoot > tr > th > a {
        color: #ffffff;
    }
}


/*===============================
       Services Thumbnail Area
  ===============================*/
.g-services-thumbnail-area {
    margin-top: 5px;
    background-color: #ffffff;
    padding: 25px 0;
}

.g-services-thumb-single {
    border-radius: 10px;
    border: 1px solid #00793F;
    position: relative;
    height: 278px;
    width: 100%;
    overflow: hidden;
    transition: all 0.4s ease-in-out;
    cursor: pointer;
    @media all and (max-width: 768px) {
        height: 220px;
    }

    img {
        object-fit: cover;
        height: 100%;
        width: auto;
        @media all and (max-width: 768px) {
            height: 100%;
            width: auto;
            object-fit: cover;
            -o-object-fit: cover;
        }
    }

    &:hover {
        > .g-services-thumb-meta {
            //position: absolute;
            //left: 0;
            //right: 0;
            //top: 0;
            //bottom: 0;
            //background-color: rgba(0, 121, 63, 0.95);
            //text-align: center;
            //display: flex;
            //justify-content: center;
            //align-items: center;
            //height: 278px;
            //margin-bottom: 0;
            //border-top-left-radius: 10px;
            //border-top-right-radius: 10px;
            //color: #ffffff;
            //font-weight: 600;
            //font-size: 1rem;

            p {
                font-weight: 600;
                margin-bottom: 0;
                padding: 0.5rem;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
            }

            @media all and (max-width: 768px) {
                height: 220px;
            }


        }

        a {
            display: flex;
            visibility: visible;
            opacity: 1;
            color: lighten(#00793F, 10%);
        }

    }

    .g-services-thumb-meta {
        transition: all 0.3s linear;
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        height: 60px;
        background-color: rgba(255, 255, 255, 0.95);
        text-align: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        margin-bottom: 0;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        color: #00793F;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.04);

        p {
            font-weight: 600;
            margin-bottom: 0;
            padding: 0.5rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        & + a {
            font-size: 0.9rem;
            font-weight: 500;
            position: absolute;
            right: 5px;
            bottom: 5px;
            display: block;
        }
    }

}

.show-article-list {
    position: relative;

    button {
        position: relative;
        background-color: #ffffff;
        padding: 0.5rem 2rem;
        z-index: 3;

        &:hover {
            background-color: #00793F;
            color: #ffffff;
        }
    }

    &::after {
        content: ' ';
        background-color: #00793F;
        width: 50%;
        height: 1px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1;
        @media all and (max-width: 768px) {
            width: 90%;
        }
    }
}


</style>
