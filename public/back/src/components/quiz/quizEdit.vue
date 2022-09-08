<template>

    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">

            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Quiz Edit</h2>

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
                            <label>Select Quiz Form</label>

                            <select class="form-control" id="quizForm" v-model="quizData.quiz_form_id" @change="checkAndValidateSelectType(), checkTotalQuestions(quizData.quiz_form_id)">
                                <option value="" disabled>Select A Quiz Form</option>
                                <option v-for="a_quiz_form in quizformList" :value="a_quiz_form.id" :key="a_quiz_form.id">
                                    {{a_quiz_form.name}}
                                </option>
                            </select>
                            <span id="quizFormError" class="text-danger small"></span>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="numberOfQuestions">Number Of Questions<span class="required">*</span><span class="text-bold" v-if="quizTotalQuestion"> (total question on this form: {{quizTotalQuestion}}) </span></label>
                            <input type="number" :max="quizTotalQuestion" min="0" step="1" class="form-control" v-model="quizData.number_of_questions" id="numberOfQuestions" placeholder="Ex : 10" @keyup="checkAndChangeValidation(quizData.number_of_questions, '#numberOfQuestions', '#numberOfQuestionsError', '*number of questions')" @change="checkMaxQuestionAvailability(quizData.number_of_questions)">
                            <span id="numberOfQuestionsError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="duration">Duration (Minutes)</label>
                            <input type="number" min="0" class="form-control" v-model="quizData.duration" id="duration" placeholder="Ex : 30" @keyup="checkAndChangeValidation(quizData.duration, '#duration', '#durationError', '*duration')" @change="checkValidTime(quizData.duration)">
                            <span id="durationError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="totalMarks">Total Marks<span class="required">*</span></label>
                            <input type="number" min="0" class="form-control" v-model="quizData.total_marks" id="totalMarks" placeholder="Ex : 100" @keyup="checkAndChangeValidation(quizData.total_marks, '#totalMarks', '#totalMarksError', '*total marks')" @change="checkValidMarks(quizData.total_marks)">
                            <span id="totalMarksError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
<!--                            <label> Is Authorized? <input type="checkbox" v-model="isAuthorized" :value="1"></label> -->
<!--                            <div v-if="isAuthorized">-->
<!--                                &lt;!&ndash; <label>User Groups <span class="required">*</span></label>-->
<!--                                <select2 id="articleIDError" v-model="roles" :options="userRoles" :settings="{ settingOption: roles, settingOption: roles,multiple: true}" @change="myChangeEvent($event)" @select="mySelectEvent($event)"/> &ndash;&gt;-->
<!--                                <div class="form-group mb-2">-->
<!--                                    <label>User Groups <span class="required">*</span><span id="roleIdError" class="text-danger small"></span></label>-->

<!--                                    <ul class="list-unstyled permission-list m-0 p-0">-->
<!--                                        <li v-for="a_user in userRoles" :key="a_user.id" class="text-left pb-2">-->
<!--                                            <label  v-if="a_user.id==1" class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id.checked" :value="a_user.id"  checked disabled> {{ a_user.name }} </label>-->
<!--                                            -->
<!--                                            <label v-else class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id" :value="a_user.id"> {{ a_user.name }} </label>-->
<!--                                        </li>-->
<!--                                    </ul>-->
<!--                                </div>-->

<!--                            </div>     -->
                            <CustomRoleEdit v-if="user_roles && quizData" :userRoles="user_roles" :banneRoleAccess="quizData.role_id" @granted-roles="getPermissionGrantedAccess"/>

                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select Quiz Form</label>

                            <select class="form-control" v-model="quizData.status">
                                <option value="" disabled>Select A Status</option>
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>

                    </div>

                </div>

                <div class="form-group text-right">
                    <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Update</button>
                </div>
            </div>

        </div>
    </div>

</template>

<script>

import axios from "axios";
import Multiselect from 'vue-multiselect'
import CustomRoleEdit from '../custom-role/CustomRoleEdit'
import $ from "jquery";
import Datepicker from 'vuejs-datepicker';

export default {
    name: "quizEdit",
    props: ['isEditCheck', 'quizId'],

    components: {
        Multiselect,
        CustomRoleEdit,
        Datepicker
    },

    data() {
        return {
            success_message : '',
            error_message   : '',
            token           : '',
            articleList     : '',
            quizformList    : '',
            quizTotalQuestion : '',
            user_roles      : '',
            roleAccess : [],
            userInformation : '',

            quiz_id         : '',
            quizDetails     : '',
            isAuthorized    : 0,
            userRoles       :[],
            role_id         :[],
            disabledDates: {
                    to: new Date(Date.now() - 86400000),
                },
            quizData        :
                {
                    name          : '',
                    article_id    : '',
                    quiz_form_id  : '',
                    duration      : '',
                    total_marks   : '',
                    status        : '',
                    number_of_questions   : '',
                    start_date    :'',
                    end_date      : '',
                },

            validation_error :{
                isTitleStatus : true,
                isQuizFormStatus : true,
                isDurationStatus : true,
                isTotalMarksStatus : true,
                isNumberOfQuestionStatus : true,
            },

            article_value: [],
            article_options: [],
            previous_included_list : ''
        }
    },

    methods: {
        getPermissionGrantedAccess(status){
            console.log(status)
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
                console.log(response.data.article_list);
                response.data.article_list.forEach(val => {

                    _that.article_options.push({

                        'id' : val.id,
                        'en_title' : (val.en_title).length < 50 ? val.en_title : (val.en_title).substring(0,50)+'..',
                    });


                })


            })
        },
        checkTotalQuestions(quizFormID){
            this.getQuizFormFieldDetails(quizFormID);
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
        checkValidMarks(val){
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
        checkValidTime(val){
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
                // error
                console.log('error');
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
                this.quizUpdate();
            }
        },

        showServerError(errors)
        {

            $('#titleError').html("");
            $('#quizFormError').html("");
            $('#durationError').html("");
            $('#totalMarksError').html("");
            $('#numberOfQuestionsError').html("");
            $('#articleIDError').html("");

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
                }else if(val.includes("article id")){
                    $('#articleIDError').html(val)
                }


            })
        },

        quizUpdate()
        {

            let _that           = this;
            let quizID          = this.quiz_id;
            let collection;
            collection          = _that.roleAccess.join();

            axios.put('quizzes/update',
                {
                    id                  : quizID,
                    name                : this.quizData.name,
                    article_id          : this.article_value,
                    quiz_form_id        : this.quizData.quiz_form_id,
                    duration            : this.quizData.duration,
                    total_marks         : this.quizData.total_marks,
                    status              : this.quizData.status,
                    number_of_questions : this.quizData.number_of_questions,
                    role_id             : collection,
                    start_date          :this.quizData.start_date,
                    end_date            :this.quizData.end_date
                },
                {
                    headers: {
                        'Authorization' : 'Bearer '+ localStorage.getItem('authToken')
                    }
                }).then(function (response){
                    if (response.data.status_code === 200) {
                    _that.quizData          = '';
                    _that.error_message     = '';

                    _that.success_message   = "Quiz updated successfully";
                    _that.$emit('quiz-slide-close', _that.success_message);
                } else if(response.data.status_code === 400){

                    _that.success_message   = "";
                    _that.error_message     = "";
                    _that.showServerError(response.data.errors);
                }else{
                    _that.success_message   = "";
                    _that.error_message     = response.data.message;
                }

            }).catch(function (error) {
                console.log(error);
            });

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

        getQuizDetails() {

            let _that = this;
            let quizID = this.quiz_id;

            let apiUrl = "quizzes/";

            axios.get(apiUrl+quizID,
                {
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                    },
                })
                .then(function (response) {
                    if (response.data.status_code === 200) {
                        _that.quizDetails                   = response.data.quiz_info;
                        _that.quizData.name                 = _that.quizDetails.name;
                        _that.quizData.article_id           = _that.quizDetails.article_id;
                        _that.quizData.quiz_form_id         = _that.quizDetails.quiz_form_id;
                        _that.quizData.duration             = _that.quizDetails.duration;
                        _that.quizData.total_marks          = _that.quizDetails.total_marks;
                        _that.quizData.status               = _that.quizDetails.status;
                        _that.quizData.number_of_questions  = _that.quizDetails.number_of_questions;
                        _that.quizData.role_id              = _that.quizDetails.role_id;
                        _that.quizData.start_date           = _that.quizDetails.start_date;
                        _that.quizData.end_date             = _that.quizDetails.end_date;
                        if ((_that.quizData.role_id).includes(',')) {

                            _that.roleAccess                = (_that.quizData.role_id).split(',');
                            _that.roleAccess                = _that.roleAccess.map(i=>Number(i))
                        }else{
                            _that.roleAccess.push(_that.quizData.role_id);
                        }
                    } else {
                        _that.success_message               = "";
                        _that.error_message                 = response.data.error;
                    }
                })
        },

        getArticleList()
        {
            let _that =this;

            axios.get('articles',
                {
                    headers: {
                        'Authorization': 'Bearer '+localStorage.getItem('authToken')
                    },
                    params :
                        {
                            isAdmin : 1
                        },
                })
                .then(function (response) {
                    if(response.data.status_code === 200){
                        _that.articleList = response.data.article_list.data;
                    }
                    else{
                        _that.success_message = "";
                        _that.error_message   = response.data.error;
                    }
                })
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
                        // console.log(response.data.quiz_form_list);
                        _that.quizformList = response.data.quiz_form_list.data;
                    } else{
                        _that.success_message = "";
                        _that.error_message   = response.data.error;
                    }
                })
        },

    },

    created() {
        this.userInformation          = JSON.parse(localStorage.getItem("userInformation"));

        this.quiz_id = this.quizId;
        this.getQuizDetails();
        this.getAllArticleList();

        this.getArticleList();
        this.getQuizFormList();
        this.getUserRoles();
    }
}
</script>

<!--<style scoped>-->

<!--</style>-->
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
