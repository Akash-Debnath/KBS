<template>
    <div>
        <!-- Check All -->
        <label><input class="check-role" type='checkbox' @click='checkAll()' v-model='isCheckAll'> Public</label>
        <!-- Checkboxes list -->
        <ul class="list-unstyled permission-list m-0 p-0" v-if="isCheckAll != true">
            <li class="text-left pb-2" v-for='lang in langsdata'>
                <label class="pl-2 mb-0" v-if="lang.id==1">
                    <input class="check-role" disabled type='checkbox' v-bind:value='lang.id' v-model='languages.checked' checked @change='updateCheckall()'> {{ lang.name }}
                </label>

                <label class="pl-2 mb-0" v-else>
                    <input class="check-role" type='checkbox' v-bind:value='lang.id' v-model='languages' @change='updateCheckall()'> {{ lang.name }}
                </label>
            </li>
        </ul>

<!--        <br>-->
<!--        Selected items : {{ selectedlang }}-->

    </div>
</template>

<script>
    import $ from 'jquery'
    export default {
        name: "CustomRoleAdd",

        props: ['userRoles'],


        data() {
            return{
                isCheckAll      : false,
                langsdata       : [],
                languages       : [],
                selectedlang    : ""
            }
        },
        methods: {
            checkAll: function()
            {
                this.isCheckAll     = !this.isCheckAll;
                this.languages      = [];
                if(this.isCheckAll){ // Check all
                    this.languages.push(0)
                    for (let key in this.langsdata) {
                        this.languages.push(this.langsdata[key].id);
                        this.selectedlang += this.languages[key]+",";
                    }
                }
                this.printValues()
                this.selectedlang   = this.selectedlang.slice(0, -1)
                this.$emit('granted-roles', this.selectedlang)
            },
            updateCheckall: function(){
                if (this.languages.includes(1)==false){
                    this.languages.push(1)
                }
                if(this.languages.length == this.langsdata.length){
                    this.checkAll()
                }else{
                    this.isCheckAll = false;
                }

                this.printValues()
                this.selectedlang   = this.selectedlang.slice(0, -1)
                this.$emit('granted-roles', this.selectedlang)
            },
            printValues: function(){
                this.selectedlang   = "";
                // Read Checked checkboxes value
                for (let key in this.languages) {
                    this.selectedlang += this.languages[key]+",";
                }
            }
        },

        created() {
            this.languages.push(1)
            // console.log(this.userRoles)
            this.langsdata = this.userRoles
        }
    }
</script>

<style scoped>

</style>