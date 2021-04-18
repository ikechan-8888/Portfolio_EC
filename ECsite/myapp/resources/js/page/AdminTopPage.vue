<template>
  <div>
    <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
    <!-- <AdminProductDelete class="overlay" v-show="checkDelete" :key="deleteProduct.id" :item="deleteProduct" @close="onclose" @update="onUpdate" /> -->
    <div class="admin_new_register">
        <h2 class="admin_title">トップページ</h2>
        <!-- <RouterLink class="button admin_new_registerButton" :to="`/adminproductregister`">新規商品登録</RouterLink> -->
    </div>
    <div class="admin_block">
      <div class="admin_top_block">
        <div class="admin_left_block">
          <div class="product_numbers">
            <div class="product_number">
              <i class="fas fa-clipboard-list"></i>
              <div class="product_number_block">
                <h3 class="product_number_font">登録商品数</h3>
                <h4 class="product_number_number">{{Number(registerproduct).toLocaleString()}}件</h4>
              </div>
            </div>
            <div class="product_number">
              <i class="far fa-check-circle"></i>
              <div class="product_number_block">
                <h3 class="product_number_font">販売中商品数</h3>
                <h4 class="product_number_number">{{Number(saleproduct).toLocaleString()}}件</h4>
              </div>
            </div>
            <div class="product_number">
              <i class="fas fa-ban"></i>
              <div class="product_number_block">
                <h3 class="product_number_font">販売停止中商品数</h3>
                <h4 class="product_number_number">{{Number(stopproduct).toLocaleString()}}件</h4>
              </div>
            </div>
          </div>
          <div class="shipping_message">
            <div class="shipping_message_fonts">
              <h3>未発送について</h3>
              <h4 class="shipping_message_font" v-show="notShippingNumber !== 0">未発送があります</h4>
              <h4 class="shipping_message_font" v-show="notShippingNumber === 0">未発送はありません</h4>
            </div>
            <div class="shipping_message_content">
              <div class="shipping_message_content_blocks">
                <div class="shipping_message_content_block">
                  <h3 class="product_number_font">未発送件数</h3>
                  <h4 class="product_number_number">{{Number(notShippingNumber).toLocaleString()}}件</h4>
                </div>
                <div class="shipping_message_content_block">
                  <h3 class="product_number_font">商品数</h3>
                  <h4 class="product_number_number">{{Number(notShippingProductNumber).toLocaleString()}}品</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="admin_right_block">
          <div>
            <h6>本日の販売履歴</h6>
            <div class="buy_message_lists">
              <h5 v-if="!messages.length">まだ購入履歴がありません</h5>
              <AdminBuyMessage v-else v-for="message in messages" :key="message.id" :item="message" />
            </div>
          </div>
          <div class="buy_status_content">
            <h6>本日の販売状況</h6>
            <div class="buy_status_blocks">
              <div class="buy_status_block">
                <div class="buy_status_font buy__font">
                  <h5>販売件数</h5>
                  <p>{{Number(buyNumber).toLocaleString()}}/{{Number(targetBuyNumber).toLocaleString()}}件</p>
                </div>
                <div class="chart_block">
                  <p>{{buyChartDataOk}}%</p>
                  <ChartDoughnut class="chart_doughnut" :chart-data="buyChartData" />
                </div>
              </div>
              <div class="buy_status_block">
                <div class="buy_status_font buyProduct__font">
                  <h5>販売商品数</h5>
                  <p>{{Number(buyProductNumber).toLocaleString()}}/{{Number(targetBuyProductNumber).toLocaleString()}}品</p>
                </div>
                <div class="chart_block">
                  <p>{{buyProductChartDataOk}}%</p>
                  <ChartDoughnut class="chart_doughnut" :chartData="buyProductChartData" />
                </div>
              </div>
              <div class="buy_status_block">
                <div class="buy_status_font buyPrice__font">
                  <h5>売り上げ金額</h5>
                  <p>¥{{Number(buyPrice).toLocaleString()}}/{{Number(targetBuyPrice).toLocaleString()}}</p>
                </div>
                <div class="chart_block">
                  <p>{{buyPriceChartDataOk}}%</p>
                  <ChartDoughnut class="chart_doughnut" :chartData="buyPriceChartData" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="admin_center_block">
        <div class="chart-container" style="position: relative; width: 100%; height: 30rem;">
          <ChartLine class="chart_Line" :chartData="chartLineData" />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Loading from '../components/Loading.vue'
import AdminBuyMessage from '../components/AdminBuyMessage.vue'
import ChartDoughnut from '../components/ChartDoughnut.vue'
import ChartLine from '../components/ChartLine.vue'
import { OK } from '../util'

export default{
  components: {
    Loading,
    AdminBuyMessage,
    ChartDoughnut,
    ChartLine,
  },
  data(){
    return {
      chartLineData: {
        labels: [],
        datasets: [{
          label: '売り上げ金額',
          lineTension: 0,
          data: [0, 0, 0, 0, 0, 0, 0],
          backgroundColor: 'rgba(255, 132, 16, 0.25)',
          borderColor: 'rgba(255, 132, 16, 0.9)',
          yAxisID: '売り上げ'
        }, {
          label: '販売商品数',
          lineTension: 0,
          data: [0, 0, 0, 0, 0, 0, 0],
          backgroundColor: 'rgba(20, 156, 190, 0.25)',
          borderColor: 'rgba(20, 156, 190, 0.9)',
          yAxisID: '商品数'
        }]
      },
      messages: [],
      loading: true,
      loadingType: {
        id: 0,
        type: 0
      },
      registerproduct: 0,
      saleproduct: 0,
      stopproduct: 0,
      notShippingNumber: 0,
      notShippingProductNumber: 0,
      buyNumber: 0,//当日件数
      buyProductNumber: 0,//当日商品数
      buyPrice: 0,//当日売り上げ
      targetBuyNumber: 20,//目標件数
      targetBuyProductNumber: 30,//目標商品数
      targetBuyPrice: 200000,//目標売り上げ
      buyChartData: {//販売件数のデフォルト値
        datasets: [
          {
            data: [0, 100],//割合
            backgroundColor: ['#f18928','#ffdab7'],//グラフの色
            hoverBackgroundColor: ['#f18928','#ffdab7'],
            borderColor: 'transparent',//線を無くしたい
          },
        ],
      },
      buyChartDataOk: 0,//販売割合
      buyChartDataNo: 0,//残割合
      buyProductChartData: {//販売商品数のデフォルト値
        datasets: [
          {
            data: [0, 100],//割合
            backgroundColor: ['#f18928','#ffdab7'],//グラフの色
            hoverBackgroundColor: ['#f18928','#ffdab7'],
            borderColor: 'transparent',//線を無くしたい
          },
        ],
      },
      buyProductChartDataOk: 0,//販売割合
      buyProductChartDataNo: 0,//残割合
      buyPriceChartData: {//売り上げ金額のデフォルト値
        datasets: [
          {
            data: [0, 100],//割合
            backgroundColor: ['#f18928','#ffdab7'],//グラフの色
            hoverBackgroundColor: ['#f18928','#ffdab7'],
            borderColor: 'transparent',//線を無くしたい
          },
        ],
      },
      buyPriceChartDataOk: 0,//販売割合
      buyPriceChartDataNo: 0,//残割合
    }
  },
  methods: {
    async fetchProduct(){
      this.loading = true;

      Promise.all([this.execNumber(), this.execToday(), this.execNotShipping(), this.execChartLine()]).then(results => {
        this.loading = false;
        //販売件数
        let copyBuyChartData = this.buyChartData;
        copyBuyChartData.datasets[0].data[0] = this.buyChartDataOk;//販売割合
        copyBuyChartData.datasets[0].data[1] = this.buyChartDataNo;//残割合
        this.buyChartData = Object.create(copyBuyChartData);
        //販売商品数
        let copybuyProductChartData = this.buyProductChartData;
        copybuyProductChartData.datasets[0].data[0] = this.buyProductChartDataOk;//販売割合
        copybuyProductChartData.datasets[0].data[1] = this.buyProductChartDataNo;//残割合
        this.buyProductChartData = Object.create(copybuyProductChartData);
        //売り上げ金額
        let copybuyPriceChartData = this.buyPriceChartData;
        copybuyPriceChartData.datasets[0].data[0] = this.buyPriceChartDataOk;//販売割合
        copybuyPriceChartData.datasets[0].data[1] = this.buyPriceChartDataNo;//残割合
        this.buyPriceChartData = Object.create(copybuyPriceChartData);
      }).catch(reject => {
        console.log(reject);
        this.loading = false;
        return;
      });
    },
    //商品件数
    async execNumber(){
      const response = await axios.get(`/api/productNumber`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return reject();
      }

      const products = response.data;

      if(products.length){
        this.registerproduct = products.length;
        let sale = 0;
        let stop = 0;

        products.forEach(data => {
          if(data.display_status === 0){
            stop += 1;
          }else if(data.display_status === 1){
            sale += 1;
          }
        });

        this.stopproduct = stop;
        this.saleproduct = sale;
      }

      return 'OK';
    },
    //本日の販売
    async execToday(){
      const moment = require("moment");
      const response = await axios.get(`/api/buyToday`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return reject();
      }

      let messageData = response.data;

      if(messageData.length){
        let buyNumber = 0;
        let buyProductNumber = 0;
        let buyPrice = 0;

        messageData.forEach(data => {
          buyNumber++;

          const itemNumber = data.item_numbers.split(',');
          const getNumber = this.arraySum(itemNumber);
          buyProductNumber += Number(getNumber);

          buyPrice += data.buy_price - 540;//送料と代引き

          if(data.shipping_flag === 0){
            data.shipping_status = '未発送';
          }else if(data.shipping_flag === 1){
            data.shipping_status = '発送済';
          }

          data.created_day = moment.utc(data.created_at).local().format("YYYY年MM月DD日HH時mm分ss秒");
        });

        //四捨五入
        const percentageBuyOk = Math.round(buyNumber / this.targetBuyNumber * 100);
        let percentageBuyNo = 0;
        if(percentageBuyOk < 100){
          percentageBuyNo = 100 - percentageBuyOk;
        }
        this.buyChartDataOk = percentageBuyOk;
        this.buyChartDataNo = percentageBuyNo;
        const percentageBuyProductOk = Math.round(buyProductNumber / this.targetBuyProductNumber * 100);
        let percentageBuyProductNo = 0;
        if(percentageBuyProductOk < 100){
          percentageBuyProductNo = 100 - percentageBuyProductOk;
        }
        this.buyProductChartDataOk = percentageBuyProductOk;
        this.buyProductChartDataNo = percentageBuyProductNo;
        const percentageBuyPriceOk = Math.round(buyPrice / this.targetBuyPrice * 100);
        let percentageBuyPriceNo = 0;
        if(percentageBuyPriceOk < 100){
          percentageBuyPriceNo = 100 - percentageBuyPriceOk;
        }
        this.buyPriceChartDataOk = percentageBuyPriceOk;
        this.buyPriceChartDataNo = percentageBuyPriceNo;

        this.messages = messageData;
        this.buyNumber = buyNumber;
        this.buyProductNumber = buyProductNumber;
        this.buyPrice = buyPrice;
      }else{
        this.buyChartDataNo = 100;
        this.buyProductChartDataNo = 100;
        this.buyPriceChartDataNo = 100;
      }

      return 'OK';
    },
    //未発送
    async execNotShipping(){
      const response = await axios.get(`/api/notShipping`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return reject();
      }

      const notShippingData = response.data;

      if(notShippingData.length){
        let number = 0;
        let productNumber = 0;

        notShippingData.forEach(data => {
          number++;
          const itemNumber = data.item_numbers.split(',');
          const getNumber = this.arraySum(itemNumber);
          productNumber += Number(getNumber);
        });

        this.notShippingNumber = number;
        this.notShippingProductNumber = productNumber;
      }

      return 'OK';
    },
    //グラフデータ
    async execChartLine(){
      const moment = require("moment");
      const response = await axios.get(`/api/chartLinedata`);

      if(response.status !== OK){
        this.$store.commit('error/setCode', response.status);
        return reject();
      }

      const getData = response.data;

      let oneWeek = [];
      let displayOneWeek = [];
      const dayWeek = "日月火水木金土";
      for(let i = 0; i < 7 ; i++){
        const date = new Date();
        date.setDate(date.getDate() - i);
        const year  = date.getFullYear();
        const month = date.getMonth() + 1;
        const day   = date.getDate();
        const day_num = date.getDay();
        const YYMMDDday = year+"/"+month+"/"+day;
        oneWeek.unshift({
          [YYMMDDday]: {
            buyProductNumber: 0,
            buyPrice: 0,
          }
        });
        displayOneWeek.unshift(month+"月"+day+"日("+dayWeek.charAt(day_num)+"曜日)");
      }

      let inBuyProductNumber = [];
      let inBuyPrice = [];
      if(getData.length){
        getData.forEach(data => {
          const day = moment.utc(data.created_at).local().format("YYYY/M/D");

          const itemNumber = data.item_numbers.split(',');
          const buyProductNumber = this.arraySum(itemNumber);
          const buyPrice = data.buy_price - 540;//送料と代引き

          oneWeek.forEach(weekData => {
            const getkey = Object.keys(weekData);
            const keyName = getkey[0];
            if(day === keyName){
              weekData[keyName].buyProductNumber += Number(buyProductNumber);
              weekData[keyName].buyPrice += Number(buyPrice);
            }
          });
        });

        oneWeek.forEach(data => {
          const getkey = Object.keys(data);
          const keyName = getkey[0];
          inBuyProductNumber.push(data[keyName].buyProductNumber);
          inBuyPrice.push(data[keyName].buyPrice);
        });
      }

      let chartLineData = this.chartLineData;
      chartLineData.labels = displayOneWeek;//一週間分の日にち
      if(getData.length){
        chartLineData.datasets[0].data = inBuyPrice;//売り上げ金額
        chartLineData.datasets[1].data = inBuyProductNumber;//販売商品数
      }
      this.chartLineData = Object.create(chartLineData);

      return 'OK';
    },
    arraySum(array){
      return array.reduce(function(prev, current) {
        return Number(prev)+Number(current);
      });
    }
  },
  watch: {
    $route: {
      async handler(){
        await this.fetchProduct();
      },
      immediate: true
    }
  }
}
</script>