<script>
    import { Line, Bar, mixins } from 'vue-chartjs'
    import Chart from "chart.js";
    const { reactiveProp } = mixins;

    export default {
        name: 'LineChart',
        extends: Line,
        mixins: [reactiveProp],
        props: ['chartData'],//データを動的に変更する
            // chartData: {
            //     type: Object,
            //     required: true
            // },
            // options: {
            //     type: Object,
            //     required: true
            // },
            // },
        data(){
            return{
                options: {//オプションは固定
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true,
                            }
                        }],
                        yAxes: [{
                            id: '売り上げ',
                            position: 'left',
                            ticks: {
                                beginAtZero: true,
                                max: 200000,
                                stepSize: 20000,
                            },
                        }, {
                            id: '商品数',
                            position: 'right',
                            ticks: {
                                beginAtZero: true,
                                max: 30,
                                stepSize: 5,
                            },
                            gridLines: {
                                display:false
                            },
                        }]
                    },
                    responsive: true,
                    maintainAspectRatio: false,//固定アスペクト比を解除する
                },
            }
        },
        mounted() {
            this.renderChart(this.chartData, this.options);
        },
        // computed: {
        //     chartData: function() {
        //         return this.data;
        //     }
        // },
        // watch: {
        //     data: {
        //         handler(newData, oldData){
        //             this.$store.commit('error/setTestData1', newData);
        //             this.renderChart(newData, this.options);
        //         },
        //         deep: true,
        //     }
        // }
    }
    //https://vue-chartjs.org/ja/guide/#%E3%83%81%E3%83%A3%E3%83%BC%E3%83%88%E3%83%87%E3%83%BC%E3%82%BF%E3%81%AE%E6%9B%B4%E6%96%B0
</script>