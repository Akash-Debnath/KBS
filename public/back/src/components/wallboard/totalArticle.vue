<template>
  <div>
    <div class="counting-number text-red">

      <ICountUp :delay="delay" :endVal="totalArticle" :options="options" @ready="onReady"/>

    </div>
  </div>
</template>

<script>
import ICountUp from 'vue-countup-v2';
export default {
  name: "totalArticle",
  props:['totalArticle'],
  components:{
    ICountUp
  },
  data() {
    return {
      delay: 500,
      endVal: 0,
      options: {
        useEasing: true,
        useGrouping: true,
        separator: ',',
        decimal: '.',
        prefix: '',
        suffix: ''
      },
    }
  },
  methods:{
    onReady: function(instance, CountUp) {
      const that = this;
      instance.update(that.totalArticle);
    },
  },
  created() {
    // console.log(this.totalArticle);
  }
}
</script>

<style scoped>

</style>
