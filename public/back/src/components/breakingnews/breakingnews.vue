<template>
    <div id="breakingNews" class="ticker box bg-light-blue mt-1">
        <article class="media d-flex align-items-center py-10 pl-15">
            <span class="breaking-news media-left">Breaking News</span>
            <div class="media-content">
                <transition name="fade" tag="div" mode="out-in">
                    <router-link class="news" :to="{ name: 'articleDetails', params: { id: articles[0].id,slug: articles[0].slug }}" v-if="news[0] && articles[0].slug" key="0"><span class="cat-title">{{articles[0].category ? articles[0].category.name : ''}} : </span> {{articles[0].en_title}}</router-link>
                    <router-link class="news" :to="{ name: 'articleDetails', params: { id: articles[1].id,slug: articles[1].slug }}" v-if="news[1] && articles[1].slug" key="1"><span class="cat-title">{{articles[1].category ? articles[1].category.name : ''}} : </span> {{articles[1].en_title}}</router-link>
                    <router-link class="news" :to="{ name: 'articleDetails', params: { id: articles[2].id,slug: articles[2].slug }}" v-if="news[2] && articles[2].slug" key="1"><span class="cat-title">{{articles[2].category ? articles[2].category.name : ''}} : </span> {{articles[2].en_title}}</router-link>
                    <router-link class="news" :to="{ name: 'articleDetails', params: { id: articles[3].id,slug: articles[3].slug }}" v-if="news[3] && articles[3].slug" key="1"><span class="cat-title">{{articles[3].category ? articles[3].category.name : ''}} : </span> {{articles[3].en_title}}</router-link>
                    <router-link class="news" :to="{ name: 'articleDetails', params: { id: articles[4].id,slug: articles[4].slug }}" v-if="news[4] && articles[4].slug" key="1"><span class="cat-title">{{articles[4].category ? articles[3].category.name : ''}} : </span> {{articles[4].en_title}}</router-link>
                </transition>
            </div>
        </article>
    </div>
</template>

<script>
export default {
    name: "breakingnews.vue",

    props: ['recent_article'],
    data() {
        return {
            tickerLocation: 0,
            news: [],
            articles : [],
        }
    },
    created: function() {
        this.articles = this.recent_article;

        for(let i =0 ; i< this.articles.length ; i++){
            if (i==0) {
                this.news.push(true);
            }
            else{
                this.news.push(false);
            }
        }

        // this.articles.foreach(val =>{
        //     this.news.push(val);
        // })
        setInterval(this.updateTicker, 5000);
        // console.log(this.articles)
        // console.log(this.news)
    },
    methods: {
        updateTicker: function() {
            var removed = this.news.pop();
            this.news.unshift(removed);
        }
    }
}
</script>

<style scoped>

.breaking-news {
    background-color: #ff7b88;
    color: #ffffff;
    border-radius: 0 50px 50px 0;
    padding: 5px 10px;
    min-width: 127px;
}

.media-content {
    padding: 5px;
    display: block;
    overflow: hidden;
}

.cat-title {
    color: #fff;
    font-weight: 500;
}

.news {
    color: #fff;
    display: block;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}

.fade-enter-active, .fade-leave-active {
    transition: opacity 1s;
}

.fade-enter, .fade-leave-to {
    opacity: 0
}
</style>