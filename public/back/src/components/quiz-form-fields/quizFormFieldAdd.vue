<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isAddFieldCheck">
        <div class="right-sidebar-content-area px-2">

            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Quiz Field in <span class="text-info">{{quizform_details.name}}</span></h2>

                <div class="add-quiz-item-wrapper" style="background: #f1f1f1; padding: 10px;border-radius: 6px;margin-bottom: 10px;">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quizFormID">Quiz Form ID <span class="required">*</span></label>
                                <input id="quizFormID" type="text" readonly v-model="selectedFormID" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quizFormName">Quiz Form Name <span class="required">*</span></label>
                                <input id="quizFormName" type="text" readonly v-model="quizform_details.name" class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="quizFormFieldLabel">Field Label <span class="required">*</span></label>
                                <input id="quizFormFieldLabel" type="text" v-model="quizFormFieldData.quizlabelName" class="form-control" placeholder="Enter Label Name" @keyup="checkAndChangeValidation(quizFormFieldData.quizlabelName, '#quizFormFieldLabel', '#fieldLabelError', '*field label')">
                                <span id="fieldLabelError" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldOptionValue">Field Option Value </label>
                                <input id="quizFormFieldOptionValue" type="text" v-model="quizFormFieldData.quizfieldOptionValue"  class="form-control" placeholder="Enter Field Option Value">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldOptionDefaultValue">Field Answer <span class="required">*</span></label>
                                <input id="quizFormFieldOptionDefaultValue" type="text" v-model="quizFormFieldData.quizfieldDefaultValue"  class="form-control" placeholder="Enter Field Option Default Value" @keyup="checkQuizDefaultValue(quizFormFieldData.quizfieldDefaultValue)">
                                <span id="fieldOptionDefaultValue" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldName">Field Name <span class="required">*</span></label>
                                <input id="quizFormFieldName" type="text" v-model="quizFormFieldData.quizfieldName" class="form-control" placeholder="Enter Field Name" @keyup="giveIdClass(quizFormFieldData.quizfieldName),checkAndChangeValidation(quizFormFieldData.quizfieldName, '#quizFormFieldName', '#fieldNameError', '*field name')" @change="giveIdClass(quizFormFieldData.quizfieldName)">
                                <span id="fieldNameError" class="text-danger small"></span>
                            </div>
                        </div>

<!--                        <div class="col-md-12" v-if="isAutoData==false">-->
<!--                            <button type="button" @click="toggleIDClassField"><i class="fa fa-plus"></i></button>-->
<!--                        </div>-->

<!--                        <div class="col-md-12" v-if="isAutoData==true">-->
<!--                            <button type="button" @click="toggleIDClassField"><i class="fa fa-minus"></i></button>-->
<!--                        </div>-->

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldID">Field ID</label>
<!--                                @keyup="checkAndChangeValidation(quizFormFieldData.quizfieldID, '#quizFormFieldID', '#fieldIDError', '*field ID')"-->
                                <input id="quizFormFieldID" type="text" v-model="quizFormFieldData.quizfieldID"  class="form-control" placeholder="Enter Field ID">
                                <span id="fieldIDError" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldClass">Field Class</label>
<!--                                @keyup="checkAndChangeValidation(quizFormFieldData.quizfieldClass, '#quizFormFieldClass', '#fieldClassError', '*field class')"-->
                                <input id="quizFormFieldClass" type="text" v-model="quizFormFieldData.quizfieldClass"  class="form-control" placeholder="Enter Field Class">
                                <span id="fieldClassError" class="text-danger small"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldType">Field Type <span class="required">*</span></label>
                                <select id="quizFormFieldType" class="form-control" v-model="quizFormFieldData.quizfieldType" @change="checkAndValidateFieldType()">
                                    <option value="" selected disabled>--Select A Type--</option>
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

<!--                        <div class="col-md-4">-->
<!--                            <div class="form-group">-->
<!--                                <label for="quizFormFieldOptionValue">Field Option Value </label>-->
<!--                                <input id="quizFormFieldOptionValue" type="text" v-model="quizFormFieldData.quizfieldOptionValue"  class="form-control" placeholder="Enter Field Option Value">-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="col-md-4">-->
<!--                            <div class="form-group">-->
<!--                                <label for="quizFormFieldOptionDefaultValue">Question Answer <span class="required">*</span></label>-->
<!--                                <input id="quizFormFieldOptionDefaultValue" type="text" v-model="quizFormFieldData.quizfieldDefaultValue"  class="form-control" placeholder="Enter Field Option Default Value">-->
<!--                            </div>-->
<!--                        </div>-->


                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldMaxValue">Field Max Value</label>
                                <input id="quizFormFieldMaxValue" type="number" v-model="quizFormFieldData.quizfieldMaxValue"  class="form-control" placeholder="Enter Max Number">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldSortValue">Field Sort Value</label>
                                <input id="quizFormFieldSortValue" type="number" v-model="quizFormFieldData.quizfieldSortValue"  class="form-control" placeholder="Enter Sort Number">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quizFormFieldRequired">Field Required <span class="required">*</span></label>


                                <select id="quizFormFieldRequired" class="form-control" v-model="quizFormFieldData.quizfieldRequired">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                               <button class="btn common-gradient-btn ripple-btn px-50" @click=" isNext= true, validateAndNext()">Save & Add More</button>
                               <button class="btn common-gradient-btn ripple-btn px-50 float-right" @click=" isNext=false ,validateAndSubmit()">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="action-modal-wraper" v-if="success_message">
            <span>{{ success_message }}</span>
        </div>
        <div class="action-modal-wraper-error" v-if="error_message">
            <span>{{ error_message }}</span>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import $ from "jquery";

    export default {
        name: "quizFormFieldAdd.vue",
        props: ['isAddFieldCheck','quizFormId'],
        components: {

        },
        data() {
            return {
                isSearch                : false,
                isAddMore               : false,
                isNext                  : false,
                // isAutoData              : false,

                success_message         : '',
                error_message           : '',
                token                   : '',
                quizform_details        :'',
                selectedFormID          : '',

                isFormFieldList         : false,

                quizFormFieldData : {

                    quizformfieldID     :  '',
                    quizlabelName       :  '',
                    quizfieldName       :  '',
                    quizfieldID         :  '',
                    quizfieldClass      :  '',
                    quizfieldType       :  '',
                    quizfieldOptionValue:  '',
                    quizfieldDefaultValue: '',
                    quizfieldMaxValue   :  '',
                    quizfieldSortValue  :  '',
                    quizfieldRequired   :  1,
                },

                validation_error:{
                    isFieldLabelStatus      : false,
                    isFieldNameStatus       : false,
                    // isFieldIDStatus         : false,
                    // isFieldClassStatus      : false,
                    isFieldDefaultValue : false,
                    isFieldTypeStatus       : false,
                },
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
            // toggleIDClassField(){
            //     this.isAutoData = !this.isAutoData
            // },
            giveIdClass(val){
                console.log(val);
                val = val.replace(/[\W_]/g, "_");
                val = val.replace(" ","_");
                this.quizFormFieldData.quizfieldName = val;
                this.quizFormFieldData.quizfieldID = val;
                this.quizFormFieldData.quizfieldClass = val;
            },

            setTimeoutElements()
            {
                // setTimeout(() => this.isLoading = false, 3e3);
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
            },
            validateAndNext (){
                if (!this.quizFormFieldData.quizlabelName){
                    $('#quizFormFieldLabel').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldLabelError').html("*field label is required");
                }
                if (!this.quizFormFieldData.quizfieldName){

                    $('#quizFormFieldName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldNameError').html("*field name is required");
                }
                if (!this.quizFormFieldData.quizfieldDefaultValue){
                    $('#quizFormFieldOptionDefaultValue').css({
                        'border-color': '#FF7B88',
                    });

                    $('#fieldOptionDefaultValue').html("*field name is required");
                }

                // if (!this.quizFormFieldData.quizfieldID){
                //
                //     $('#quizFormFieldID').css({
                //         'border-color': '#FF7B88',
                //     });
                //     $('#fieldIDError').html("*field ID is required");
                // }
                //
                // if (!this.quizFormFieldData.quizfieldClass){
                //
                //     $('#quizFormFieldClass').css({
                //         'border-color': '#FF7B88',
                //     });
                //     $('#fieldClassError').html("*field class is required");
                // }
                if (!this.quizFormFieldData.quizfieldType){

                    $('#quizFormFieldType').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldTypeError').html("*field type is required");
                }
                // if (this.validation_error.isFieldLabelStatus === true &&
                //     this.validation_error.isFieldNameStatus === true &&
                //     this.validation_error.isFieldIDStatus === true &&
                //     this.validation_error.isFieldClassStatus === true &&
                //     this.validation_error.isFieldTypeStatus === true){
                //     this.quizformfieldStore();
                //     // store to an array
                // }
                if (this.validation_error.isFieldLabelStatus === true &&
                    this.validation_error.isFieldNameStatus === true &&
                    this.validation_error.isFieldDefaultValue === true &&
                    this.validation_error.isFieldTypeStatus === true){
                    this.quizformfieldStore();
                    // store to an array
                }
            },
            validateAndSubmit()
            {
                if (!this.quizFormFieldData.quizlabelName){
                    $('#quizFormFieldLabel').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldLabelError').html("*field label is required");
                }
                if (!this.quizFormFieldData.quizfieldName){

                    $('#quizFormFieldName').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldNameError').html("*field name is required");
                }

                // if (!this.quizFormFieldData.quizfieldID){
                //
                //     $('#quizFormFieldID').css({
                //         'border-color': '#FF7B88',
                //     });
                //     $('#fieldIDError').html("*field ID is required");
                // }
                //
                // if (!this.quizFormFieldData.quizfieldClass){
                //
                //     $('#quizFormFieldClass').css({
                //         'border-color': '#FF7B88',
                //     });
                //     $('#fieldClassError').html("*field class is required");
                // }
                if (!this.quizFormFieldData.quizfieldDefaultValue){
                    $('#quizFormFieldOptionDefaultValue').css({
                        'border-color': '#FF7B88',
                    });

                    $('#fieldOptionDefaultValue').html("*field name is required");
                }
                if (!this.quizFormFieldData.quizfieldType){

                    $('#quizFormFieldType').css({
                        'border-color': '#FF7B88',
                    });
                    $('#fieldTypeError').html("*field type is required");
                }
                // if (this.validation_error.isFieldLabelStatus === true &&
                //     this.validation_error.isFieldNameStatus === true &&
                //     this.validation_error.isFieldIDStatus === true &&
                //     this.validation_error.isFieldClassStatus === true &&
                //     this.validation_error.isFieldTypeStatus === true){
                //     this.quizformfieldStore();
                // }
                if (this.validation_error.isFieldLabelStatus === true &&
                    this.validation_error.isFieldNameStatus === true &&
                    this.validation_error.isFieldDefaultValue === true &&
                    this.validation_error.isFieldTypeStatus === true){
                    this.quizformfieldStore();
                }
            },


            checkAndValidateFieldType()
            {
                if (!this.quizFormFieldData.quizfieldType) {
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
                    $(selected_error_id).html(selected_name+" field is required")

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
                    // }
                    // else if(val.includes("f class") === true){
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

            quizformfieldStore()
            {
                let _that = this;
                axios.post('quiz-form-fields',
                    {
                        quiz_form_id            : _that.selectedFormID,
                        f_label                 : _that.quizFormFieldData.quizlabelName,
                        f_name                  : _that.quizFormFieldData.quizfieldName,
                        f_id                    : _that.quizFormFieldData.quizfieldID,
                        f_class                 : _that.quizFormFieldData.quizfieldClass,
                        f_type                  : _that.quizFormFieldData.quizfieldType,
                        f_option_value          : _that.quizFormFieldData.quizfieldOptionValue,
                        f_default_value         : _that.quizFormFieldData.quizfieldDefaultValue,
                        f_max_value             : _that.quizFormFieldData.quizfieldMaxValue,
                        f_sort_order            : _that.quizFormFieldData.quizfieldSortValue,
                        f_required              : _that.quizFormFieldData.quizfieldRequired,
                    },
                    {
                        headers: {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {

                    if (response.data.status_code === 201) {

                        _that.error_message         = '';
                        _that.success_message       = "Quiz form field added successfully";
                        // _that.quizFormFieldData.     = '';

                        _that.quizFormFieldData.quizformfieldID         =  '',
                        _that.quizFormFieldData.quizlabelName           =  '',
                        _that.quizFormFieldData.quizfieldName           =  '',
                        _that.quizFormFieldData.quizfieldID             =  '',
                        _that.quizFormFieldData.quizfieldClass          =  '',
                        _that.quizFormFieldData.quizfieldType           =  '',
                        _that.quizFormFieldData.quizfieldOptionValue    =  '',
                        _that.quizFormFieldData.quizfieldDefaultValue   = '',
                        _that.quizFormFieldData.quizfieldMaxValue       =  '',
                        _that.quizFormFieldData.quizfieldSortValue      =  '',
                        _that.quizFormFieldData.quizfieldRequired       =  1,

                        _that.validation_error.visFieldLabelStatus      = false;
                        _that.validation_error.isFieldNameStatus        = false;
                        // _that.validation_error.isFieldIDStatus          = false;
                        // _that.validation_error.isFieldClassStatus       = false;
                        _that.validation_error.isFieldTypeStatus        = false;

                        if (_that.isNext == true){
                            _that.success_message = "Quiz form field added and add more"
                            _that.setTimeoutElements();
                        }else{
                            _that.$emit('quiz-form-field-slide-close', _that.success_message);
                        }

                        // _that.$emit('quiz-form-field-slide-close', _that.success_message);

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
            quizformDetails()
            {
                let _that       = this;
                let quizformID  = _that.selectedFormID;

                axios.get("quiz-forms/"+quizformID,
                    {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('authToken')
                        },
                    })
                    .then(function (response) {
                        if (response.data.status_code === 200) {
                            _that.quizform_details= response.data.quiz_form_info
                        } else {
                            _that.success_message       = "";
                            _that.error_message         = response.data.error;
                        }
                    })
            },
        },
        created()
        {
            this.selectedFormID = this.quizFormId;
            this.quizformDetails();
        }
    }
</script>

<style scoped>

</style>
