<template>
    <div class="page-wrapper">
        <div v-if="isLoading">
            <Loader></Loader>
        </div>
        <div v-else v-cloak class="min-height-wrapper">
            <section class="py-50 py-md-60">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 p-3 m-3">
                            <h2 class="text-center font-weight-bold pb-20 pb-md-40">Quiz List</h2>
                            <!--                            v-if="userInformation.roles ? a_quiz.role_id.includes(userInformation.roles[0].id) : a_quiz.role_id.includes(0)"-->
                            <div  v-for="a_quiz in allQuizzes" :key="a_quiz.id">
                                <div class="quiz-item mb-20">

                
                                    <div class="q-featured-caption">
                                        <div class="q-icon">
                                            <i class="fa fa-desktop" aria-hidden="true"></i>
                                        </div>
                                        <div class="q-feature">
                                            <h4 class="my-0 pb-1 q-title">
                                                Quiz Name
                                            </h4>
                                            <p class="mb-0">{{ a_quiz.name }}</p>
                                        </div>
                                    </div>
                                    <div class="q-featured-caption">
                                        <div class="q-icon">
                                            <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        </div>
                                        <div class="q-feature">
                                            <h4 class="my-0 pb-1 q-title">
                                                Duration
                                            </h4>
                                            <p class="mb-0">{{a_quiz.duration}} Min</p>
                                        </div>
                                    </div>
                                    <div class="q-featured-caption">
                                        <div class="q-icon">
                                            <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                                        </div>
                                        <div class="q-feature">
                                            <h4 class="my-0 pb-1 q-title">
                                                Number of Questions
                                            </h4>
                                            <p class="mb-0">{{ a_quiz.number_of_questions }}</p>
                                        </div>
                                    </div>
                                    <div class="q-featured-caption">
                                        <div class="q-icon">
                                            <i class="fa fa-bolt" aria-hidden="true"></i>
                                        </div>
                                        <div class="q-feature">
                                            <h4 class="my-0 pb-1 q-title">
                                                Total Marks
                                            </h4>
                                            <p class="mb-0">{{ a_quiz.total_marks }}</p>
                                        </div>
                                    </div>
                                    <div class="q-featured-caption">
                                        <div class="q-icon">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </div>
                                        <div class="q-feature">
                                            <h4 class="my-0 pb-1 q-title">
                                              Start/End Date
                                            </h4>
                                            <p class="mb-0">{{ a_quiz.start_date }}/{{a_quiz.end_date}}</p>
                                        </div>
                                    </div>
                                    <div class="q-featured-caption"  v-if="new Date().toISOString().slice(0,10)>=new Date(a_quiz.start_date).toISOString().slice(0,10) && new Date(a_quiz.end_date).toISOString().slice(0,10)>=new Date().toISOString().slice(0,10)">
                                        <button class="btn btn-common btn-primary px-25 text-white font-16" @click="quizStart(a_quiz)"><span>Start</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Loader from "../components/Loader";
    export default {
        name: "Quiz",
        data(){
            return{
                isLoading: true,
                itemA: '0' ,
                allQuizzes : '',
                userInformation : ''
            }
        },
        components:{
            Loader,
        },
        methods:{
            getQuizList()
            {
                let _that =this;

                if (this.userInformation.roles){
                    console.log('authorised')
                    axios.get('quiz-list',{

                        params : {
                            isAuthorised : 1,
                            isRoleID : this.userInformation.roles[0].id
                        }

                    }).then(function (response) {
                            _that.isLoading= false;
                            if(response.data.status_code === 200){
                                _that.allQuizzes = response.data.quiz_form_list;
                            }
                        })
                } else{
                    // console.log('unauthorised')
                    axios.get('quiz-list', { cache: false })
                        .then(function (response) {
                            _that.isLoading= false;
                            if(response.data.status_code === 200){
                                _that.allQuizzes = response.data.quiz_form_list;
                            }
                        })
                }
            },

            quizStart(quizInfo){
                this.$router.push({name: 'StartExam', params: { quiz_info: JSON.stringify(quizInfo) }});
            }
        },
        created(){
            if (sessionStorage.userInformation){
                this.userInformation = JSON.parse(sessionStorage.userInformation);
                console.log(this.userInformation);
                if (!this.userInformation){
                    this.isAuthinticate = false;
                } else{
                    this.isAuthinticate = true;
                }
            }else{
                this.userInformation = '';
            }
            this.getQuizList();
        }
    }
</script>

<style scoped>

</style>
