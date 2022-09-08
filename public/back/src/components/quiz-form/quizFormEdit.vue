<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Quiz Form Edit</h2>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="quizFormName">Quiz Form Name<span class="required">*</span></label>
                            <input class="form-control" @keyup="checkAndChangeValidation(quizFormDetails.name, '#quizFormName', '#formNameError', '*quiz form name')" v-on:keyup.enter="validateAndSubmit()" type="text" id="quizFormName" v-model="quizFormDetails.name" required>
                            <span id="formNameError" class="text-danger small"></span>
                        </div>
                    </div>


                    <div class="col-md-8">
                        <div class="form-group text-left">
                            <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import $ from "jquery";

    export default {

        name: "quizFormEdit.vue",
        components: {
        },
        props: ['isEditCheck', 'quizFormId'],

        data() {
            return {
                isMounted               : false,
                success_message         : '',
                error_message           : '',
                token                   : '',
                selectedId              : '',
                quizFormDetails         : '',
                roles                   : '',
                userRoles               : '',

                filter  : {
                    isAdmin             : 1
                },
                validation_error:{
                    isFormNameStatus: true
                }
            }
        },

        methods: {
            validateAndSubmit()
            {
                if (!this.quizFormDetails.name){
                    $('#quizFormName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#formNameError').html("*quiz form name field is required");
                }


                if (this.validation_error.isFormNameStatus === true){
                    //console.log(this.validation_error)
                    this.quizFormUpdate();
                }
            },

            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                        if (selected_name === "*quiz form name"){
                            this.validation_error.isFormNameStatus = false;
                        }

                    }
                    else if (selected_data.length >100){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain maximum 100 character");

                        if (selected_name === "*quiz form name"){
                            this.validation_error.isFormNameStatus = false;
                        }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*quiz form name" ){
                            this.validation_error.isFormNameStatus = true;
                        }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "*quiz form name" ){
                        this.validation_error.isFormNameStatus = false;
                    }
                }
            },
            showServerError(errors)
            {
                $('#formNameError').html("");
                $('#quizFormName').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("name") === true){
                        $('#formNameError').html(val)
                        $('#quizFormName').css({'border-color': '#FF7B88'});
                    }
                })
            },

            quizFormUpdate() {
                let _that = this;
                let quizFormID = _that.selectedId;

                axios.put('quiz-forms/update',
                    {
                        id        : quizFormID,
                        name      : _that.quizFormDetails.name,
                    },
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code === 200){
                        _that.quizFormDetails   = '';
                        _that.error_message     = '';
                        _that.success_message   = "Quiz form updated successfully!";
                        _that.$emit('quiz-form-slide-close', _that.success_message);
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

            getQuizFormDetails() {
                let _that = this;
                let quizFormID = this.selectedId;

                axios.get("quiz-forms/"+quizFormID,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            _that.quizFormDetails = response.data.quiz_form_info;
                        } else {
                            _that.success_message = "";
                            _that.error_message = response.data.error;
                        }
                    })
            },
        },
        created()
        {
            this.selectedId = this.quizFormId;
            this.getQuizFormDetails();
        }
    }
</script>

<style scoped>

</style>
