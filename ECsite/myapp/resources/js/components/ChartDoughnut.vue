<script>
    import { Doughnut, mixins } from 'vue-chartjs'
    import Chart from "chart.js";
    const { reactiveProp } = mixins;

    export default {
        name: 'DoughnutChart',
        extends: Doughnut,
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
                    plugins: {
                    colorschemes: {
                        scheme: 'tableau.Tableau20',
                    },
                    },
                    cutoutPercentage: 80,//グラフの幅
                    responsive: true,// コンテナサイズが変更された際に、チャートキャンバスサイズを変更する
                    animation: {// アニメーションに関する設定
                    duration: 3500, // アニメーションにかける時間（ミリ秒）
                    easing: "easeInOutQuad", // 使用するイージング(easing)(訳注:アニメーションの効果
                    animateRotate: true, // （円グラフ）trueの場合、グラフは回転アニメーションをします。
                    },
                    tooltips: {
                        // ツールチップに関する設定
                        display: false, // キャンバス上でツールチップを有効にします
                        enabled: false
                    },
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