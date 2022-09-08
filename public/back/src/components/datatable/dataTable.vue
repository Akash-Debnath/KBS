<template>
  <div>
    <b-table striped hover :items="posts"></b-table>
  </div>
</template>

<script>
export default {
  name: "dataTable",

  props: ['posts'],

  data(){
    return{

    }
  },

  methods:{

  },

  created() {
    console.log(this.posts)
  }
}
</script>

<style scoped>

</style>
