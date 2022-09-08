<template>
    <div class="container-fluid">
        <DropMenu/>
        <div class="row">

            <div class="col-md-10 mx-auto quiz-meta-area-parent">
                <h2 class="py-4 text-primary">Available Quizzes</h2>
                <div class="d-flex" v-for="quiz in allQuizzes" :key="quiz.id">

                    <div class="my-2 text-center">
                        <div class="quiz-meta-area">

                            <div class="quiz-meta">
                                <div class="quiz-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-card-checklist" viewBox="0 0 16 16">
                                        <path
                                            d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                        <path
                                            d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </div>
                                <div class="quiz-meta-description">
                                    <strong>QUIZ NAME</strong>
                                    <small>{{ quiz.name }}</small>
                                </div>

                            </div>

                            <div class="quiz-meta">
                                <div class="quiz-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-stopwatch" viewBox="0 0 16 16">
                                        <path
                                            d="M8.5 5.6a.5.5 0 1 0-1 0v2.9h-3a.5.5 0 0 0 0 1H8a.5.5 0 0 0 .5-.5V5.6z"/>
                                        <path
                                            d="M6.5 1A.5.5 0 0 1 7 .5h2a.5.5 0 0 1 0 1v.57c1.36.196 2.594.78 3.584 1.64a.715.715 0 0 1 .012-.013l.354-.354-.354-.353a.5.5 0 0 1 .707-.708l1.414 1.415a.5.5 0 1 1-.707.707l-.353-.354-.354.354a.512.512 0 0 1-.013.012A7 7 0 1 1 7 2.071V1.5a.5.5 0 0 1-.5-.5zM8 3a6 6 0 1 0 .001 12A6 6 0 0 0 8 3z"/>
                                    </svg>
                                </div>
                                <div class="quiz-meta-description">
                                    <strong>DURATION</strong>
                                    <small>{{ quiz.duration }} Min</small>
                                </div>

                            </div>

                            <div class="quiz-meta">
                                <div class="quiz-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-question-circle" viewBox="0 0 16 16">
                                        <path
                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path
                                            d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                                    </svg>
                                </div>
                                <div class="quiz-meta-description">
                                    <strong>NUMBER OF QUESTIONS</strong>
                                    <small>{{ quiz.number_of_questions }}</small>
                                </div>

                            </div>

                            <div class="quiz-meta">
                                <div class="quiz-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-bookmark-check-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                              d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5zm8.854-9.646a.5.5 0 0 0-.708-.708L7.5 7.793 6.354 6.646a.5.5 0 1 0-.708.708l1.5 1.5a.5.5 0 0 0 .708 0l3-3z"/>
                                    </svg>
                                </div>
                                <div class="quiz-meta-description">
                                    <strong> TOTAL MARKS</strong>
                                    {{ quiz.total_marks }}
                                </div>

                            </div>

                            <div class="quiz-meta">
                                <div class="quiz-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-calendar-event-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4V.5zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2zm-3.5-7h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5z"/>
                                    </svg>
                                </div>
                                <div class="quiz-meta-description">
                                    <strong> START / END DATE</strong>
                                    {{ quiz.start_date }} / {{ quiz.end_date }}
                                </div>

                            </div>

                            <div class="quiz-meta" v-if="new Date().toISOString().slice(0,10)>=new Date(quiz.start_date).toISOString().slice(0,10) && new Date(quiz.end_date).toISOString().slice(0,10)>=new Date().toISOString().slice(0,10)">
                                <div class="quiz-image">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-smartwatch" viewBox="0 0 16 16">
                                        <path d="M9 5a.5.5 0 0 0-1 0v3H6a.5.5 0 0 0 0 1h2.5a.5.5 0 0 0 .5-.5V5z"/>
                                        <path
                                            d="M4 1.667v.383A2.5 2.5 0 0 0 2 4.5v7a2.5 2.5 0 0 0 2 2.45v.383C4 15.253 4.746 16 5.667 16h4.666c.92 0 1.667-.746 1.667-1.667v-.383a2.5 2.5 0 0 0 2-2.45V8h.5a.5.5 0 0 0 .5-.5v-2a.5.5 0 0 0-.5-.5H14v-.5a2.5 2.5 0 0 0-2-2.45v-.383C12 .747 11.254 0 10.333 0H5.667C4.747 0 4 .746 4 1.667zM4.5 3h7A1.5 1.5 0 0 1 13 4.5v7a1.5 1.5 0 0 1-1.5 1.5h-7A1.5 1.5 0 0 1 3 11.5v-7A1.5 1.5 0 0 1 4.5 3z"/>
                                    </svg>
                                </div>


                                <div class="quiz-meta-description ">
                                    <a class="text-white text-decoration-none d-flex flex-column">
                                        <button class="text-white btn btn-danger btn-sm"  @click="quizStart(quiz)">START</button>
                                        <small>Get Ready now</small>
                                    </a>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>


    </div>
</template>

<script>
import {ref, computed, onBeforeUpdate, onMounted, defineComponent} from "vue";
import {ApiCallMaker} from "../../api/ApiCallMaker";
import DropMenu from "../DropMenu.vue";

export default defineComponent({
    components: {DropMenu},
    setup() {
        const allQuizzes = ref({});
        const userInformation = ref({});
        const userToken = ref('');

        onMounted(() => {
            if (sessionStorage.userInformation) {
                userInformation.value = JSON.parse(sessionStorage.userInformation);
            } else {
                userInformation.value = '';
            }

            if (sessionStorage.userToken) {
                userToken.value = sessionStorage.userToken;
                // console.log('token is', userToken.value);
                getQuizList();

            }
        });

        async function getQuizList() {
            const params = {
                            isAuthorised : 1,
                            isRoleID : userInformation.value.roles[0].id
                        };
            const response = await ApiCallMaker('GET', 'get-quiz-list', '', userToken.value, params);
            if (response.data.status_code == 200) {
                allQuizzes.value = response.data.quiz_form_list;
                console.log(allQuizzes.value);
            } else {
                console.log(response.data.messages);
            }
        }

        function quizStart(quizInfo){
            this.$router.push({name: 'QuizTemplate', params: { quiz_info: JSON.stringify(quizInfo) }});
        }

        return {userInformation, userToken, allQuizzes,quizStart}
    }
});

</script>

<style scoped lang="scss">

.quiz-meta-area-parent {
    min-height: 65vh;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
}

.quiz-meta-area {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    justify-content: space-between;
    color: #ffffff;
    background-color: lighten(#00783f, 3%);
    padding: 2rem 1rem;
    border-radius: 1rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2);
}

.quiz-meta {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: flex-start;
}

.quiz-meta-description {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    text-align: left;
}


/*============================
        Quiz Template
============================*/
.g-main-question-slot {
    display: block;
    position: relative;
    margin: 1rem 0 2rem;
    height: auto;
    padding: 2rem;
    background-color: #e7f0ec;
    border: 1px solid #d4eae0;
    box-shadow: 0 0.2rem 0.2rem rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    @media all and (max-width: 768px) {
        padding: 1rem 0.5rem;
    }

    h4 {
        color: #00783f;
    }

    ul {
        list-style: none;
        margin: 0;
        padding: 0;
        overflow: auto;
    }

    ul li {
        color: #00783f;
        display: block;
        position: relative;
        float: left;
        width: 100%;
    }

    ul li input[type=radio] {
        position: absolute;
        visibility: hidden;
    }

    ul li label {
        display: block;
        position: relative;
        font-weight: 300;
        font-size: 1.35em;
        padding: 25px 25px 25px 80px;
        margin: 10px auto;
        height: 30px;
        z-index: 9;
        cursor: pointer;
        -webkit-transition: all 0.25s linear;
    }

    ul li:hover label {
        color: lighten(#00783f, 10%);;
    }

    ul li .check {
        display: block;
        position: absolute;
        border: 5px solid #00783f;
        border-radius: 100%;
        height: 25px;
        width: 25px;
        top: 40px;
        left: 20px;
        z-index: 5;
        transition: border .25s linear;
        -webkit-transition: border .25s linear;
    }

    ul li:hover .check {
        border: 5px solid lighten(#00783f, 10%);
    }

    ul li .check::before {
        display: block;
        position: absolute;
        content: '';
        border-radius: 100%;
        height: 15px;
        width: 15px;
        top: 0;
        left: 0;
        margin: auto;
        transition: background 0.25s linear;
        -webkit-transition: background 0.25s linear;
    }
}

input[type=radio]:checked ~ .check {
    border: 5px solid #00783f;
}

input[type=radio]:checked ~ .check::before {
    background: #00783f;
}

input[type=radio]:checked ~ label {
    color: #00783f;
}

.g-question-btn-area {
    display: flex;
    justify-content: space-between;
    padding: 20px;
}

.quiz-user-image {
    img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
    }
}
</style>
