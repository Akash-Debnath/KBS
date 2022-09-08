<template>
    <div class="modal fade" id="profileDetails" tabindex="-1" aria-labelledby="profileDetailsLabel" aria-hidden="true" @click="toggoleProfile">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body profile-content p-20">
                    <h4 class="user-title text-center pb-30 my-0">{{userInformation ? userInformation.first_name+' '+userInformation.last_name : 'Jhon Due'}}'s Profile</h4>
<!--                    <div class="form-group border-bottom pb-3">-->
<!--                        <img style="height: 175px; width: auto" src="http://localhost/ccKnowledgeBase/kbs2.1/public/media/user.jpg">-->
<!--                    </div>-->
                    <div class="form-group border-bottom pb-3">
                        <strong>Username:</strong> {{userInformation ? userInformation.username : 'jhon404'}}
                    </div>

                    <div class="form-group border-bottom pb-3">
                        <strong>Email:</strong> {{userInformation ? userInformation.email : 'jhondue@genuity.us'}}
                    </div>

                    <div class="form-group border-bottom pb-3">
                        <strong>Roles:</strong> {{userInformation ? userInformation.roles[0].name : 'Agent'}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProfileDetails",

        data(){
            return{
                userInformation : ''
            }
        },
        methods:{
            toggoleProfile(){
                this.$emit('close-profile',false)
            }
        },
        created() {
            if (sessionStorage.userInformation) {
                this.userInformation = JSON.parse(sessionStorage.userInformation);
                console.log(this.userInformation);
            }
        }
    }
</script>


<style>
    .header {
        z-index: 1039;
    }

    .user-title {
        font-size: 24px;
        font-weight: 600;
        color: #000;
    }
</style>