<template>
    <!--**********************************
                     Main Footer
        ***********************************-->
    <div class="g-footer-link-area">
        <div class="container-fluid">
            <div class="g-footer-link-main">
                <ul>
                    <li ><a href="">Useful links</a></li>
                     <li v-for="item in links" :key="item.id"><a  :href="item.url" target="_blank">{{item.title}}</a></li>
                    <!-- <li><a href="">IFIC website</a></li>
                    <li><a href="">CES</a></li>
                    <li><a href="">FiMi</a></li>
                    <li><a href="">Contact Center</a></li>
                    <li><a href="">Party</a></li>
                    <li><a href="">MBM</a></li>
                    <li><a href="">SSL</a></li>
                    <li><a href="">DC Admin</a></li>
                    <li><a href="">Digital Banking </a></li>
                    <li><a href="">e-approval </a></li>
                    <li><a href="">Outlook </a></li>
                    <li><a href="">Internet Banking </a></li>
                    <li><a href="">Quiz </a></li> -->
                </ul>
            </div>
        </div>
    </div>

    <div class="g-bottom-svg-area d-none">
        <BottomArea/>
    </div>
    <!--    End Main Footer-->

</template>

<script>
import BottomArea from "../icons/BottomArea.vue";
import { ref, computed, onBeforeUpdate, onMounted } from "vue";
export default {
    name: "Footer.vue",
    components: {BottomArea},
    props: {
        links: {type:Array},
    },

}
</script>

<style lang="scss" scoped>
/*============================
         Main Footer Style
  ============================*/
.g-footer-link-area{
    border-top: 1px solid #cde9db;
    //position:absolute;
    //left: 0;
    //bottom: 47px;
    //right: 0;
    //overflow: hidden;
}
.g-footer-link-main {
    padding: 1rem;
    display: flex;
    justify-content: center;
    align-items: center;

    ul {
        margin: 0 0 -1rem;
        padding: 0;
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    ul {
        li {
            position: relative;
            margin-bottom: 1rem;
            display: inline-block;

            &::after {
                content: "";
                position: absolute;
                background-color: rgba(0, 121, 63, 0.3);
                width: 2px;
                height: 20px;
                top: 50%;
                transform: translateY(-50%);
            }

            &:last-child {
                &::after {
                    display: none;
                }
            }

            a {
                border-radius: 5px;
                padding: 0.6rem 1.2rem;
                transition: all 0.4s ease-in-out;
                text-decoration: none;

                &:hover {
                    background-color: #00793F;
                    color: #ffffff;
                }
            }
        }
    }
}

.g-bottom-svg-area {
    //position:absolute;
    //left: 0;
    //bottom: 0;
    //right: 0;
    //overflow: hidden;
}
</style>
