<template>
    <div class="container-fluid">
        <DropMenu/>
        <div class="page-wrapper">
            <div v-if="userToken" class="min-height-wrapper">

                <h2 class="text-center text-primary font-weight-bold py-4">Quiz Result List</h2>
                <!--**********************************
                            Date Picker
                ***********************************-->

                <div class="g-date-picker d-flex justify-content-center align-items-center">
                    <div class="date-error"><small class="text-danger block" id="dateError">
                    </small></div>
                    <div class="block">
                        <span class="demonstration mr-2">From</span>
                        <el-date-picker
                            v-model="data.start_date"
                            type="date"
                            placeholder="Pick a date"
                            :size="size"
                        />
                    </div>
                    <div class="block">
                        <span class="demonstration mr-2">To</span>
                        <el-date-picker
                            v-model="data.end_date"
                            type="date"
                            placeholder="Pick a date"
                            :size="size"
                        />
                    </div>
                    <button class="btn btn-success" @click="checkValidationAndSearch()">Search</button>

                </div>
                <!-- End Date Picker-->

                <section class="py-50 py-md-60">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 p-3 m-3 table-responsive">
                                <!-- {{userQuizList}} -->

                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">SL</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Full Name</th>
                                        <th scope="col">Quiz Title</th>
                                        <th scope="col">Attempt Number</th>
                                        <th scope="col">Marks</th>
                                        <th scope="col">View Details</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item,i) in quizResultList" :key="item.id">
                                        <td scope="row">{{ i + 1 }}</td>
                                        <!-- <td>{{item.quiz.name}}</td> -->
                                        <td>{{ item.created_at }}</td>
                                        <td>{{ item.user.first_name }} {{ item.user.last_name }}</td>
                                        <td>{{ item.quiz.name }}</td>
                                        <td>{{ item.attempt }}</td>
                                        <td>{{ item.score }}/{{ parseInt(item.quiz.total_marks) }}</td>
                                        <td>
                                            <router-link
                                                :to="{ name: 'DetailsResult', params: { quiz_id:item.quiz_id, attempt:item.attempt}}"
                                                class="btn btn-info btn-xs m-1" tag="button"><i
                                                class="fa fa-eye text-white"></i>View Details
                                            </router-link>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script>

import {ref, computed, onBeforeUpdate, onMounted, defineComponent} from "vue";
import {ApiCallMaker} from '../../api/ApiCallMaker';
import $ from 'jquery';
import DropMenu from "../DropMenu.vue";
export default defineComponent({
      components: {DropMenu},
    setup() {

        const size = ref('large')
        const startDate = ref('');
        const endDate = ref('');

        const quizResultList = ref([]);
        const userInformation = ref({});
        const userToken = ref('');
        const data = ref({
            user_id: '',
            start_date: '',
            end_date: ''
        });
        const pagination = ref({
            current: 1,
            per_page: 20,
            total: ''
        });

        // return{
        //     size,
        //     startDate,
        //     endDate
        // }

        onMounted(() => {
            userInformation.value = JSON.parse(sessionStorage.userInformation);
            data.value.user_id = userInformation.value.id;
            userToken.value = sessionStorage.userToken;
            getUserQuizList(data.value);

        });

        async function getUserQuizList(data) {

            const response = await ApiCallMaker('GET', 'user-quiz-results', '', userToken.value, data);
            if (response && response.data.status_code == 200) {
                quizResultList.value = response.data.user_result_list;
                console.log('quiz results', quizResultList.value);
            }
        }

        function checkValidationAndSearch() {
            if (!data.value.start_date || !data.value.end_date) {
                $('#dateError').html("Please provide a valid date range");
                setTimeout(() => {
                    $('#dateError').html('')
                }, 2e3);
            } else {
                getUserQuizList(data.value);
            }
        }

        return {userToken, quizResultList, size, data, checkValidationAndSearch}
    },
})
</script>


<style scoped lang="scss">
.g-date-picker {
    position: relative;
    gap: 2rem;
    flex-wrap: wrap;
    @media all and (max-width: 768px) {
        flex-direction: column;
    }

    span.demonstration {
        @media all and (max-width: 768px) {
            width: 40px;
            display: inline-block;
        }
    }
}

.g-date-picker {
    .date-error {
        position: absolute;
        top: -30px;
        z-index: 3;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        width: 100%;
    }
}
td{
    vertical-align: middle!important;
}
</style>
