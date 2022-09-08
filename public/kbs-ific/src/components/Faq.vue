<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9 mx-auto">
                <div class="text-center jumbotron bg-primary mt-5">
                    <h2 class="display-4 text-white">FAQ</h2>
                    <p class="text-white">Frequently Asked Questions</p>
                </div>
                <el-collapse v-model="activeName" accordion>
                    <el-collapse-item :name="item.id" v-for="item in faqList" :key="item.id">
                        <template #title>
                            {{item.en_title}}
                        </template>
                        <div v-for="content in item.contents" :key="content.id" v-html="content.en_body">

                        </div>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </div>
    </div>
</template>

<script setup>
    import {ApiCallMaker} from "../api/ApiCallMaker";
    import {defineComponent} from "vue";
    import {ref, computed, onBeforeUpdate, onMounted} from "vue";
    const activeName = ref('1');
    const faqList = ref([]);

    onMounted(()=>{
         getArticleList();
    });

    async function getArticleList() {
        const response = await ApiCallMaker('GET', 'faq-list', '', '', '');
        if (response && response.data.status_code == 200) {
            faqList.value = response.data.faq_list;
        }
    }
</script>

<style lang="scss">
.el-collapse {
    --el-collapse-border-color: var(--el-border-color-lighter);
    --el-collapse-header-height: 48px;
    --el-collapse-header-bg-color: transparent;
    --el-collapse-header-text-color: var(--el-text-color-primary);
    --el-collapse-header-font-size: 1.2rem;
    --el-collapse-content-bg-color: transparent;
    --el-collapse-content-font-size: 1rem;
    --el-collapse-content-text-color: var(--el-text-color-primary);
    border-top: 1px solid var(--el-collapse-border-color);
    border-bottom: 1px solid var(--el-collapse-border-color);
}

.el-collapse-item__arrow {
    color: #00793f;
}
.el-collapse-item__header {
    background-color: darken(#e7f0ec, 5%);
    padding: 0 0.5rem;
    border-radius: 0.2rem;
}
</style>
