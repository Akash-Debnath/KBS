<template>
  <div>
    <div class="search-input-wrapper mt-40">
      <div class="input-group">
        <input style="text-align: center" type="text" class="form-control" v-on:keyup.enter="fromData.search ? searchData() : ''" v-model="fromData.search" v-on:keyup="autoSuggetion" placeholder="Search Article Here" aria-label="Search Here" aria-describedby="searchBtn">


        <div class="input-group-append">
          <button class="btn btn-outline-secondary" id="searchBtn" type="button" @click="fromData.search ? searchData() : ''">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z"/>
              <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
            </svg>
          </button>
        </div>
      </div>
    </div>

    <div v-if="suggestedArtiles.length" class="search-suggestion">
      <ul>

        <li v-for="a_suggestion in suggestedArtiles" :key="a_suggestion.id">
          <router-link :to="{ name: 'ArticleDetail', params: { articleID: a_suggestion.id,articleSlug: a_suggestion.slug }}">
            {{a_suggestion.en_title.length == 50 ? a_suggestion.en_title : (a_suggestion.en_title).substring(0,50)+"..."}}
          </router-link>
        </li>

      </ul>
    </div>
  </div>
</template>

<script>

  import $ from 'jquery'
  import axios from 'axios'

  $(document).on('focus','.search-input-wrapper input', function(){
    $(this).parent().addClass('focused');
  });

  $(document).on('blur','.search-input-wrapper input', function () {
    let inputValue = $(this).val();
    if ( inputValue == "" ) {
      $(this).removeClass('filled');
      $(this).parents().removeClass('focused');
    } else {
      $(this).addClass('filled');
    }
  });
  export default {
    name: "Search",

    data(){
      return{
        fromData: {
          search:''
        },
        suggestedArtiles:[],
        userInformation : ''
      }
    },

    methods:{
      searchData(){
        let _that = this;
        // console.log(_that.fromData.search);
        if (localStorage.query_string){
          localStorage.setItem('query_string','');
          localStorage.setItem('query_string',_that.fromData.search);
        }else{
          localStorage.setItem('query_string',_that.fromData.search);
        }
        _that.$router.push({ name: 'Search'});
      },

      autoSuggetion(e) {
        let _that = this;
        _that.suggestedArtiles = [];
        if(e.target.value.length>3){
          setTimeout(()=>{
            if (this.userInformation ==''){
              axios.get('article/search/'+e.target.value)
              .then(function (res) {
                _that.suggestedArtiles = res.data.article_list.data;
                console.log(_that.suggestedArtiles);
              })
            } else{
              axios.get('article/search/'+e.target.value,{
                params : {
                  isRole :  _that.userInformation.roles[0].id
                }
              }).then(function (res) {
                _that.suggestedArtiles = res.data.article_list.data;
                console.log(_that.suggestedArtiles);
              })
            }

          },500);
        }

      }
    },
    created() {
      if (sessionStorage.userInformation) {
        this.userInformation = JSON.parse(sessionStorage.userInformation);
      }
    }
  }
</script>

<style scoped>

</style>
