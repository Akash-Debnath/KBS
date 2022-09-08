<template>

    <div>
        <!-- Check All -->
        <label><input type='checkbox' @click='checkAll()' v-model='languages' :value="0"> Public</label>
        <!-- Checkboxes list -->
        <ul class="list-unstyled permission-list m-0 p-0" v-if="isCheckAll != true">
            <li class="text-left pb-2" v-for='lang in langsdata'>

                <label v-if="lang.id==1" class="pl-2 mb-0" :for="lang.id">
                    <input disabled type="checkbox" v-model="languages" :id="lang.id" :value="lang.id"> {{lang.name}}
                </label>

                <label v-else class="pl-2 mb-0" :for="lang.id">
                    <input @change='updateCheckall()' type="checkbox" v-model="languages" :id="lang.id" :value="lang.id"> {{lang.name}}
                </label>

            </li>
        </ul>

<!--        <br>-->
<!--        Selected items : {{ selectedlang }}-->

    </div>

</template>

<script>
    export default {
        name: "CustomRoleEdit",

        props: ['userRoles','banneRoleAccess'],

        data(){
            return{
                isCheckAll          : false,
                langsdata           : [],
                // langsPrevdata       : [],
                languages           : [],
                selectedlang        : "",
                approvedRoles       : ''
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
                }else{
                    this.languages.push(1)
                }
                this.printValues()
                this.selectedlang   = this.selectedlang.slice(0, -1)
                this.$emit('granted-roles', this.selectedlang)
            },


            updateCheckall: function(){
                // console.log(this.languages)
                // console.log(this.languages.length)
                if (this.languages.includes(1)==false){
                    this.languages.push(1)
                }
                if(this.languages.length == this.langsdata.length){
                    // this.isCheckAll = true;
                    this.checkAll()
                }else{
                    this.isCheckAll = false;
                }
                //
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
            },
            checkedPrevData(){
                this.languages = this.approvedRoles.split(',').map(function(item) {
                    return parseInt(item, 10);
                });


                for (let key in this.languages) {
                    this.selectedlang += this.languages[key]+",";
                }
                this.selectedlang   = this.selectedlang.slice(0, -1)
                if(this.languages.length > this.langsdata.length){
                    // this.isCheckAll = true;
                    this.checkAll()
                    // console.log('hide')
                }else{
                    this.isCheckAll = false;
                }
            },
        },
        created() {
            this.langsdata      = this.userRoles;
            this.approvedRoles  = this.banneRoleAccess;
            // console.log(this.langsdata)
            console.log(this.banneRoleAccess)
            this.checkedPrevData()
        }
    }
</script>

<style scoped>

</style>