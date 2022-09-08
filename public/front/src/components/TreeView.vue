<template>
  <li class="nav-item">
    <!--            :id="'treeLevel'+item.id"-->
    <div :class="{bold: isFolder}" class="d-flex justify-content-between cat-expand-menu-item">
      <a class="nav-link d-block w-100" :class="current_slug == router_path ? 'active' : ''" href="#" @click.prevent="getSelectedArticleList(item)">{{ item.name }}</a>
      <span class="cat-expand" v-if="isFolder" @click="toggle">{{ isOpen ? '−' : '+' }}</span>
    </div>
    <ul class="list-unstyled list-inline mb-0 pl-10" v-show="isOpen" v-if="isFolder">

        <span v-for="(child, index) in item.children_recursive" :key="index" @click="getSelectedArticleList(child)">
          <tree-view class="item" :item="child"></tree-view>
        </span>
    </ul>
  </li>
</template>

<script>
export default {
  name: "TreeView",
  props: {
    item: Object,
    router_path : String
  },
  data: function() {
    return {
      isOpen: false,
      pressedCategoryIdHistory : [],
      current_slug : '',
      current_id : ''
    };
  },
  computed: {
    isFolder: function() {
      return this.item.children_recursive && this.item.children_recursive.length;
    }
  },
  methods: {
    toggle: function() {
      if (this.isFolder) {
        this.isOpen = !this.isOpen;
      }
    },
    getSelectedArticleList(item){
      // console.log('hi');
      // this.$router.push({ path: `/category-list/${item.slug}` })
      // localStorage.setItem('category-article-list', JSON.stringify(item));
      this.$emit('category-slug',item.slug,item.id);
      this.current_slug = item.slug;
    }
  },
  created() {
    this.current_slug = this.item.slug;
    this.current_id = this.item.id;
    // this.current_slug = this.item.slug;
  }
}
</script>

<style scoped>

</style>
