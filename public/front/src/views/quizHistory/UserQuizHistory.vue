<template>
    <div class="page-wrapper">
        <div v-if="isLoading">
            <Loader></Loader>
        </div>

        <div v-else  v-cloak class="min-height-wrapper" >
            <h2 class="text-center font-weight-bold pb-5 pb-md-10">Quiz Perform List</h2>
            <section class="py-50 py-md-60">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 p-3 m-3">
                            <!-- {{userQuizList}} -->

                            <table class="table border">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Quiz Title</th>
                                    <th scope="col">Number of Attempt</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item,i) in userQuizList" :key="item.id">
                                    <th scope="row">{{i+1}}</th>
                                    <td>{{item.quiz.name}}</td>
                                    <td>{{item.attempt}}</td>
                                    <td><router-link :to="{ name: 'UserQuizResultList', params: { quiz_id:item.quiz_id}}" class="btn btn-info btn-xs m-1" tag="button" ><i class="fa fa-eye text-white"></i> Show All Results</router-link></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Loader from "../../components/Loader";
    export default {
        
        name: "UserQuizHistory",
        data(){
            return{
                isLoading: true,
                isAuthinticate:false,
                userQuizList : '',
                userInformation : '',
                pagination  :{
                    current         :1,
                    per_page        : 20,
                    total           : ''
                },
            }
        },
        components:{
            Loader,
        },

         methods:{
            getUserQuizList(pageUrl)
            {
                let _that = this;
                pageUrl = pageUrl == undefined ? 'user-quiz-list' : pageUrl;
                axios.get(pageUrl,
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+sessionStorage.userToken
                        },
                        params : {
                            userId: _that.userInformation.id,
                            page: _that.pagination.current
                        }
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response.data);
                            _that.pagination.current = response.data.user_quiz_list.current_page;
                            _that.pagination.total   = response.data.user_quiz_list.last_page;
                            _that.userQuizList   = response.data.user_quiz_list.data;
                            console.log(_that.userQuizList);
                            _that.isLoading          = false;
                            _that.setTimeoutElements();

                        }
                        else{
                            _that.success_message   = "";
                            _that.error_message     = response.data.error;
                        }
                    })             
            },
             setTimeoutElements()
            {
                // setTimeout(() => this.isLoading = false, 3e3);
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
            },
        },

        created(){
            if (sessionStorage.userInformation){
                this.userInformation = JSON.parse(sessionStorage.userInformation);
                // console.log(this.userInformation.roles[0].id);
                if (!this.userInformation){
                    this.isAuthinticate = false;
                } else{
                    this.isAuthinticate = true;
                }
            }else{
                this.userInformation = '';
            }
            this.getUserQuizList();
        }


        
    }
</script>

<style scoped>

</style>