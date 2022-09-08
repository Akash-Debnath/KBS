<template>
    <swiper
        :modules="modules"
        :auto-height="true"
        :effect="'fade'"
        :autoplay="{
           delay: 5000,
           disableOnInteraction: false,
        }"
        :slides-per-view="1"
        :space-between="50"
        :loop="true"
        :navigation="true"
        :pagination="{ clickable: true }"
        @swiper="onSwiper"
        @slideChange="onSlideChange"
    >
        <swiper-slide v-for="item in bannerList" :key="item">
            <img :src="item.media[0].url" :title="item.title" :alt="item.title">
        </swiper-slide>
    </swiper>
</template>
<script>
// import Swiper core and required modules
import {Navigation, Pagination, Scrollbar, A11y, Autoplay, EffectFade} from 'swiper';

// Import Swiper Vue.js components
import {Swiper, SwiperSlide} from 'swiper/vue';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import "swiper/css/effect-fade";

export default {
    components: {
        Swiper,
        SwiperSlide,
    },

    props: {
        bannerList: {type: Array},
    },
    setup() {
        const onSwiper = (swiper) => {
            // console.log(swiper);
        };
        const onSlideChange = () => {
            // console.log('slide change');
        };
        return {
            onSwiper,
            onSlideChange,
            modules: [Navigation, Pagination, Scrollbar, A11y, Autoplay, EffectFade],
        };
    },
};
</script>
<style lang="scss">
.swiper-button-next, .swiper-button-prev {
    position: absolute;
    top: 50%;
    width: 40px;
    height: 40px;
    /*margin-top: calc(0px - (var(--swiper-navigation-size)/ 2));*/
    z-index: 10;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #424242;
    background-color: rgba(255, 255, 255, 0.6);
    border-radius: 50%;
}

.swiper-button-next:after, .swiper-button-prev:after {
    font-family: 'swiper-icons', sans-serif;
    font-size: 24px;
    letter-spacing: 0;
    text-transform: none;
    font-variant: initial;
    line-height: 1;
    font-weight: 800;
}

.swiper-slide {
    img {
        height: 280px;
        width: 100%;
        object-fit: cover;
        object-position: center;
    }
}
</style>
