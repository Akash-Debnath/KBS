<template>
    <div class="menu-wrapper bg-white mt-30">
        <h3 class="menu-title mb-20 p-15">Tags</h3>
        <ul class="list-inline list-unstyled px-15 pb-15 tag-list-wrapper">
            <li class="d-inline-block" v-for="a_tag in tagList" :key="a_tag"><a href="#" @click.prevent="search=a_tag,searchData()">{{a_tag}}</a></li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "TagView",

        props: ['selectedCategory'],

        data(){
            return{
                articleList : '',
                tagList : [],
                search : ''
            }
        },
        methods:{
            searchData(){
                let _that = this;
                console.log(_that.search);
                if (localStorage.query_string){
                    localStorage.setItem('query_string','');
                    localStorage.setItem('query_string',_that.search);
                }else{
                    localStorage.setItem('query_string',_that.search);
                }
                _that.$router.push({ name: 'Search'});
            },
        },

        created() {
            this.articleList = this.selectedCategory;
            this.articleList.forEach(val => {
                if (val.tag.includes(',')){
                    let res = val.tag.split(',');
                     res.forEach(single =>{
                         this.tagList.push(single)
                     })
                }else{
                    this.tagList.push(val.tag);
                }
            })
            this.tagList = this.tagList.filter((v, i, a) => a.indexOf(v) === i);
        }
    }
</script>

<style scoped>

</style>