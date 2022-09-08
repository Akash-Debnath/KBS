<template>
    <div v-if="userInformation && userToken" class="text-right mt-2 dropdown dropleft">

        <button class="btn btn-primary dropdown-toggle px-4" type="button" id="dropdownMenuButton"
                data-toggle="dropdown"
                aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                 class="bi bi-layout-text-sidebar" viewBox="0 0 16 16">
                <path
                    d="M3.5 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 3a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM3 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
                <path
                    d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm12-1v14h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zm-1 0H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h9V1z"/>
            </svg>
        </button>

        <div class="dropdown-menu pt-0" aria-labelledby="dropdownMenuButton">
            <a href="#" class="dropdown-item text-decoration-none bg-white">
                <img class="g-user-images img-fluid mr-2" src="https://picsum.photos/200" alt="">
                <span class="g-user-name">{{userInformation.first_name}} {{userInformation.last_name}}</span>
            </a>
            <div class="dropdown-divider"></div>
            <router-link class="dropdown-item" :to="{name:'quiz'}" >Quiz Board</router-link>
            <router-link class="dropdown-item" :to="{name:'QuizResults'}" >Quiz results</router-link>
            <!-- <a class="dropdown-item" href="#">Settings</a> -->
            <a class="dropdown-item" href="#" @click="logout()">Logout</a>
        </div>
    </div>
</template>

<script>
    import { defineComponent,ref,onMounted } from 'vue';
    import {ApiCallMaker} from '../api/ApiCallMaker';
    import { useRoute } from 'vue-router';
    export default defineComponent({
        setup() {
            const userInformation = ref({});
            const userToken = ref('');
            
            onMounted(()=>{
                if(sessionStorage.userInformation) {
                    userInformation.value = JSON.parse(sessionStorage.userInformation);
                }
                if(sessionStorage.userToken) {
                    userToken.value = sessionStorage.userToken;
                }
            });

            async function logout() {
                let formData = new FormData();
                formData.append('id',  userInformation.value.id);
                formData.append('tokenId', userToken.value);

                const response = await ApiCallMaker('POST', 'logout', formData,userToken.value, '');
                if (response.data.status_code === 200){
                    sessionStorage.removeItem('userInformation');
                    sessionStorage.removeItem('userToken');
                    // location.reload();
                    this.$router.push({name : 'home'})
                }
            }
            return {userInformation, userToken, logout}
        },
    })
</script>


<style scoped lang="scss">
.g-user-images {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    -o-object-fit: cover;
}
.g-user-name{
    color: #484848;
}
</style>
