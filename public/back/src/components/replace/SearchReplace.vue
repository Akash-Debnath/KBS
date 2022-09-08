<template>
    <div v-if="post_id">
        <div>
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Search" id="text-search" v-model="search_word" @keyup="getHighlightWord()">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="replace" v-model="replace_word">
            </div>
<!--            <button type="button" class="btn btn-primary text-white" v-if="!isSearch" @click="searchArticleData()">Search</button>-->
            <button type="button" class="btn btn-primary text-white" data-dismiss="modal" @click="replaceArticleData()">Replace</button>
        </div>

<!--        <div class="action-modal-wraper" v-if="success_message">-->
<!--            <span>{{ success_message }}</span>-->
<!--        </div>-->
<!--        <div class="action-modal-wraper-error" v-if="error_message">-->
<!--            <span>{{ error_message }}</span>-->
<!--        </div>-->
    </div>
</template>

<script>
    import $ from 'jquery'
    import axios from "axios";


    export default {
        name: "SearchReplace",

        props : ['post_id','post_slug'],

        data(){
            return{
                isSearch : false,
                search_word : '',
                replace_word : '',
                success_message: '',
                error_message : '',
                aArticle:'',
                articleID :  '',
                articleSlug :  '',
            }
        },
        methods:{
            getHighlightWord(){
              // console.log(this.search_word);
              this.$emit('keyword-search', this.search_word);
            },
            setTimeoutElements()
            {
                setTimeout(() => this.success_message = "", 2e3);
                setTimeout(() => this.error_message = "", 2e3);
            },

            searchArticleData(){
                let _that = this;


                if (_that.search_word !=''){
                    axios.get('replace/'+_that.post_id+'/'+_that.search_word,
                        {
                            headers: {
                                'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                            }
                        }).then(function (response) {
                        if (response.data.status_code==200 && response.data.article != 'Not found!') {
                            console.log(response)
                            _that.success_message = 'Search found!';
                            _that.isSearch = !_that.isSearch;
                            _that.$emit('replace', _that.search_word);
                            _that.setTimeoutElements();
                            // $( "div:contains(sear)" ).css( "text-decoration", "underline" );
                        }
                        else {
                            // _that.error_message = 'No data found!'
                            _that.search_word = '';
                            _that.replace_word = '';
                            _that.error_message = 'Not found!';
                            _that.$emit('replace',_that.error_message);
                            _that.setTimeoutElements();
                        }
                    })
                }


            },
            replaceArticleData(){
                let _that = this;

                _that.isSearch = !_that.isSearch;

                if (this.search_word){
                    if (this.replace_word){
                        axios.post('replace/'+_that.post_id+'/'+_that.search_word,
                            {
                                'replace_word' : _that.replace_word
                            },
                            {
                                headers: {
                                    'Authorization' : 'Bearer '+localStorage.getItem('authToken')
                                }
                            }).then(function (response) {
                            if (response.data.status_code == 200 && response.data.article != 'Not found!'){
                                console.log(response.data)
                                _that.search_word = '';
                                _that.replace_word = '';
                                _that.success_message = 'word replaced!';
                                _that.articleSearch(_that.articleSlug)
                                _that.$emit('replace',_that.success_message, _that.aArticle);
                            }else{
                                _that.search_word = '';
                                _that.replace_word = '';
                                _that.error_message = 'Not found!';
                                _that.$emit('replace',_that.error_message);
                            }
                        })
                    }else {
                        _that.search_word = '';
                        _that.replace_word = '';
                        _that.$emit('replace','replace field empty!');
                    }
                } else{
                    _that.search_word = '';
                    _that.replace_word = '';
                    _that.$emit('replace','search field empty!');
                }




            },

            articleSearch(v){
                let _that = this;
                console.log(v);
                let articleSlug = v;
                axios.get('article-details/'+this.post_slug)
                    .then(function (response) {
                        _that.aArticle = response.data.article_info;
                        console.log(_that.aArticle);
                        // location.reload()
                    })
            },

        },
        created() {
            this.articleID = this.post_id;
            this.articleSlug = this.post_slug;
        }
    }
</script>

<style scoped>
    /*.highlight {*/
    /*    background-color: #fff34d;*/
    /*    -moz-border-radius: 5px; !* FF1+ *!*/
    /*    -webkit-border-radius: 5px; !* Saf3-4 *!*/
    /*    border-radius: 5px; !* Opera 10.5, IE 9, Saf5, Chrome *!*/
    /*    -moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); !* FF3.5+ *!*/
    /*    -webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); !* Saf3.0+, Chrome *!*/
    /*    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.7); !* Opera 10.5+, IE 9.0 *!*/
    /*}*/

    /*.highlight {*/
    /*    padding:1px 4px;*/
    /*    margin:0 -4px;*/
    /*}*/
</style>