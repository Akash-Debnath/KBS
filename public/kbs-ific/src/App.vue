
<template>
    <Header v-if="!route.meta.commonPart" :menus="menus"/>
    <Navigation v-if="!route.meta.navigationPart" :menus="menus"/>
    <RouterView/>
    <Footer v-if="!route.meta.commonPart" :links="usefulLinks"/>
</template>

<script setup>
    import {useRoute, RouterLink, RouterView} from "vue-router";
    import Header from "./components/Header/Header.vue";
    import Footer from "./components/Footer/Footer.vue";
    import {ApiCallMaker} from "./api/ApiCallMaker";
    import { onMounted } from "@vue/runtime-core";
    import { ref, computed, onBeforeUpdate } from "vue";
    import Navigation from "./components/Navigation/Navigation.vue";
    let route = useRoute();


    const usefulLinks = ref([]);
    const menus = ref([]);

    onMounted(()=>{
         getUsefullLinks();
         getMenus();
    });

    async function getUsefullLinks() {
        const response =  await ApiCallMaker('GET','usefull-links','','','');
        if(response && response.data.status_code===200){
             usefulLinks.value = response.data.link_list;
            //  console.log('sfsdf',usefulLinks);
        }
    }

     async function getMenus() {
        const response =  await ApiCallMaker('GET','all-menu-navbars','','','');
        if(response && response.data.status_code===200){
             menus.value = response.data.menu_list;
        }
    }
</script>

<style>
@import 'assets/base.css';

#app {
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    overflow: hidden;
}

</style>
