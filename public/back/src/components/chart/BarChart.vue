<template>
    <div>
        <canvas ref="canvas" id="canvas"></canvas>
    </div>
</template>


<script>
import { Bar } from "vue-chartjs";

export default {
    extends: Bar,
    methods:{
        // callIntervalGraph() {
        //     setInterval(()=>{
        //         this.callAfterOneMinute();
        //     },5e3);
        // },
        callAfterOneMinute(){
            this.renderChart(
                {
                    labels: [
                        "January",
                        "February",
                        "March",
                        "April",
                        "May",
                        "June",
                        "July",
                        "August",
                        "September",
                        "October",
                        "November",
                        "December"
                    ],
                    datasets: [
                        {
                            label: "Data One",
                            backgroundColor: "#f87979",
                            data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
                        },
                        {
                            label: "Data Two",
                            backgroundColor: "#eeaa22",
                            data: [100, 20, 12, 39, 10, 40, 50, 80, 40, 20, 12, 11]
                        }
                    ]
                },
                { responsive: true, maintainAspectRatio: false }
            );
        },
    },
    mounted()
    {
        this.callAfterOneMinute();
        // this.callIntervalGraph();
    }
};
</script>
