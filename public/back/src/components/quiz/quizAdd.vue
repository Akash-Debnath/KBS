<template>

    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isAddCheck===true">
        <div class="right-sidebar-content-area px-2">

            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Quiz</h2>

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Start Date <span class="required">*</span></label>
                            <datepicker class="date-picker" :disabled-dates="disabledDates" v-model="quizData.start_date"></datepicker>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">End Date <span class="required">*</span></label>
                            <datepicker class="date-picker" :disabled-dates="disabledDates" v-model="quizData.end_date"></datepicker>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="title">Title <span class="required">*</span></label>
                            <input type="text" class="form-control" v-model="quizData.name" id="title" placeholder="Enter Quiz Title" @keyup="checkAndChangeValidation(quizData.name, '#title', '#titleError', '*title')" required>
                            <span id="titleError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select Articles <span class="required">*</span></label>

                            <multiselect
                                    v-model="article_value"
                                    tag-placeholder="Add this article!"
                                    placeholder="Select articles!"
                                    label="en_title"
                                    track-by="id"
                                    :options="article_options"
                                    :multiple="true"
                                    :taggable="true"
                            >
                                <!--                                @tag="addTag"-->

                            </multiselect>
                            <span id="articleIDError" class="text-danger small"></span>
                            <!--                            <pre class="language-json"><code>{{ value  }}</code></pre>-->

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select Quiz Form <span class="required">*</span></label>

                            <select class="form-control" id="quizForm" v-model="quizData.quiz_form_id" @change="checkAndValidateSelectType(), checkTotalQuestions(quizData.quiz_form_id)" >
                                <option value="" disabled>Select A Quiz Form</option>
                                <option v-for="a_quiz_form in quizformList" :value="a_quiz_form.id" :key="a_quiz_form.id">
                                    {{ a_quiz_form.name }}
                                </option>
                            </select>
                            <span id="quizFormError" class="text-danger small"></span>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="numberOfQuestions">Number Of Questions<span class="required">*</span><span class="text-bold" v-if="quizTotalQuestion"> (total question on this form: {{quizTotalQuestion}}) </span></label>
                            <input type="number" class="form-control" :max="quizTotalQuestion" min="0" step="1" v-model="quizData.number_of_questions" id="numberOfQuestions" placeholder="Ex : 10" @keyup="checkAndChangeValidation(quizData.number_of_questions, '#numberOfQuestions', '#numberOfQuestionsError', '*number of questions')" @change="checkMaxQuestionAvailability(quizData.number_of_questions)">
                            <span id="numberOfQuestionsError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="duration">Duration (Minutes)</label>
                            <input type="number" min="0" class="form-control" v-model="quizData.duration"  id="duration" placeholder="Ex : 30" @keyup="checkAndChangeValidation(quizData.duration, '#duration', '#durationError', '*duration')" @change="checkValidTime(quizData.duration)">
                            <span id="durationError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="totalMarks">Total Marks<span class="required">*</span></label>
                            <input min="0" type="number" class="form-control " v-model="quizData.total_marks" id="totalMarks" placeholder="Ex : 100" @keyup="checkAndChangeValidation(quizData.total_marks, '#totalMarks', '#totalMarksError', '*total marks')" @change="checkValidMarks(quizData.total_marks)">
                            <span id="totalMarksError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
<!--                            <label> Is Authorized? <input type="checkbox" v-model="isAuthorized" :value="1"></label>-->
<!--                            <div v-if="isAuthorized">-->
<!--                                <div class="form-group mb-2">-->
<!--                                    <label>User Groups <span class="required">*</span><span id="roleIdError" class="text-danger small"></span></label>-->

<!--                                    <ul class="list-unstyled permission-list m-0 p-0">-->
<!--                                        <li v-for="a_user in userRoles" :key="a_user.id" class="text-left pb-2">-->
<!--                                            <label  v-if="a_user.id==1" class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id.checked" :value="a_user.id"  checked disabled> {{ a_user.name }} </label>-->

<!--                                            <label v-else class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id" :value="a_user.id"> {{ a_user.name }} </label>-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </div>-->
                            <CustomRoleAdd v-if="user_roles" :userRoles="user_roles" @granted-roles="getPermissionGrantedAccess"/>
                        </div>
                    </div>

                    <!--                    status-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select A Status</label>
                            <select class="form-control" v-model="quizData.status">
                                <option value="" disabled>Select A Status</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="form-group text-right">
                    <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Add</button>
                </div>
            </div>

        </div>
    </div>

</template>

<script>

    import axios from "axios";
    import $ from "jquery";
    import Multiselect from 'vue-multiselect'
    import CustomRoleAdd from '../custom-role/CustomRoleAdd'
    import Datepicker from 'vuejs-datepicker';


    export default {
        name: "quizAdd",
        props: ['isAddCheck'],
        components: {
            Multiselect,
            CustomRoleAdd,
            Datepicker
        },

        data() {
            return {
                success_message             : '',
                error_message               : '',
                token                       : '',
                articleList                 : '',
                quizformList                : '',
                isAuthorized                : 0,
                userRoles                   : [],
                user_roles                  : '',
                role_id                     : [],
                quizTotalQuestion           : '',
                roleAccess                  : [],
                userInformation             : '',

                disabledDates: {
                    to: new Date(Date.now() - 86400000),
                },
                quizData            :{
                    start_date              : new Date(),
                    end_date                : new Date(),
                    name                    : '',
                    quiz_form_id            : '',
                    duration                : '',
                    total_marks             : '',
                    status                  : 1,
                    number_of_questions     : '',
                },

                validation_error    :{
                    isTitleStatus           : false,
                    isQuizFormStatus        : false,
                    isDurationStatus        : false,
                    isTotalMarksStatus      : false,
                    isNumberOfQuestionStatus: false,
                },

                article_value               : [],
                article_options             : [],
            }
        },
        methods: {
            getPermissionGrantedAccess(status)
            {
                this.roleAccess = status.split(',');
            },
            getAllArticleList()
            {
                let _that = this;
                axios.get('all-article-list', {
                    headers: {
                        'Authorization': 'Bearer '+localStorage.getItem('authToken')
                    },
                    params : {
                        isAdmin : 1,
                        isRole : _that.userInformation.roles[0].id
                    },}).then(function (response) {
                    console.log(response.data.article_list)
                    response.data.article_list.forEach(val => {
                        _that.article_options.push({

                            'id' : val.id,
                            'en_title' : (val.en_title).length < 50 ? val.en_title : (val.en_title).substring(0,50)+'..',
                            'slug' : val.slug
                        });
                    })


                })
            },
            checkValidMarks(val)
            {
                if (val <= 0){
                    this.validation_error.isTotalMarksStatus = false;

                    $('#totalMarks').css({
                        'border-color': '#FF7B88',
                    });
                    $('#totalMarksError').html("*total marks invalid");
                }
                else{
                    this.validation_error.isTotalMarksStatus = true;

                    $('#totalMarks').css({
                        'border-color': '#ced4da',
                    });
                    $('#totalMarksError').html("");
                }
            },
            checkValidTime(val)
            {
                if (val <= 0){
                    this.validation_error.isDurationStatus = false;
                    $('#duration').css({
                        'border-color': '#FF7B88',
                    });
                    $('#durationError').html("*duration time invalid");
                }
                else{
                    this.validation_error.isDurationStatus = true;
                    $('#duration').css({
                        'border-color': '#ced4da',
                    });
                    $('#durationError').html("");
                }
            },
            checkMaxQuestionAvailability(val)
            {
                if (this.quizTotalQuestion < val){
                    this.validation_error.isNumberOfQuestionStatus = false;

                    $('#numberOfQuestions').css({
                        'border-color': '#FF7B88',
                    });
                    $('#numberOfQuestionsError').html("*maximum number of questions exceeded");
                }
                else if (val == 0) {
                    this.validation_error.isNumberOfQuestionStatus = false;

                    $('#numberOfQuestions').css({
                        'border-color': '#FF7B88',
                    });
                    $('#numberOfQuestionsError').html("*minimum number of questions exceeded");
                }
                else if (val < 0) {
                    this.validation_error.isNumberOfQuestionStatus = false;

                    $('#numberOfQuestions').css({
                        'border-color': '#FF7B88',
                    });
                    $('#numberOfQuestionsError').html("*minimum number of questions exceeded");
                }
                else if (val > 0) {
                    //right
                    console.log('no error');
                    this.validation_error.isNumberOfQuestionStatus = true;

                    $('#numberOfQuestions').css({
                        'border-color': '#ced4da',
                    });
                    $('#numberOfQuestionsError').html("");
                }
            },
            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    if (selected_name === "*title"){

                        if (selected_data.length <3){
                            $(selected_id).css({
                                'border-color': '#FF7B88',
                            });
                            $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                            if (selected_name === "*title"){
                                this.validation_error.isTitleStatus = false;
                            }

                        }else {
                            $(selected_id).css({
                                'border-color': '#ced4da',
                            });
                            $(selected_error_id).html("");

                            if (selected_name === "*title"){
                                this.validation_error.isTitleStatus = true;
                            }
                        }
                    }else{
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*duration"){
                            this.validation_error.isDurationStatus = true;
                        }else if(selected_name === "*total marks"){
                            this.validation_error.isTotalMarksStatus = true;
                        }else if(selected_name === "*number of questions"){
                            this.validation_error.isNumberOfQuestionStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*title"){
                        this.validation_error.isTitleStatus = true;
                    }else if (selected_name === "*duration"){
                        this.validation_error.isDurationStatus = true;
                    }else if(selected_name === "*total marks"){
                        this.validation_error.isTotalMarksStatus = true;
                    }else if(selected_name === "*number of questions"){
                        this.validation_error.isNumberOfQuestionStatus = true;
                    }
                }
            },
            checkAndValidateSelectType()
            {
                if (!this.quizData.quiz_form_id) {
                    $('#quizForm').css({
                        'border-color': '#FF7B88',
                    });
                    $('#quizFormError').html("*quiz form field is required");
                    this.validation_error.isQuizFormStatus = false;

                } else{
                    $('#quizForm').css({
                        'border-color': '#ced4da',
                    });
                    $('#quizFormError').html("");
                    this.validation_error.isQuizFormStatus = true;
                }
            },
            validateAndSubmit()
            {
                if (!this.quizData.name){
                    $('#title').css({
                        'border-color': '#FF7B88',
                    });
                    $('#titleError').html("*title field is required");
                }
                if (!this.quizData.quiz_form_id){
                    $('#quizForm').css({
                        'border-color': '#FF7B88',
                    });
                    $('#quizFormError').html("*quiz form field is required");
                }
                if (!this.quizData.duration){
                    $('#duration').css({
                        'border-color': '#FF7B88',
                    });
                    $('#durationError').html("*duration field is required");
                }
                if (!this.quizData.total_marks){
                    $('#totalMarks').css({
                        'border-color': '#FF7B88',
                    });
                    $('#totalMarksError').html("*total marks field is required");
                }
                if (!this.quizData.number_of_questions){
                    $('#numberOfQuestions').css({
                        'border-color': '#FF7B88',
                    });
                    $('#numberOfQuestionsError').html("*number of questions field is required");
                }

                console.log(this.validation_error);

                if (this.validation_error.isTitleStatus === true &&
                    this.validation_error.isQuizFormStatus === true &&
                    this.validation_error.isDurationStatus === true &&
                    this.validation_error.isTotalMarksStatus === true &&
                    this.validation_error.isNumberOfQuestionStatus === true){
                    //console.log(this.validation_error)
                    this.quizAdd();
                }
            },
            showServerError(errors)
            {
                $('#titleError').html("");
                $('#quizFormError').html("");
                $('#durationError').html("");
                $('#totalMarksError').html("");
                $('#numberOfQuestionsError').html("");

                $('#title').css({'border-color': '#ced4da'});
                $('#quizForm').css({'border-color': '#ced4da'});
                $('#duration').css({'border-color': '#ced4da'});
                $('#totalMarks').css({'border-color': '#ced4da'});
                $('#numberOfQuestions').css({'border-color': '#ced4da'});

                errors.forEach(val=>{

                    if (val.includes("name") === true){

                        $('#titleError').html(val)
                        $('#title').css({'border-color': '#FF7B88'});
                    }else if(val.includes("quiz form") === true){
                        $('#quizFormError').html(val)
                        $('#quizForm').css({'border-color': '#FF7B88'});

                    }else if(val.includes("duration") === true){
                        $('#durationError').html(val)
                        $('#duration').css({'border-color': '#FF7B88'});

                    }else if(val.includes("total marks") === true){
                        $('#totalMarksError').html(val)
                        $('#totalMarks').css({'border-color': '#FF7B88'});

                    }else if(val.includes("number of questions") === true){
                        $('#numberOfQuestionsError').html(val)
                        $('#numberOfQuestions').css({'border-color': '#FF7B88'});
                    }
                    else if(val.includes("article id")){
                        $('#articleIDError').html(val)
                    }
                })
            },

            quizAdd() {
                let _that = this;

                if (_that.roleAccess.length == 0){
                    _that.roleAccess.push(1);
                }
                // console.log(_that.roleAccess);

                let collection =  _that.roleAccess.join();

                if (collection == '') {
                    collection = '1'
                }

                axios.post('quizzes',
                    {
                        name                    : _that.quizData.name,
                        article_id              : _that.article_value,
                        quiz_form_id            : _that.quizData.quiz_form_id,
                        duration                : _that.quizData.duration,
                        total_marks             : _that.quizData.total_marks,
                        status                  : _that.quizData.status,
                        number_of_questions     : _that.quizData.number_of_questions,
                        // is_authorized           : this.isAuthorized,
                        role_id                 : collection,
                        // start_date              : _that.quizData.start_date.toISOString().slice(0,10),
                        // end_date                 :_that.quizData.end_date.toISOString().slice(0,10)
                        start_date                 :_that.quizData.start_date,
                        end_date                 :_that.quizData.end_date

                    },
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {

                    if (response.data.status_code === 200){
                        _that.quizData         = '';
                        _that.error_message    = '';
                        _that.success_message  = "Quiz added successfully";
                        _that.$emit('quiz-slide-close', _that.success_message);

                    }else if(response.data.status_code === 400){

                        _that.success_message = "";
                        _that.error_message   = "";
                        _that.showServerError(response.data.errors);

                    }else{
                        _that.success_message  = "";
                        _that.error_message    = response.data.message;
                    }

                }).catch(function (error) {
                    console.log(error);
                });

            },


            getQuizFormList(){
                let _that =this;

                axios.get('quiz-forms',
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){

                            _that.quizformList = response.data.quiz_form_list.data;

                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },

            checkTotalQuestions(quizFormID){
                this.getQuizFormFieldDetails(quizFormID);
            },


            getUserRoles()
            {
                let _that =this;
                axios.get('roles',
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                        params : {
                            isAdmin : 1,
                            without_pagination : 1
                        },
                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            _that.user_roles = response.data.role_list;

                            // response.data.role_list.forEach(val => {
                            //     _that.userRoles.push({
                            //         'id' : val.id,
                            //         'text' : val.name
                            //     })
                            // })
                            // console.log(_that.userRoles);
                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },


            getQuizFormFieldDetails(quizFormID)
            {
                let _that               = this;

                _that.quizData.number_of_questions = '';

                axios.get("quiz-form/quiz-field-list/"+quizFormID,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            _that.quizTotalQuestion  = response.data.total_quiz_count;
                        } else {
                            _that.success_message       = "";
                            _that.error_message         = response.data.error;
                        }
                    })
            },

        },
        created() {
            /*  this.isAdd = this.isAddCheck;*/
            this.userInformation          = JSON.parse(localStorage.getItem("userInformation"));
            this.getQuizFormList();
            this.getUserRoles();
            this.getAllArticleList();
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
    .multiselect__tags {
        background: transparent !important;
    }
</style>
