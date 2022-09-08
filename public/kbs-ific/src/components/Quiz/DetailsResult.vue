<template>
    <div class="container-fluid">
        <DropMenu/>
        <div class="page-wrapper">
            <div v-if="resultDetails" v-cloak class="min-height-wrapper">
                <h2 class="text-center text-primary font-weight-bold py-3 py-md-5">Details Result</h2>
                <section class="py-50 py-md-60">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 p-3 m-3">
                                <div class="row">
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
                                <div class=" py-3 ">
                                    <ul class="result-items-wrapper list-inline list-unstyled">

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
    </div>
</template>

<script>
    import {ref, computed, onBeforeUpdate, onMounted,defineComponent} from "vue";
    import {ApiCallMaker} from '../../api/ApiCallMaker';
    import { useRoute } from 'vue-router';
    import DropMenu from "../DropMenu.vue";
    export default defineComponent({
        components: {DropMenu},
        setup() {
            const resultDetails =  ref('');
            const userInformation = ref({});
            const userToken = ref('');
            const paramsData = ref({
                user_id:'',
                quiz_id:'',
                attempt:'',
            });

            const route  = useRoute();

            onMounted(() => {
                userInformation.value = JSON.parse(sessionStorage.userInformation);
                userToken.value = sessionStorage.userToken;
                paramsData.value.user_id = userInformation.value.id;
                paramsData.value.quiz_id = route.params.quiz_id;
                paramsData.value.attempt = route.params.attempt;
                getUserQuizDetails();
            });

            async function getUserQuizDetails() {
                const response = await ApiCallMaker('GET', 'result-details', '', userToken.value, paramsData.value);
                if (response && response.data.status_code == 200) {
                    resultDetails.value = response.data.result_details;
                }
            }

            return {resultDetails,userToken}
        },
    })
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
        }
    }
</style>
