<template>
  <div>
    <div class="counting-number text-light-blue">

      <ICountUp :delay="delay" :endVal="totalQuiz" :options="options" @ready="onReady"/>

    </div>
  </div>
</template>

<script>
import ICountUp from 'vue-countup-v2'
export default {
  name: "totalQuiz",
  props:['totalQuiz'],
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
      instance.update(that.totalQuiz);
    },
  },
  created() {
    // console.log(this.totalQuiz);
  }
}
</script>

<style scoped>

</style>
