<template>
    <div class="tag-input" @click="inputFocus($event)">
        <div v-for="(tag, index) in tags" :key="index" class="tag-group">
            <span class="tag tag-primary" @dblclick="updateTag(tag,index)">{{tag}}</span>
            <span class="tag tag-delete tag-secondary" @click="removeTag(index)">X</span>
        </div>
        <input v-if="isExceed" type="text" class="tag-control form-control" placeholder="Add a new tag and press enter to add!" v-model="tagValue" @keyup="error_message = ''" @blur="tagValue = ''" @input="autoWidth($event)"
        @keyup.enter="createTag($event)">
        <span class="text-danger" v-if="error_message">{{error_message}}</span>
    </div>
</template>

<script>

export default {
    name: "TagComponent",

    data(){
        return{
            isExceed : true,
            tagValue : '',
            tagList : [],
            tagSeparator: ' ',
            error_message: '',
        }
    },
    computed: {
        tags:{
            get(){
                return this.tagList
            },
            set(val){
                this.tags = val
            }
        }
    },
    methods: {
        inputFocus(e){
            if (!e.target.querySelector('input.tag-control')) return;
            e.target.querySelector('input.tag-control').focus();
        },
        autoWidth(e){
            const tagControlHidden = document.createElement('div')
            tagControlHidden.classList.add('tag-control','tag-control-hidden')

            const tagString = this.tagValue || e.target.getAttribute('placeholder') || ''
            tagControlHidden.innerHTML = tagString.replace(/ /g, '&nbsp;').trim()
            document.body.appendChild(tagControlHidden);

            e.target.style.setProperty('width', Math.ceil(window.getComputedStyle(tagControlHidden).width
                .replace('px','')) + 1 + "px")
            tagControlHidden.remove();
        },

        createTag(e)
        {
            this.tagValue = this.tagValue.replace(/[^\w\s]/gi, '');
            this.tagValue = this.tagValue.replace(/[0-9]/g, '');
            this.tagValue = this.tagValue.replace(" ", '');
            // console.log(this.tagValue)
            if (this.tagValue.length > 0 && this.tagList.includes(this.tagValue)!=true ){
                if (this.tagValue.includes(this.tagSeparator) || e.key === 'Enter'){
                    this.tagList.push(this.tagValue.replace(this.tagSeparator, '').trim())
                    this.tagValue = "";
                    if (this.tagList.length > 10){
                        this.isExceed = false;
                    }
                }
                this.autoWidth(e);
                this.$emit('tag-list', this.tagList);
            }else{
                this.error_message = "*tag can not be empty or tag already exist";
            }
        },
        removeTag(i){
            this.tags.splice(i,1)
            if (this.tagList.length <= 10){
                this.isExceed = true;
            }
            this.$emit('tag-list', this.tagList);
        },
        updateTag(tag,index){
            this.tagValue = tag;
            this.removeTag(index)
        }
    },

}
</script>

<style scoped>

</style>
