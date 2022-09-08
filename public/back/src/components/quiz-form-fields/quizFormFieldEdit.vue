<template>

    <div class="right-sidebar-content-wrapper position-relative overflow-hidden"  v-if="isEditCheck">
        <div class="right-sidebar-content-area px-2">

            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Quiz Form Field Edit</h2>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="quizFormID">Quiz Form ID <span class="required">*</span></label>
                            <input id="quizFormID" type="text" readonly v-model="quizFormFieldDetails.quiz_form_id" class="form-control" placeholder="">
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="quizFormFieldLabel">Field Label <span class="required">*</span></label>
                            <input id="quizFormFieldLabel" type="text" v-model="quizFormFieldDetails.f_label" class="form-control" placeholder="Enter Label Name" @keyup="checkAndChangeValidation(quizFormFieldDetails.f_label, '#quizFormFieldLabel', '#fieldLabelError', '*field label')">
                            <span id="fieldLabelError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldOptionValue">Field Option Value</label>
                            <input id="quizFormFieldOptionValue" type="text" v-model="quizFormFieldDetails.f_option_value"  class="form-control" placeholder="Enter Field Option Value">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldOptionDefaultValue">Field Answer <span class="required">*</span></label>
                            <input id="quizFormFieldOptionDefaultValue" type="text" v-model="quizFormFieldDetails.f_default_value"  class="form-control" placeholder="Enter Field Option Default Value" @keyup="checkQuizDefaultValue(quizFormFieldDetails.f_default_value)">
                            <span id="fieldOptionDefaultValue" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldName">Field Name <span class="required">*</span></label>
                            <input id="quizFormFieldName" type="text" v-model="quizFormFieldDetails.f_name" class="form-control" placeholder="Enter Field Name" @keyup="giveIdClass(quizFormFieldDetails.f_name),checkAndChangeValidation(quizFormFieldDetails.f_name, '#quizFormFieldName', '#fieldNameError', '*field name')" @change="giveIdClass(quizFormFieldDetails.f_name)">
                            <span id="fieldNameError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldID">Field ID</label>
<!--                            @keyup="checkAndChangeValidation(quizFormFieldDetails.f_id, '#quizFormFieldID', '#fieldIDError', '*field ID')"-->
                            <input id="quizFormFieldID" type="text" v-model="quizFormFieldDetails.f_id"  class="form-control" placeholder="Enter Field ID">
                            <span id="fieldIDError" class="text-danger small"></span>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldClass">Field Class</label>
<!--                            @keyup="checkAndChangeValidation(quizFormFieldDetails.f_class, '#quizFormFieldClass', '#fieldClassError', '*field class')"-->
                            <input id="quizFormFieldClass" type="text" v-model="quizFormFieldDetails.f_class"  class="form-control" placeholder="Enter Field Class">
                            <span id="fieldClassError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldType">Field Type <span class="required">*</span></label>
                            <select id="quizFormFieldType" class="form-control" v-model="quizFormFieldDetails.f_type">
                                <option value="" disabled>--Select A Type--</option>
                                <option value="Text">Text</option>
                                <option value="Email">Email</option>
                                <option value="Password">Password</option>
                                <option value="Number">Number</option>
                                <option value="Textarea">Textarea</option>
                                <option value="Radio">Radio</option>
                                <option value="Checkbox">Checkbox</option>
                                <option value="Select/Dropdown">Select/Dropdown</option>
                            </select>
                            <span id="fieldTypeError" class="text-danger small"></span>
                        </div>
                    </div>


<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="quizFormFieldOptionValue">Field Option Value</label>-->
<!--                            <input id="quizFormFieldOptionValue" type="text" v-model="quizFormFieldDetails.f_option_value"  class="form-control" placeholder="Enter Field Option Value">-->
<!--                        </div>-->
<!--                    </div>-->

<!--                    <div class="col-md-4">-->
<!--                        <div class="form-group">-->
<!--                            <label for="quizFormFieldOptionDefaultValue">Question Answer <span class="required">*</span></label>-->
<!--                            <input id="quizFormFieldOptionDefaultValue" type="text" v-model="quizFormFieldDetails.f_default_value"  class="form-control" placeholder="Enter Field Option Default Value">-->
<!--                        </div>-->
<!--                    </div>-->


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldMaxValue">Field Max Value</label>
                            <input id="quizFormFieldMaxValue" type="number" v-model="quizFormFieldDetails.f_max_value"  class="form-control" placeholder="Enter Max Number">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldSortValue">Field Sort Value</label>
                            <input id="quizFormFieldSortValue" type="number" v-model="quizFormFieldDetails.f_sort_order"  class="form-control" placeholder="Enter Sort Number">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="quizFormFieldRequired">Field Required <span class="required">*</span></label>

                            <select id="quizFormFieldRequired" class="form-control" v-model="quizFormFieldDetails.f_required">
                                <option disabled>--Select Status--</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
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

        name: "quizFormFieldEdit.vue",
        components: {
        },
        props: ['isEditCheck', 'quizFormFieldId'],

        data() {
            return {
                isMounted           : false,
                isEdit              : false,
                success_message     : '',
                error_message       : '',
                token               : '',
                selectedId          :'',
                quizFormFieldDetails:'',
                roles               :'',
                userRoles           :'',
                filter  : {
                    isAdmin         : 1
                },
                validation_error:{
                    isFieldLabelStatus      : true,
                    isFieldNameStatus       : true,
                    // isFieldIDStatus         : true,
                    // isFieldClassStatus      : true,
                    isFieldDefaultValue : true,
                    isFieldTypeStatus       : true,
                }
            }
        },

        methods: {
            checkQuizDefaultValue(val){
                if (val.length > 0) {
                    $('#quizFormFieldOptionDefaultValue').css({
                        'border-color': '#ced4da',
                    });
                    $('#fieldOptionDefaultValue').html("");
                    this.validation_error.isFieldDefaultValue =  true;

                } else{
                    $('#quizFormFieldOptionDefaultValue').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldOptionDefaultValue').html("*field default is required");

                    this.validation_error.isFieldDefaultValue =  false;
                }
            },
            giveIdClass(val){
                console.log(val);
                val = val.replace(/[\W_]/g, "_");
                val = val.replace(" ","_");
                this.quizFormFieldDetails.f_name = val;
                this.quizFormFieldDetails.f_id = val;
                this.quizFormFieldDetails.f_class = val;
            },
            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {
                if (selected_data.length >0) {
                    if (selected_data.length <3){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain minimum 3 character");

                        if (selected_name === "*field name"){
                            this.validation_error.isFieldNameStatus = false;
                        } else if(selected_name === "*field label"){
                            this.validation_error.isFieldLabelStatus = false;
                        }
                        // else if(selected_name === "*field ID"){
                        //     this.validation_error.isFieldIDStatus = false;
                        // }else if(selected_name === "*field class"){
                        //     this.validation_error.isFieldClassStatus = false;
                        // }

                    }
                    else if (selected_data.length >200){
                        $(selected_id).css({
                            'border-color': '#FF7B88',
                        });
                        $(selected_error_id).html( selected_name+" should contain maximum 200 character");

                        if (selected_name === "*field name"){
                            this.validation_error.isFieldNameStatus = false;
                            // parallel
                            this.validation_error.isFieldIDStatus = false;
                            this.validation_error.isFieldClassStatus = false;

                        }else if(selected_name === "*field label"){
                            this.validation_error.isFieldLabelStatus = false;
                        }
                        // else if(selected_name === "*field ID"){
                        //     this.validation_error.isFieldIDStatus = false;
                        // }else if(selected_name === "*field class"){
                        //     this.validation_error.isFieldClassStatus = false;
                        // }
                    }else {
                        $(selected_id).css({
                            'border-color': '#ced4da',
                        });
                        $(selected_error_id).html("");

                        if (selected_name === "*field name" ){
                            this.validation_error.isFieldNameStatus = true;
                        }else if(selected_name === "*field label"){
                            this.validation_error.isFieldLabelStatus = true;
                        }
                        // else if(selected_name === "*field ID"){
                        //     this.validation_error.isFieldIDStatus = true;
                        // }else if(selected_name === "*field class"){
                        //     this.validation_error.isFieldClassStatus = true;
                        // }
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" is required")

                    if (selected_name === "*field name" ){
                        this.validation_error.isFieldNameStatus = false;
                    } else if(selected_name === "*field label"){
                        this.validation_error.isFieldLabelStatus = false;
                    }
                    // else if(selected_name === "*field ID"){
                    //     this.validation_error.isFieldIDStatus = false;
                    // }else if(selected_name === "*field class"){
                    //     this.validation_error.isFieldClassStatus = false;
                    // }
                }
            },
            checkAndValidateFieldType()
            {
                if (!this.quizFormFieldDetails.f_type) {
                    $('#quizFormFieldType').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldTypeError').html("*field is required");
                    this.validation_error.isFieldTypeStatus = false;

                } else{
                    $('#quizFormFieldType').css({
                        'border-color': '#ced4da',
                    });
                    $('#fieldTypeError').html("");
                    this.validation_error.isFieldTypeStatus = true;
                }
            },
            validateAndSubmit()
            {
                // console.log(this.validation_error);
                if (!this.quizFormFieldDetails.f_label){
                    $('#quizFormFieldLabel').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldLabelError').html("*field label is required");
                }
                if (!this.quizFormFieldDetails.f_name){

                    $('#quizFormFieldName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldNameError').html("*field name is required");
                }

                if (!this.quizFormFieldDetails.f_type){

                    $('#quizFormFieldType').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldTypeError').html("*field type is required");
                }

                if (this.validation_error.isFieldLabelStatus === true &&
                    this.validation_error.isFieldNameStatus === true &&
                    this.validation_error.isFieldDefaultValue === true &&
                    this.validation_error.isFieldTypeStatus === true){
                    this.quizFormFieldUpdate();
                }
            },
            showServerError(errors)
            {
                $('#fieldLabelError').html("");
                $('#fieldNameError').html("");
                // $('#fieldIDError').html("");
                // $('#fieldClassError').html("");
                $('#fieldOptionDefaultValue').html("");
                $('#fieldTypeError').html("");

                $('#quizFormFieldLabel').css({'border-color': '#ced4da'});
                $('#quizFormFieldName').css({'border-color': '#ced4da'});
                // $('#quizFormFieldID').css({'border-color': '#ced4da'});
                // $('#quizFormFieldClass').css({'border-color': '#ced4da'});
                $('#quizFormFieldOptionDefaultValue').css({'border-color': '#ced4da'});
                $('#quizFormFieldType').css({'border-color': '#ced4da'});
                errors.forEach(val=>{
                    if (val.includes("f label") === true){

                        $('#fieldLabelError').html(val)
                        $('#quizFormFieldLabel').css({'border-color': '#FF7B88'});
                    }else if(val.includes("f name") === true){
                        $('#fieldNameError').html(val)
                        $('#quizFormFieldName').css({'border-color': '#FF7B88'});

                    }
                    // else if(val.includes("f id") === true){
                    //     $('#fieldIDError').html(val)
                    //     $('#quizFormFieldID').css({'border-color': '#FF7B88'});
                    //
                    // }else if(val.includes("f class") === true){
                    //     $('#fieldClassError').html(val)
                    //     $('#quizFormFieldClass').css({'border-color': '#FF7B88'});
                    //
                    // }
                    else if(val.includes("f default value") === true){
                        $('#fieldOptionDefaultValue').html(val)
                        $('#quizFormFieldOptionDefaultValue').css({'border-color': '#FF7B88'})
                    }
                    else if(val.includes("f type") === true){
                        $('#fieldTypeError').html(val)
                        $('#quizFormFieldType').css({'border-color': '#FF7B88'});
                    }

                })
            },

            quizFormFieldUpdate()
            {
                let _that = this;
                let quizFormFieldID = _that.selectedId;

                axios.put('quiz-form-fields/update',
                    {
                        id                      : quizFormFieldID,
                        quiz_form_id            :_that.quizFormFieldDetails.quiz_form_id,
                        f_label                 :_that.quizFormFieldDetails.f_label,
                        f_name                  :_that.quizFormFieldDetails.f_name,
                        f_id                    :_that.quizFormFieldDetails.f_id,
                        f_class                 :_that.quizFormFieldDetails.f_class,
                        f_type                  :_that.quizFormFieldDetails.f_type,
                        f_option_value          :_that.quizFormFieldDetails.f_option_value,
                        f_default_value         :_that.quizFormFieldDetails.f_default_value,
                        f_max_value             :_that.quizFormFieldDetails.f_max_value,
                        f_sort_order            :_that.quizFormFieldDetails.f_sort_order,
                        f_required              :_that.quizFormFieldDetails.f_required,
                    },
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code == 200)
                    {
                        _that.error_message             = '';
                        _that.quizFormFieldDetails      = '',
                        _that.success_message  = "Quiz form field updated successfully!";
                        _that.$emit('quiz-form-field-slide-close', _that.success_message);
                    }else if(response.data.status_code === 400){

                        _that.success_message           = "";
                        _that.error_message             = "";
                        _that.showServerError(response.data.errors);

                    }else{
                        _that.success_message           = "";
                        _that.error_message             = response.data.message;
                    }
                }).catch(function (error) {
                    console.log(error);
                });

            },

            getQuizFormFieldDetails()
            {
                let _that               = this;
                let quizFormFieldID     = _that.selectedId;
                axios.get("quiz-form-fields/"+quizFormFieldID,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            _that.quizFormFieldDetails  = response.data.quiz_form_field_info;
                        } else {
                            _that.success_message       = "";
                            _that.error_message         = response.data.error;
                        }
                    })
            },
        },
        created()
        {
            this.selectedId = this.quizFormFieldId;
            this.getQuizFormFieldDetails();
        }
    }
</script>

<style scoped>

</style>
