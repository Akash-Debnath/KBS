<template>
  <div>
    <footer class="footer-area py-15 border-top border-gray">
      <div class="container">
        <div class="row">
          <div class="col-sm-6 order-sm-2 text-sm-right text-center">
            <ul class="list-inline mb-0">
              <li class="list-inline-item">
                <router-link class="nav-link" :to="{ name: 'Sitemap'}">
                  <span>SITEMAP</span>
                </router-link>
              </li>
              <li class="list-inline-item">
                <router-link class="nav-item" :to="{ name: 'Contact'}">
                  <span>CONTACT</span>
                </router-link>
              </li>
            </ul>
          </div>
          <div class="col-sm-6 order-sm-1 text-sm-left text-center">
            ©{{ new Date().getFullYear() }} Genuity Systems Ltd, All Rights Reserved.
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: "Footer"
}
</script>

<style scoped>
  .nav-link{
    padding: 0 1rem;
  }

  .nav-link:active{
    color: #3fc6d4;
  }
</style>
