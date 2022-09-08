<template>
    <div class="g-common-page-layouts">

        <div class="container-fluid my-5">
            <div class="g-common-page-area">
                <div class="g-common-page-main py-5">

                    <!--Sidebar / Navigation link-->
                    <div class="g-common-page-aside">
                        <ul>
                            <li><a class="active" href="">Savings account</a></li>
                            <li><a href="">IFIC Amar Account</a></li>
                            <li><a href="">Shohoj Account</a></li>
                            <li><a href="">IFIC Savings Account</a></li>
                            <li><a href="">10TK. Savings Account</a></li>
                        </ul>
                    </div>
                    <!--End Sidebar / Navigation link-->

                    <!--                    Right Content-->
                    <div class="g-common-page-right">
                        <div class="g-common-banner-area pb-5">
                            <img class="img-fluid" src="../../assets/images/common-banner.png" alt="">
                        </div>

                        <div class="g-common-page-content">
                            <h4>Lorem ipsum dolor sit amet.</h4>
                            <small>Lorem ipsum dolor.</small>

                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab at cumque cupiditate
                                deserunt enim, eveniet exercitationem id ipsa labore magnam molestiae possimus
                                praesentium quae, quo ratione rem saepe vel vero voluptas voluptate? Doloribus mollitia
                                neque obcaecati placeat quasi quia quo recusandae repellat sint, totam. Asperiores at
                                cum cupiditate delectus dolorem eligendi facilis iure iusto modi quae quas, qui quia
                                quibusdam quisquam ut voluptas voluptatibus? Adipisci alias architecto aspernatur atque
                                culpa dignissimos dolorum, enim esse exercitationem facere iure laboriosam magni minus
                                molestiae mollitia nemo non nostrum obcaecati pariatur placeat quam quibusdam quos
                                ratione repellat repellendus reprehenderit rerum totam veritatis vero voluptate.</p>
                        </div>
                    </div>
                    <!--                    End Right Content-->


                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
import {ref} from 'vue';

const activeName = ref('user')
const tabPosition = ref('left')
</script>

<style scoped lang="scss">
.g-common-page-layouts {
    background-color: #ffffff;
}

.g-common-page-main {
    display: flex;
    justify-content: space-between;
    gap: 3rem;
    align-items:flex-start;
    align-content:flex-start;
    @media all and (max-width: 768px) {
        flex-wrap: wrap;
    }
}

.g-common-page-aside {
    width: 320px;
    background-color: #e7f0ec;
    padding: 1rem;
    flex-shrink: 0;
    border-radius: 10px;
    box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.4);

    ul {
        list-style: none;
        padding: 0;
        margin: 0;

        li {
            margin-bottom: 1px;
            background-color: darken(#e7f0ec, 40%);

            a {
                color: #000000;
                font-size: 16px;
                font-weight: 500;
                text-decoration: none;
                transition: all 0.4s ease-in-out;
                padding: 0.6rem;
                display: block;

                &.active {
                    color: #ffffff;
                    background-color: #00783f;
                }

                &:hover {
                    color: #ffffff;
                    background-color: #00783f;
                }
            }
        }
    }
}

</style>

























