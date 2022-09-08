<template>
    <div class="step-form-wrapper">
        <div v-if="step === 1">
            <h1 class="section-title text-uppercase mb-20">Title Page</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="d-inline-block pr-15">
                        <input type="checkbox" id="checkbox1"  v-model="selected_checkbox" checked disabled>
                        <label for="checkbox1" class="ml-2">English</label>
                    </div>

                    <div class="d-inline-block">
                        <input type="checkbox" id="checkbox2" v-model="bangla_checkbox" @change="changeCheckBox()">
                        <label for="checkbox2" class="ml-2">Bangla</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="enTitle">Enter Title<span class="required">*</span></label>
                        <input placeholder="Enter a title" class="form-control" type="text" v-model="faqData.en_title" id="enTitle" required>
                        <span id="enTitleError" class="text-danger small"></span>
                    </div>
                </div>

                <div class="col-md-12" v-if="selected_language==='bangla'">
                    <div class="form-group">
                        <label>Bangla Title </label>
                        <input placeholder="Enter a title" class="form-control" type="text" v-model="faqData.bn_title" >
                    </div>
                </div>
            </div>
            <button class="btn common-gradient-btn ripple-btn px-50" @click="checkAndChangeValidation(faqData.en_title, '#enTitle', '#enTitleError', '*title')">Next <i class="fas fa-chevron-right"></i></button>
        </div>


        <div v-if="step === 2">
            <h1 class="section-title text-uppercase mb-20">Enter Article</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Article <span class="required">*</span></label>
                        <select2 id="articleIDError" v-model="article_value" :options="article_options" :settings="{ settingOption: article_value, settingOption: article_value, settingOption: article_value }" @change="myChangeEvent($event)" @select="mySelectEvent($event)"/>
                        <span id="articleError" class="text-danger small"></span>
                    </div>
                </div>
            </div>
            <button class="btn common-gradient-btn ripple-btn px-50" @click.prevent="checkArticleData()">Next <i class="fas fa-chevron-right"></i></button>
        </div>

        <div v-if="step === 3">
            <h1 class="section-title text-uppercase mb-20">Add Tag</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="tag" class="d-block">Tag <small>(Press enter to add a new tag.)</small></label>
                        <tag-input id="tag" class="tag-input-wrapper" @tag-list="collectTagList"/>
                    </div>
                </div>
            </div>
            <button class="btn common-gradient-btn ripple-btn px-50" @click.prevent="next()">Next <i class="fas fa-chevron-right"></i></button>
        </div>

        <div v-if="step === 4">
            <h1 class="section-title text-uppercase mb-20">Add Content</h1>
            <div class="row">
                <div class="col-md-12">
<!--                    <div class="text-left">-->
<!--                        <button @click.prevent class="btn common-gradient-btn ripple-btn px-15 p-2 bg-primary" data-toggle="modal" data-target="#contentModal">-->
<!--                            <i class="fa fa-plus text-white"></i> Add Content-->
<!--                        </button>-->
<!--                    </div>-->
                    <div class="d-inline-block">
                        <input type="checkbox" id="checkbox4" v-model="bangla_checkbox" @change="changeCheckBox()">
                        <label for="checkbox4" class="ml-2">Bangla</label>
                    </div>
                    <div class="form-group">
                        <label>English Body</label>
                        <input hidden class="form-control" type="text" v-model="contentData.article_id">
                        <Summernote  v-model="contentData.en_body" :idFromParent="enBody" ></Summernote>
                    </div>

                    <div class="form-group mb-2" v-if="selected_language=='bangla'">
                        <label >Bangla Body</label>
                        <Summernote  v-model="contentData.en_body" :idFromParent="bnBody"></Summernote>
                    </div>

                    <div class="form-group mb-2">
                        <label>Roles <span class="required">*</span><span id="roleIdError" class="text-danger small"></span></label>

                        <CustomRoleAdd v-if="user_roles" :userRoles="user_roles" @granted-roles="getPermissionGrantedAccess"/>

<!--                        <ul class="list-unstyled permission-list m-0 p-0">-->
<!--                            <li class="text-left pb-2">-->

<!--                                <label class="mb-0 ml-2">-->
<!--                                    <input type="checkbox" v-model="role_id" value="0"> All-->
<!--                                </label>-->
<!--                            </li>-->
<!--                            <li v-for="a_user in user_roles" :key="a_user.id" class="text-left pb-2">-->
<!--                                <label  v-if="a_user.id==1" class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id.checked" :value="a_user.id"  checked disabled> {{ a_user.name }} </label>-->
<!--                                <label v-else class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id" :value="a_user.id"> {{ a_user.name }} </label>-->
<!--                            </li>-->
<!--                        </ul>-->
                    </div>

<!--                    <button type="button" id="closeModal_2" class="btn btn-danger rounded btn-md m-1 px-15 py-1 text-white" data-dismiss="modal" @click="unSelectAll()">Close</button>-->
<!--                    <button type="button" class="btn btn-primary rounded btn-md m-1 px-15 py-1 text-white" @click="addContentData()">Add</button>-->


                </div>

                <div class="col-md-12">
                    <ul v-if="contentList" class="mb-10">
                        <li v-for="(a_content,index) in contentList" :key="a_content.id" class="content-list d-flex justify-content-between align-items-center px-10 py-1">
                            <span class="text-black font-12" v-if="a_content.id">Block {{ index+1 }}</span>
                            <div class="action-buttons">
                                <i @click="getContentDetails(a_content.id), unSelectAll()" data-toggle="modal" data-target="#contentModalEdit" class="fa fa-edit d-inline-block text-black font-12"></i>
                                <i @click="deleteContent(a_content.id)"  class="fa fa-trash d-inline-block text-red font-12"></i>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <button class="btn common-gradient-btn ripple-btn px-50" @click.prevent="addContentData()">Add More <i class="fa fa-plus"></i></button>
            <button class="btn common-gradient-btn ripple-btn px-50" @click.prevent="isNextStep = true,addContentData()">Next <i class="fas fa-chevron-right"></i></button>
        </div>

        <div v-if="step === 5">
            <h1 class="section-title text-uppercase mb-20">Commentable Status</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Commentable</label>
                        <div>
                            <label for="cmmnt_active"><input id="cmmnt_active" type="radio" value="1" v-model="faqData.commentable"/> Active</label>
                            <label for="cmmnt_in_active"><input id="cmmnt_in_active" type="radio" value="0" v-model="faqData.commentable"/> Inactive</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Is Notifiable?</label>
                        <div>
                            <label for="cmmnt_active"><input id="cmmnt_active" type="radio" value="1" v-model="faqData.is_notifiable"/> Yes</label>
                            <label for="cmmnt_in_active"><input id="cmmnt_in_active" type="radio" value="0" v-model="faqData.is_notifiable"/>  No</label>
                        </div>
                    </div>
                </div>
                
            </div>
            <button class="btn common-gradient-btn ripple-btn px-50" @click.prevent="next()">Next <i class="fas fa-chevron-right"></i></button>
        </div>

        <div v-if="step === 6">
            <h1 class="section-title text-uppercase mb-20">Status</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Select A Status</label>
                        <select class="form-control" v-model="faqData.status">
                            <option value="draft">Draft</option>
                            <option value="hide">Hide</option>
                            <option value="private">Private</option>
                            <option value="public">Public</option>
                            <!--                                <option value="scheduling">Scheduling</option>-->
                            <!--                                <option value="announcement">Announcement</option>-->
                        </select>
                    </div>
                </div>
            </div>
            <button :disabled="isNotifyUser==true ? true : false" class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()"><i class="far fa-save"></i> Save</button>
        </div>


        <div class="modal fade" id="contentModalEdit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contentModalLabel_2">Edit New Content</h5>
                    </div>
                    <div class="modal-body">
                        <div class="d-inline-block">
                            <input type="checkbox" id="checkbox3" v-model="bangla_checkbox" @change="changeCheckBox()">
                            <label for="checkbox3" class="ml-2">Bangla</label>
                        </div>

                        <div class="form-group">
                            <label>English Body</label>
                            <input hidden class="form-control" type="text" v-model="aContent.id">
                            <SummernoteEdit v-if="isMounted"   :idFromParent="enBodyEdit" :dataFromParent="enBodyData"></SummernoteEdit>
                        </div>

                        <div class="form-group mb-15" v-if="selected_language=='bangla'">
                            <label>Bangla Body</label>
                            <SummernoteEdit v-if="isMounted"  :idFromParent="bnBodyEdit" :dataFromParent="bnBodyData"></SummernoteEdit>
                        </div>

                        <div class="form-group mb-0">
                            <label>Roles <span class="required">*</span><span id="roleIdError_2" class="text-danger small"></span></label>

                            <CustomRoleEdit v-if="user_roles && aContent" :userRoles="user_roles" :banneRoleAccess="aContent.role_id" @granted-roles="getPermissionGrantedAccess"/>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="closeModal_3" class="btn btn-danger rounded btn-md m-1 px-15 py-1 text-white" data-dismiss="modal" @click="clearAll()">Close</button>
                        <button type="button" class="btn btn-primary rounded btn-md m-1 px-15 py-1 text-white" @click="updateContentData(aContent.id)">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'

    import Summernote from "@/components/summer-note/summernote";
    import SummernoteEdit from "@/components/summer-note/summernote-edit";
    import TagInput from "../tag/TagComponent";
    import CustomRoleAdd from '../custom-role/CustomRoleAdd'
    import CustomRoleEdit from '../custom-role/CustomRoleEdit'
    import Select2 from 'v-select2-component';
    // import select2 from './../select2/select2';
    import $ from "jquery";

    export default {
        props: ['isAddCheck'],
        components: {
            Summernote,
            SummernoteEdit,
            TagInput,
            Select2,
            CustomRoleAdd,
            CustomRoleEdit
        },
        data() {
            return {
                step:1,
                isNotifyUser            : false,
                isNextStep : false,
                isToogleModal       : false,
                isStep :false,
                isMounted           : false,
                checked             : true,
                enBody              : "en_Body",
                bnBody              : "bn_Body",
                selected_language   : 'english',

                article_value: '',
                article_options: [],
                article_id:'',


                success_message     : '',
                error_messages      : '',
                token               : '',
                categoryList        : '',
                user_roles          : '',
                contentList         : '',
                enBodyData          : '',
                bnBodyData          : '',
                enBodyEdit          : "en_Body_edit",
                bnBodyEdit          : "bn_Body_edit",
                aContent            : '',
                roleAccess          : [],
                selectedCategory    : '',
                userInfo            : '',
                isSummerNoteError   : false,

                selected_checkbox    : 1,
                bangla_checkbox      : '',
                contentData :{
                    id                  : '',
                    article_id          : '',
                    en_body             : '',
                    bn_body             : '',

                },
                role_id                 : [],

                faqData      : {
                    id              : '',
                    en_title        : '',
                    bn_title        : '',
                    tag             : '',
                    en_body         : '',
                    bn_body         : '',
                    commentable     : 1,
                    is_notifiable   : 0,
                    status          : 'draft'
                },

                validation_error : {
                    isTitleStatus    : false,
                    isCategoryStatus : false,
                    isArticleSelect : false,
                },
            }
        },

        methods:{
            getPermissionGrantedAccess(status)
            {
                this.roleAccess = status.split(',');

                // this.getUserRoles();
            },
            prev() {
                this.step--;
            },
            next() {
                this.step++;
            },
            submit() {
                alert('Submit to blah and show blah and etc.');
            },
            checkArticleData(){
                console.log(this.article_value)
                if (this.article_value == ''){
                    $('#articleIDError').css({
                        'border-color': '#FF7B88',
                    });
                    $('#articleError').html("*article is required");
                    this.validation_error.isArticleSelect = false;
                }else{
                    $('#articleIDError').css({
                        'border-color': '#ced4da',
                    });
                    $('#articleError').html("");
                    this.validation_error.isArticleSelect = true;
                    this.next();
                }
            },

            deleteContent(content_id){
                let _that = this;

                axios.delete('contents/delete',{
                    data    : {
                        id                  : content_id
                    },
                    headers : {
                        'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                    },
                }).then(function (response) {

                    console.log(response);
                    _that.getContentList(_that.contentData.article_id);

                })
            },
            clearAll(){
                let _that = this;
                _that.isMounted  = false;
                _that.enBodyData = '';
                _that.roleAccess = [];
            },
            updateContentData(content_id)
            {

                let _that = this;
                let collection;
                let enBody      = $('#en_Body_edit').val();
                let bnBody      = $('#bn_Body_edit').val();
                _that.aContent.en_body = enBody;
                _that.aContent.bn_body = typeof (bnBody) != 'undefined'? bnBody : '';
                collection = _that.roleAccess.join();

                if (!collection) {
                    collection = 1;
                }

                axios.put('contents/update',
                    {
                        id      : _that.aContent.id,
                        article_id : _that.aContent.article_id,
                        en_body : _that.aContent.en_body,
                        bn_body : _that.aContent.bn_body,
                        role_id : collection
                    },{
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    // console.log(response);
                    if (response.data.status_code === 200){
                        _that.getCategoryList(_that.aContent.article_id);
                        _that.isMounted = false;
                        _that.unSelectAll();
                        $('#closeModal_3').click();
                        $('#roleIdError_2').html("");
                    }
                    else if (response.data.status_code === 400){
                        _that.showServerError(response.data.errors)
                    }
                })
            },
            getContentDetails(content_id){

                let _that = this;

                axios.get('contents/'+content_id,{
                    headers: {
                        'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                    },
                }).then(function (response) {
                    console.log(response);
                    _that.isMounted = true;
                    // _that.isContentStatus = true;
                    _that.aContent = response.data.content_info;
                    _that.enBodyData  = _that.aContent.en_body;
                    _that.bnBodyData  = _that.aContent.bn_body;
                    console.log(_that.bnBodyData);
                    if ((_that.aContent.role_id).includes(',')) {
                        _that.roleAccess = (_that.aContent.role_id).split(',');
                        _that.roleAccess = _that.roleAccess.map(i=>Number(i))
                        console.log(_that.roleAccess);
                    }else{
                        _that.roleAccess.push(_that.aContent.role_id);
                    }
                })

            },
            getContentList(articleID){
                let _that = this;
                // console.log(articleID);

                axios.get('contents-article/'+articleID,{
                    headers: {
                        'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                    },
                }).then(function (response) {
                    console.log(response.data)
                    _that.contentList = response.data.content_list;
                })
            },
            addContentData(){
                let _that = this;
                let collection;
                let enBody      = $('#en_Body').val() ? $('#en_Body').val() : 'n/a';
                let bnBody      = $('#bn_Body').val();
                _that.contentData.id = (Math.round((new Date()).getTime()*10));
                _that.contentData.en_body = enBody;
                _that.contentData.bn_body = typeof (bnBody) != 'undefined'? bnBody : '';

                if (_that.roleAccess.length == 0){
                    _that.roleAccess.push(1);
                }

                collection = _that.roleAccess.join();

                let formData = new FormData();
                formData.append('id', _that.contentData.id);
                formData.append('article_id', _that.contentData.article_id);
                formData.append('en_body', _that.contentData.en_body);
                formData.append('bn_body', _that.contentData.bn_body);
                formData.append('role_id', collection);
                axios.post('contents',formData,
                    {
                        headers: {
                            'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code === 200){

                        $('#closeModal_2').click();
                        _that.contentData.id = '';
                        _that.contentData.en_body = '';
                        _that.contentData.bn_body = '';
                        _that.getContentList(_that.contentData.article_id);
                        _that.success_message = "Content added successfully";
                        _that.setTimeoutElements();
                        _that.unSelectAll();
                        if (_that.isNextStep===true){
                            _that.next();
                        }
                    }

                    else if (response.data.status_code === 400){
                        console.log(response.data.errors);
                        _that.showServerError(response.data.errors)
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },

            unSelectAll(){
                let _that = this;
                $('.note-editable').html('');
                _that.roleAccess = [];
                _that.user_roles = '';
                _that.getUserRoles()
                // this.role_id = [];
                // let items=document.querySelectorAll('.check-role');
                // for(let i=0; i<items.length; i++){
                //     if(items[i].type=='checkbox'){
                //         items[i].checked=false;
                //     }
                //
                // }
            },
            setTimeoutElements()
            {
                // setTimeout(() => this.isLoading = false, 3e3);
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
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

                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },
            getAllArticleList()
            {
                let _that = this;
                axios.get('all-article-list', {
                    headers: {
                        'Authorization': 'Bearer '+localStorage.getItem('authToken')
                    },
                    params : {
                        isAdmin             : 1,
                        isRole              : _that.userInformation.roles[0].id
                    },}).then(function (response) {
                    console.log(response.data.article_list)
                    response.data.article_list.forEach(val => {
                        _that.article_options.push({
                            'id' : val.id,
                            'text' : (val.en_title).length < 100 ? val.en_title : (val.en_title).substring(0,100)+'..',
                            'category' : val.category ?  val.category.id : ''
                        })
                    })
                })
            },

            myChangeEvent(val){
                console.log(val)
                let _that = this;
                _that.article_id = val;
            },
            mySelectEvent({id, text, category}){
                console.log({id, text, category})
                this.selectedCategory = category;
                console.log(this.selectedCategory);
            },

            collectTagList(tagList){
                this.faqData.tag = tagList.join();
            },

            changeCheckBox() {
                if (this.bangla_checkbox === true)
                    this.selected_language = 'bangla';
                else
                    this.selected_language = 'english';
            },

            checkAndValidateSelectType(){

                if (!this.selectedCategory) {
                    $('#categoryID').css({
                        'border-color': '#FF7B88',
                    });
                    $('#categoryIDError').html("category field is required");
                    this.validation_error.isCategoryStatus = false;

                } else{
                    $('#categoryID').css({
                        'border-color': '#ced4da',
                    });
                    $('#categoryIDError').html("");
                    this.validation_error.isCategoryStatus = true;
                }
            },

            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name){

                if (selected_data.length >0){

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

                        if (selected_name === "*title" ){
                            this.validation_error.isTitleStatus = true;
                        }
                        this.next();
                        // this.isStep = true;
                    }

                } else{
                    $(selected_id).css({
                        'border-color': '#FF7B88',
                    });
                    $(selected_error_id).html(selected_name+" field is required")

                    if (selected_name === "title" ){
                        this.validation_error.isTitleStatus = false;
                    }
                }
            },

            validateAndSubmit(){
                console.log('here');
                this.faqAdd();
            },

            showServerError(errors)
            {

                $('#enTitleError').html("");
                $('#categoryIDError').html("");
                $('#enBodyError').html("");
                $('#roleIdError').html("");
                $('#roleIdError_2').html("");

                $('#enTitle').css({'border-color': '#ced4da'});
                $('#categoryID').css({'border-color': '#ced4da'});

                errors.forEach(val => {
                    if (val.includes("en title")==true){
                        $('#enTitleError').html(val)
                        $('#enTitle').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("category")==true){
                        $('#categoryIDError').html(val)
                        $('#categoryID').css({'border-color': '#FF7B88'});
                    }
                    else if (val.includes("role id")=== true){
                        $('#roleIdError').html(val)
                        $('#roleIdError_2').html(val)
                        $('#categoryID').css({'border-color': '#FF7B88'});
                    }
                })
            },

            faqAdd()
            {
                let _that           = this;

                if (this.faqData.is_notifiable == 1){
                    this.isNotifyUser = true;
                }

                axios.post('faqs', {

                        id                  : this.faqData.id,
                        article_id          : this.article_id,
                        category_id         : this.selectedCategory,
                        en_title            : this.faqData.en_title,
                        bn_title            : this.faqData.bn_title,
                        tag                 : this.faqData.tag,
                        commentable_status  : this.faqData.commentable,
                        is_notifiable       : this.faqData.is_notifiable,
                        status              : this.faqData.status,
                    },
                    {
                        headers : {
                            'Authorization'     : 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    if (response.data.status_code == 201) {
                        _that.isNotifyUser              = false;
                        _that.faqData                   = '';
                        _that.error_message             = '';
                        _that.success_message           = "FAQ Added Successfully";

                        _that.$emit('faq-slide-close', _that.success_message);
                        // localStorage.clear('articleID');
                    } else {
                        _that.success_message = "";
                        _that.error_messages   = response.data.errors;
                        _that.showServerError(response.data.errors);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },

            // getCategoryList()
            // {
            //
            //     let _that   = this;
            //
            //     axios.get('categories',
            //         {
            //             headers     : {
            //                 'Authorization'         : 'Bearer '+localStorage.getItem('authToken')
            //             },
            //             params      :
            //                 {
            //                     isAdmin             : 1,
            //                     without_pagination  : 1
            //                 },
            //
            //         })
            //         .then(function (response) {
            //             if(response.data.status_code === 200) {
            //                 _that.categoryList          = response.data.category_list;
            //             }
            //             else{
            //                 _that.success_message       = "";
            //                 _that.error_messages        = response.data.errors;
            //             }
            //         })
            // },
        },
        created() {
            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            if (this.userInformation != ''){
                this.isNextStep = false;
                this.faqData.id = (Math.round((new Date()).getTime()*10));
                this.contentData.article_id = (Math.round((new Date()).getTime()*10));
                // this.getCategoryList();
                this.getAllArticleList();
                this.getUserRoles();
                this.getContentList(this.faqData.id);
                this.$emit('faq-id', this.faqData.id);
                // console.log('from child'+this.faqData.id);
            }

        }
    }
</script>

<style scoped>
.font-12 {
    font-size: 12px;
}

#contentModal.modal {
    background: rgba(0,0,0,0.35);
}

#contentModalEdit.modal{
    background: rgba(0,0,0,0.35);
}

.content-list {
        background: #c5ecff;
        border-radius: 6px;
        color: #000;
        margin-bottom: 5px;
    transition: all 0.4s;
    }

.content-list:hover {
    background: #2e9ce0;
    color: #fff;
}

.action-buttons i {
    cursor: pointer;
}
</style>