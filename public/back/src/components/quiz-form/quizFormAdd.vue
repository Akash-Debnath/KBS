<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Quiz Form</h2>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="quizFormName">Quiz Form Title <span class="required">*</span></label>
                            <input class="form-control" type="text" v-model="quizFormData.name" @keyup="checkAndChangeValidation(quizFormData.name, '#quizFormName', '#formNameError', '*quiz form name')" @keyup.enter="validateAndSubmit()"  id="quizFormName" placeholder="Enter quiz form title here!!" required>
                            <span id="formNameError" class="text-danger small"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group text-left">
                            <button class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Add</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import $ from "jquery";

    export default {
        name: "quizFormAdd.vue",
        props: ['isAddCheck'],
        components: {

        },

        data() {
            return {
                isAdd           : false,
                isSearch        : false,
                success_message : '',
                error_message   : '',
                token           : '',
                userRoles       :'',

                quizFormData    : {
                    name        : '',
                },

                validation_error:{
                    isFormNameStatus: false
                }
            }
        },


        methods: {

            validateAndSubmit()
            {
                if (!this.quizFormData.name){
                    $('#quizFormName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#formNameError').html("*quiz form name field is required");
                }


                if (this.validation_error.isFormNameStatus === true){
                    //console.log(this.validation_error)
                    this.quizFormAdd();
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

            quizFormAdd()
            {
                let _that       = this;
                axios.post('quiz-forms',
                    {
                        name    : this.quizFormData.name,
                    },
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code === 201){
                        _that.quizFormData     = '',
                        _that.error_message    = '';
                        _that.success_message  = "Quiz form added successfully";

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
        },
        created() {
            this.isAdd = this.isAddCheck;
        }
    }
</script>

<style scoped>

</style>
