<template>
    <div class="right-sidebar-content-wrapper position-relative overflow-hidden" v-if="isAddCheck">
        <div class="right-sidebar-content-area px-2">
            <div class="form-wrapper">
                <h2 class="section-title text-uppercase mb-20">Add New Article</h2>

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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="enTitle">Title <span class="required">*</span></label>
                            <input hidden class="form-control" type="text" v-model="articleData.id" id="art_id">
                            <input placeholder="Enter title here" class="form-control" type="text" v-model="articleData.en_title" id="enTitle" @keyup="checkAndChangeValidation(articleData.en_title, '#enTitle', '#enTitleError', '*title')" required>
                            <span id="enTitleError" class="text-danger small"></span>
                        </div>
                    </div>

                    <div class="col-md-6" v-if="selected_language=='bangla'">
                        <div class="form-group">
                            <label>Bangla Title </label>
                            <input placeholder="Enter bangla title here" class="form-control" type="text" v-model="articleData.bn_title">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="categoryID">Category <span class="required">*</span></label>

                            <select class="form-control" v-model="selectedCategory" id="categoryID" @change="checkAndValidateSelectType()">
                                <option value="" disabled>Select A Category</option>
                                <option v-for="a_category in categoryList" :value="a_category.id" :key="a_category.id">
                                    {{a_category.name}}
                                </option>
                            </select>
                            <span id="categoryIDError" class="text-danger small"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="tag" class="d-block">Tag</label>
                            <!--                            <input class="form-control" type="text" v-model="articleData.tag" id="tag">-->
                            <tag-input id="tag" class="tag-input-wrapper" @tag-list="collectArticleList"/>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-15">
                            <label>English Short Summary</label>
                            <textarea placeholder="type here"  cols="30" rows="4" class="form-control" v-model="articleData.en_short_summary"></textarea>
                        </div>
                    </div>

                    <div class="col-md-12" v-if="selected_language=='bangla'">
                        <div class="form-group mb-15">
                            <label for="bn_short_summary">Bangla Short Summary</label>
                            <textarea placeholder="type here" cols="30" rows="4" class="form-control " v-model="articleData.bn_short_summary" id="bn_short_summary"></textarea>
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="text-left">
                            <button @click.prevent class="btn common-gradient-btn ripple-btn px-15 p-2 bg-primary" data-toggle="modal" data-target="#contentModal">
                                <i class="fa fa-plus text-white"></i> Add Content
                            </button>
                        </div>
                    </div>

                    <div class="col-md-12" v-if="contentList">
                        <ul class="mb-0">
                            <li v-for="(a_content,index) in contentList" :key="a_content.id" class="content-list d-flex justify-content-between align-items-center px-10 py-1">
                                <span class="text-black font-12" v-if="a_content.id">Block {{ index+1 }}</span>
                                <div class="action-buttons">
                                    <i @click="getContentDetails(a_content.id), unSelectAll()" data-toggle="modal" data-target="#contentModalEdit" class="fa fa-edit d-inline-block text-black font-12"></i>
                                    <i @click="deleteContent(a_content.id)"  class="fa fa-trash d-inline-block text-red font-12"></i>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Thumbnail Image</label>
                            <input type="file" id="files" ref="files" @change="onThumbnailFileChange" accept="image/*">
                             <p><small class="text-danger ">Recommended (800 x 400)px</small></p>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="thumbnail_url">
                        <div class="form-group" >
                            <img class="preview" style="height:150px; width: auto" :src="thumbnail_url"/>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label  class="d-block">Banner Image</label>
                            <input type="file" id="files" ref="files" @change="onBannerFileChange" accept="image/*">
                             <p><small class="text-danger ">Recommended (1800 x 400)px</small></p>
                        </div>
                    </div>
                    <div class="col-md-12" v-if="banner_url">
                        <div class="form-group" >
                            <img class="preview" style="height:150px; width: auto" :src="banner_url"/>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-15">
                            <label class="form-label" >Additional Files</label>
                            <input type="file"  id="files" ref="files" multiple @change="fileUploadChange"  >
                        </div>
                    </div>

                    <div class="col-md-12 mb-15" v-if="article_files.length >0">
                        <div class="card">
                            <div class="card-header bg-light-2 px-10 py-1">List of Files</div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-10 py-1"
                                    v-for="(file, index) in article_files"
                                    :key="index"
                                >
                                    <a :href="file.url" class="font-12 text-body d-flex justify-content-between align-items-center">
                                        {{ file.name }}
                                        <span class="close-btn" @click="deleteUploadedFile(index)">x</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Commentable</label>
                            <div>
                                <label for="cmmnt_active"><input id="cmmnt_active" type="radio" value="1" v-model="articleData.commentable"/> Active</label>
                                <label for="cmmnt_in_active"><input id="cmmnt_in_active" type="radio" value="0" v-model="articleData.commentable"/> Inactive</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Is Notifiable?</label>
                            <div>
                                <label for="is_notifiable"><input id="is_notifiable" type="radio" value="1" v-model="articleData.is_notifiable"/> Yes</label>
                                <label for="is_notifiable"><input id="is_notifiable" type="radio" value="0" v-model="articleData.is_notifiable"/> No</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Select A Status</label>
                            <select class="form-control" v-model="articleData.status">
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

                <div class="form-group text-right">
                    <button :disabled="isNotifyUser==true ? true : false" class="btn common-gradient-btn ripple-btn px-50" @click="validateAndSubmit()">Add</button>
                </div>
            </div>

        </div>

        <div class="modal fade" id="contentModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contentModalLabel">Add New Content</h5>
                    </div>
                    <div class="modal-body" style="max-height: 450px;overflow-y: auto;">
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
<!--                            <ul class="list-unstyled permission-list m-0 p-0">-->
<!--                                <li class="text-left pb-2">-->

<!--                                    <label class="mb-0 ml-2">-->
<!--                                        <input type="checkbox" v-model="role_id" value="0"> All-->
<!--                                    </label>-->
<!--                                </li>-->

<!--                                <li v-for="a_user in user_roles" :key="a_user.id" class="text-left pb-2">-->
<!--                                    <label v-if="a_user.id==1" class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id.checked" :value="a_user.id"  checked disabled> {{ a_user.name }} </label>-->
<!--                                    <label v-else class="pl-2 mb-0"><input class="check-role" type="checkbox" v-model="role_id" :value="a_user.id"> {{ a_user.name }} </label>-->
<!--                                </li>-->
<!--                            </ul>-->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="closeModal_2" class="btn btn-danger rounded btn-md m-1 px-15 py-1 text-white" data-dismiss="modal" @click="unSelectAll()">Close</button>
                        <button type="button" class="btn btn-primary rounded btn-md m-1 px-15 py-1 text-white" @click="addContentData()">Add</button>
                    </div>
                </div>
            </div>
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
    import $ from "jquery";





    export default {
        name: "articleAdd.vue",
        props: ['isAddCheck'],
        components: {
            Summernote,
            SummernoteEdit,
            TagInput,
            CustomRoleAdd,
            CustomRoleEdit
            // Select2
        },
        data() {
            return {
                isNotifyUser            : false,
                isMounted               : false,
                checked                 : true,
                enBody                  : "en_Body",
                enBodyEdit              : "en_Body_edit",
                bnBodyEdit              : "bn_Body_edit",
                bnBody                  : "bn_Body",
                selected_language       : 'english',
                success_message         : '',
                thumbnail_url           : '',
                banner_url           : '',
                thumbnail_file          : '',
                banner_file          : '',
                error_message           : '',
                token                   : '',
                categoryList            : '',
                selectedCategory        : '',
                userInfo                : '',
                user_roles              : '',
                contentList             : '',
                enBodyData              : '',
                bnBodyData              : '',
                aContent                : '',
                roleAccess              : [],

                articleData : {
                    id                  : '',
                    en_title            : '',
                    bn_title            : '',
                    tag                 : '',
                    en_short_summary    : '',
                    bn_short_summary    : '',
                    en_body             : '',
                    bn_body             : '',
                    commentable         : 1,
                    status              : 'draft',
                    is_notifiable       :0,
                },
                contentData :{
                    id                  : '',
                    article_id          : '',
                    en_body             : '',
                    bn_body             : '',

                },
                role_id                 : [],
                userInformation         : '',
                validation_error : {
                    isTitleStatus       : false,
                    isCategoryStatus    : false,
                },
                selected_checkbox       : 1,
                bangla_checkbox         : '',
                fileUrl                 : [],
                article_files           : [],
                url                     : '',
                video_files             : ''
            }
        },
        methods: {
            getPermissionGrantedAccess(status)
            {
                this.roleAccess = status.split(',');

                // this.getUserRoles();
            },
            deleteContent(content_id)
            {
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
            clearAll()
            {
                let _that = this;
                _that.isMounted  = false;
                _that.enBodyData = '';
                _that.roleAccess = [];

                $('#roleIdError').html("");
                $('#roleIdError_2').html("");
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
                        if (response.data.status_code === 200){
                            _that.getCategoryList(_that.aContent.article_id);
                            _that.isMounted = false;
                            $('#closeModal_3').click();
                            $('#roleIdError_2').html("");
                            _that.unSelectAll();
                        }
                        else if (response.data.status_code === 400){
                            console.log(response.data.errors);
                            _that.showServerError(response.data.errors)
                        }
                    })
            },
            onThumbnailFileChange(e)
            {
                this.thumbnail_file = e.target.files[0];
                this.thumbnail_url = URL.createObjectURL(this.thumbnail_file);
            },
            onBannerFileChange(e)
            {
                this.banner_file = e.target.files[0];
                this.banner_url = URL.createObjectURL(this.banner_file);
            },
            getContentDetails(content_id)
            {

                let _that = this;

                axios.get('contents/'+content_id,{
                    headers: {
                        'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                    },
                }).then(function (response) {
                    // console.log(response.data)
                    if (response.data.status_code == 200){
                        _that.isMounted         = true;
                        _that.aContent          = response.data.content_info;
                        _that.enBodyData        = _that.aContent.en_body;
                        _that.bnBodyData        = _that.aContent.bn_body;
                        if ((_that.aContent.role_id).includes(',')) {
                            _that.roleAccess    = (_that.aContent.role_id).split(',');
                            _that.roleAccess    = _that.roleAccess.map(i=>Number(i))
                        }else{
                            _that.roleAccess.push(Number(_that.aContent.role_id));
                        }

                    }

                })

            },

            getContentList(articleID)
            {
                let _that = this;
                // console.log(articleID);

                axios.get('contents-article/'+articleID,{
                    headers: {
                        'Authorization'     : 'Bearer ' + localStorage.getItem('authToken')
                    },
                }).then(function (response) {
                    // console.log(response.data.content_list[0].en_body)
                    _that.contentList = response.data.content_list;
                })
            },
            addContentData()
            {
                let _that = this;
                let collection;
                let enBody      = $('#en_Body').val();
                let bnBody      = $('#bn_Body').val();
                _that.contentData.id = (Math.round((new Date()).getTime()*10));
                _that.contentData.en_body = enBody;
                _that.contentData.bn_body = typeof (bnBody) != 'undefined'? bnBody : '';
                // if (_that.role_id.length ==0){
                //     _that.role_id.push(1);
                // }
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
                        console.log(response.data);
                        $('#closeModal_2').click();
                        _that.contentData.id = '';
                        _that.contentData.en_body = '';
                        _that.contentData.bn_body = '';
                        _that.getContentList(_that.contentData.article_id);
                        _that.success_message = "Content added successfully";
                        _that.setTimeoutElements();
                        _that.unSelectAll();
                    }

                    else if (response.data.status_code === 400){
                        console.log(response.data.errors);
                        _that.showServerError(response.data.errors)
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            unSelectAll()
            {
                let _that = this;
                $('.note-editable').html('');
                $('#roleIdError').html("");
                $('#roleIdError_2').html("");
                _that.roleAccess = [];
                _that.user_roles = '';
                _that.getUserRoles()
            },
            setTimeoutElements()
            {
                // setTimeout(() => this.isLoading = false, 3e3);
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
            },
            deleteUploadedFile(index)
            {
                document.getElementById('files').value= "";
                (this.article_files).splice(index, 1);
            },
            fileUploadChange(e)
            {
                let _that = this;
                const selectedFiles = e.target.files;

                for(var j=0; j<selectedFiles.length; j++){
                    console.log(selectedFiles[j]);
                    _that.article_files.push(selectedFiles[j]);
                }

            },
            collectArticleList(tagList)
            {
                this.articleData.tag = tagList.join();
            },
            changeCheckBox()
            {
                if (this.bangla_checkbox === true)
                    this.selected_language = 'bangla';
                else
                    this.selected_language = 'english';
            },
            checkAndValidateSelectType()
            {
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
            checkAndChangeValidation(selected_data, selected_id, selected_error_id, selected_name)
            {

                if (selected_data.length >0) {
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
            validateAndSubmit()
            {

                if (!this.articleData.en_title){
                    $('#enTitle').css({
                        'border-color': '#FF7B88',
                    });
                    $('#enTitleError').html("title field is required");
                }

                if (!this.selectedCategory){
                    $('#categoryID').css({
                        'border-color': '#FF7B88',
                    });
                    $('#categoryIDError').html("category field is required");
                }

                if (this.validation_error.isTitleStatus    === true &&
                    this.validation_error.isCategoryStatus === true ){
                    this.articleAdd();
                }
            },
            showServerError(errors)
            {
                $('#enTitleError').html("");
                $('#categoryIDError').html("");
                $('#roleIdError').html("");
                $('#roleIdError_2').html("");

                $('#enTitle').css({'border-color': '#ced4da'});
                $('#categoryID').css({'border-color': '#ced4da'});

                errors.forEach(val=>{
                    console.log(val);
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
            articleAdd()
            {
                if (this.articleData.is_notifiable ==1){
                    this.isNotifyUser = true;
                }

                let _that       = this;
                let formData    = new FormData();

                for( var i = 0; i < this.article_files.length; i++ ){
                    let file = this.article_files[i];

                    formData.append('uploaded_file[' + i + ']', file);
                }

                formData.append('id',this.articleData.id);
                formData.append('category_id', this.selectedCategory);
                formData.append('en_title', this.articleData.en_title);
                formData.append('bn_title', this.articleData.bn_title);
                formData.append('tag', this.articleData.tag);
                formData.append('en_short_summary', this.articleData.en_short_summary);
                formData.append('bn_short_summary', this.articleData.bn_short_summary);
                formData.append('commentable_status', this.articleData.commentable);
                formData.append('status', this.articleData.status);
                formData.append('is_notifiable', this.articleData.is_notifiable);
                formData.append('thumbnail_img', this.thumbnail_file);
                formData.append('banner_img', this.banner_file);


                axios.post('articles', formData,
                    {
                        headers: {
                            'Content-Type'  : 'multipart/form-data',
                            'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {

                    if (response.data.status_code === 201) {
                        _that.isNotifyUser          = false;
                        _that.articleData           = '';
                        _that.selectedCategory      = '';
                        _that.error_message         = '';
                        _that.success_message       = "Article Added Successfully";
                        _that.$emit('article-slide-close', _that.success_message);
                    }else if(response.data.status_code === 400){
                        _that.success_message       = "";
                        _that.error_message         = "";
                        _that.showServerError(response.data.errors);

                    }else{
                        _that.success_message       = "";
                        _that.error_message         = response.data.message;
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            getCategoryList()
            {
                let _that =this;

                axios.get('categories',
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        },
                        params :
                            {
                                isAdmin : 1,
                                isRole : _that.userInformation.roles[0].id
                            },

                    })
                    .then(function (response) {
                        if(response.data.status_code === 200){
                            // console.log(response.data);
                            _that.categoryList = response.data.category_list;
                        }
                        else{
                            _that.success_message = "";
                            _that.error_message   = response.data.error;
                        }
                    })
            },
            myChangeEvent(val)
            {
                let _that = this;
                _that.article_id = val;
            },
            mySelectEvent({id, text})
            {
                console.log({id, text})
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
            }

        },
        created()
        {
            this.userInformation = JSON.parse(localStorage.getItem("userInformation"));
            if (this.userInformation){
                this.articleData.id = (Math.round((new Date()).getTime()*10));
                this.contentData.article_id = (Math.round((new Date()).getTime()*10));
                this.getCategoryList();
                this.getUserRoles();
                this.getContentList(this.articleData.id);
                this.$emit('article-id', this.articleData.id);
            }
        }
    }
</script>

<style scoped>
    .font-12 {
        font-size: 12px;
    }

    .close-btn {
        cursor: pointer;
        background: #ff7b88;
        color: #ffffff;
        width: 18px;
        height: 18px;
        text-align: center;
        line-height: 16px;
        border-radius: 50%;
        text-indent: 1px;
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
