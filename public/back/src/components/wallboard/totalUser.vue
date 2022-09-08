<template>
  <div>
    <div class="counting-number text-primary">

      <ICountUp :delay="delay" :endVal="totalUser" :options="options" @ready="onReady"/>

    </div>
  </div>
</template>

<script>
import ICountUp from 'vue-countup-v2'
export default {
  name: "totalUser",
  props:['totalUser'],
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
      instance.update(that.totalUser);
    },
  },
  created() {
    // console.log(this.totalUser);
  }
}
</script>

<style scoped>

</style>
