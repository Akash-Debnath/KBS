<template>
    <div class="page-wrapper">
        <div v-if="isLoading">
            <Loader></Loader>
        </div>

        <div v-else  v-cloak class="min-height-wrapper">
            <h2 class="text-center font-weight-bold pb-5 pb-md-10">Details Result</h2>
            <section class="py-50 py-md-60">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12 p-3 m-3">
                            <!-- {{userQuizList}} -->
                            <div class="d-flex p-3">
                                <div class="col-md-6">
                                    <div>
                                        <strong>Quiz Title: </strong>{{resultDetails[0].quiz.name}}
                                    </div>
                                    <div>
                                        <strong>Performed Date: </strong>{{resultDetails[0].created_at}}
                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <strong>Total marks </strong>: {{resultDetails[0].quiz.total_marks}}
                                    <div>
                                        <strong>Obtained marks: </strong>{{(resultDetails[0].result)}}
                                    </div>
                                </div>
                            </div>
                            <div class=" p-3 ">
                                <ul class="result-items-wrapper list-inline list-unstyled">
                                    <!-- {{resultDetails}} -->
                                    <li v-for="(item, index) in resultDetails" :key="item.id">
                                        <div class="q-box">
                                            <label class=""><b><span>{{index + 1}}.</span> {{item.question.f_label}}</b> </label>
                                            <div v-if="item.question.f_option_value">
                                                <ol class="ml-30" type="A">
                                                    <li v-for="option in item.question.f_option_value.split(',')" :key="option">
                                                        <label v-if="option==item.answer">
                                                            <span v-if="item.answer == item.question.f_default_value" class="text-success">{{option}}</span>
                                                            <span v-else class="text-danger"> {{option}}</span>
                                                        </label>
                                                        <label v-else>  {{option}}</label>
                                                    </li>
                                                </ol>
                                            </div>
                                            <div v-else>
                                                <label >
                                                    Your Answer:
                                                    <span v-if="item.answer == item.question.f_default_value" class="text-success"> {{item.answer}}</span>
                                                    <span v-else class="text-danger"> {{item.answer}}</span>
                                                </label>
                                            </div>
                                            <p class="mb-0"> <b>Correct Answer: </b>{{item.question.f_default_value}} </p>
                                        </div>
                                    </li>
                                </ul>
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
    import Loader from "../../components/Loader";
    export default {
        
        name: "DetailsResult",
        data(){
            return{
                isLoading: true,
                isAuthinticate:false,
                resultDetails : '',
                userInformation : '',
                quizID:'',
                attempt:'',
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
                pageUrl = pageUrl == undefined ? 'result-details' : pageUrl;
                axios.get(pageUrl,
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+sessionStorage.userToken
                        },
                        params : {
                            user_id: _that.userInformation.id,
                            quiz_id:_that.quizID,
                            attempt:_that.attempt,
                        }
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            console.log(response.data);
                             _that.resultDetails   = response.data.result_details;
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
             this.quizID = this.$route.params.quiz_id;
             this.attempt = this.$route.params.attempt;
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
    .mhv-100 {
        min-height: 50vh;
    }

    .result-items-wrapper {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -15px;
    }

    .result-items-wrapper > li {
        width: 50%;
        max-width: 0 0 50%;
        padding: 10px 15px;
    }

    .result-items-wrapper > li .q-box {
        background: #f1f1f1;
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 30px;
        height: 100%;
        box-shadow: 0 5px 10px rgba(0,0,0,0.15);
    }

    @media screen and (max-width: 767px) {
        .result-items-wrapper > li {
            width: 100%;
            max-width: 0 0 100%;
        }
    }
</style>