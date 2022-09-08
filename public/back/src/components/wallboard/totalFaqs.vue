<template>
  <div>
    <div class="counting-number text-yellow">

      <ICountUp :delay="delay" :endVal="totalFaq" :options="options" @ready="onReady"/>

    </div>
  </div>
</template>

<script>
import ICountUp from 'vue-countup-v2'
export default {
  name: "totalFaqs",
  props:['totalFaq'],
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
      instance.update(that.totalFaq);
    },
  },
  created() {
    // console.log(this.totalFaq);
  }
}
</script>

<style scoped>

</style>
