<template>
    <div class="main-wrapper d-flex">
        <!-- sidebar -->
        <Menu></Menu>
        <!-- sidebar end -->

        <div class="main-content-wrapper w-100 position-relative overflow-auto bg-white" >
            <!-- Topbar -->
            <Header></Header>

            <!-- Topbar End -->

            <!-- Content Area -->
            <div class="content-area">
                <div class="content-title-wrapper px-15 py-10">
                    <h2 class="content-title text-uppercase m-0">Page Configuration</h2>
                </div>

                <div class="content-wrapper bg-white">
                    <!-- list top area -->
                    <div class="list-top-area px-15 py-10 d-sm-flex justify-content-between align-items-center">
                        <div class="adding-btn-area d-md-flex align-items-center">
                            <div class="d-flex align-items-center">

<!--                                <button class="btn common-gradient-btn ripple-btn new-agent-session right-side-common-form mx-10 m-w-140 px-15 mb-10 mb-md-0" @click="isAddCheck=true">
                                    <i class="fas fa-plus"></i>
                                    Add Category
                                </button>-->

                            </div>

                        </div>

                    </div>
                    <!-- list top area end -->


                    <!-- Content Area -->
                    <div class="data-content-area px-15 py-10">
                        <!-- Table Data -->

                        <div class="right-sidebar-content-wrapper position-relative" >
                            <div class="right-sidebar-content-area px-2">

                                <div class="form-wrapper">
                                    <h2 class="section-title text-uppercase mb-20">Config Front Page</h2>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div v-if="success_message" class="alert alert-success" role="alert">
                                                {{ success_message }}
                                            </div>
                                            <div v-if="error_message" class="alert alert-danger" role="alert">
                                                {{ error_message }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Title <span class="required">*</span></label>
                                                <input class="form-control" type="text" v-model="configure_data.title" id="name" placeholder="Enter Title" required>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="d-block">Logo</label>
                                                <input type="file" id="files"  ref="files" @change="onLogoFileChange" >
                                            </div>
                                        </div>

                                        <div class="col-md-12" v-if="logo_url">
                                            <div class="form-group" >
                                                <img class="preview" style="height:250px; width: auto" :src="logo_url"/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Select A Position</label>

                                                <select class="form-control" v-model="configure_data.position">
                                                    <option value="left">Left</option>
                                                    <option value="right">Right</option>
                                                    <option value="center">Center</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name" class="d-block">Banner</label>
                                                <input type="file" id="banner_files"  ref="banner_files"  @change="onBannerFileChange"  >
                                            </div>
                                        </div>

                                        <div class="col-md-12" v-if="banner_url">
                                            <div class="form-group" >
                                                <img class="preview" style="height:250px; width: auto" :src="banner_url"/>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">Description</label>
                                                <textarea cols="20" rows="4" class="form-control form-control-lg" v-model="configure_data.description"></textarea>
                                            </div>
                                        </div>

                                    </div>

                                    <div>
                                        <div class="form-group text-left">
                                            <button class="btn common-gradient-btn ripple-btn px-50"  @click="savePageConfiguration">Add</button>
                                        </div>
                                    </div>


                                </div>

                            </div>
                        </div>
                        <!-- Common Right SideBar -->
                    </div>
                    <!-- Content Area End -->
                </div>
            </div>
            <!-- Content Area End -->
        </div>
    </div>
</template>

<script>

import axios from 'axios'
import Menu from '../../layouts/common/Menu.vue'
import Header from '../../layouts/common/Header.vue'

export default {
    name: "pageConfiguration",

    components: {
        Header,
        Menu,
    },

    data() {
        return {
            isEditCheck  : false,
            isAddCheck   : false,

            success_message : '',
            error_message   : '',
            token : '',

            pageConfigInfo : '',

            logo_file      : '',
            banner_file    : '',

            logo_url      : '',
            banner_url    : '',

            configure_data : {
                id          : '',
                title       : '',
                position    : 'left',
                description : '',
            },

        }
    },

    methods: {

        onLogoFileChange(e) {
            this.logo_file = e.target.files[0];
            this.logo_url = URL.createObjectURL(this.logo_file);
        },

        onBannerFileChange(e) {
            this.banner_file = e.target.files[0];
            this.banner_url = URL.createObjectURL(this.banner_file);
        },

        getPageConfiguration(pageUrl) {

            let _that =this;

            pageUrl = pageUrl == undefined ? 'pages' : pageUrl;

            axios.get(pageUrl,
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

                        console.log(response.data);

                        if ( !(response.data.page_config_info)) {

                            _that.isEdit =  false;
                            _that.isAdd  =  true;

                        }
                        else{
                            _that.isEdit =  true;
                            _that.isAdd  =  false;
                            _that.pageConfigInfo = response.data.page_config_info;
                            _that.logo_url                   =  _that.pageConfigInfo.logo;
                            _that.banner_url                 =  _that.pageConfigInfo.banner;
                            _that.configure_data.id          =  _that.pageConfigInfo.id;
                            _that.configure_data.title       =  _that.pageConfigInfo.title;
                            _that.configure_data.position    =  _that.pageConfigInfo.position;
                            _that.configure_data.description =  _that.pageConfigInfo.description;
                        }

                    } else{

                        _that.success_message = "";
                        _that.error_message   = response.data.error;

                    }
                });

        },

        savePageConfiguration() {

            let _that = this;

            if (this.isAdd === true) {

                let formData = new FormData();

                formData.append('logo', this.logo_file);
                formData.append('banner', this.banner_file);

                formData.append('title', this.configure_data.title);
                formData.append('position', this.configure_data.position);
                formData.append('description', this.configure_data.description);

                axios.post('pages', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200) {

                        _that.isAdd = false;
                        _that.error_message    = '';
                        _that.success_message  = "Page Configuration Saved Successfully";
                        _that.setTimeoutElements();

                    } else {
                        _that.success_message = "";
                        _that.error_message   = response.data.error;
                    }

                }).catch(function (error) {
                    console.log(error);
                });
            }

            if (this.isEdit === true) {

                let formData = new FormData();

                formData.append('logo', this.logo_file);
                formData.append('banner', this.banner_file);
                formData.append('id', this.configure_data.id);
                formData.append('title', this.configure_data.title);
                formData.append('position', this.configure_data.position);
                formData.append('description', this.configure_data.description);

                axios.post('pages/update-data', formData,
                    {
                        headers: {
                            'Authorization': 'Bearer '+localStorage.getItem('authToken')
                        }
                    }).then(function (response) {
                    console.log(response)
                    if (response.data.status_code === 200) {

                        _that.getPageConfiguration();

                        _that.error_message    = '';
                        _that.logoFiles        = '';
                        _that.success_message  = "Page Configuration Updated Successfully";
                        _that.setTimeoutElements();

                    } else {

                        _that.success_message = "";
                        _that.error_message   = response.data.error;

                    }

                }).catch(function (error) {
                    console.log(error);
                });
            }



        },

        setTimeoutElements() {

            setTimeout(() => this.success_message = "", 3000);
            setTimeout(() => this.error_message = "", 3000);
        },

    },

    created() {

        this.getPageConfiguration();

    }

}
</script>

<style scoped>

</style>
