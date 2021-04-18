<template>
    <div class="admin_product_list">
        <div class="admin_product_left">
            <div class="admin_buy_buttons">
                <h2 class="button admin_buy_button" :class="{'select_start_butoon': select === 1}">発送済</h2>
                <h2 class="button admin_buy_button" :class="{'select_stop_butoon': select === 0}">未発送</h2>
            </div>
            <h2 class="admin_product_font admin_shipping_day">{{item.created_at}}</h2>
            <h2 class="admin_product_font admin_shipping_day">{{howShipping}}</h2>
            <h2 class="admin_product_font admin_shipping_id">{{item.id}}</h2>
            <h2 class="admin_product_font admin_shipping_loginid">{{item.login_id}}</h2>
            <h2 class="admin_product_font admin_shipping_numbers">{{item.item_numbers}}</h2>
            <h2 class="admin_product_font admin_shipping_delivery">{{item.delivery_address}}</h2>
        </div>
        <div class="admin_chenge_buttons">
            <RouterLink class="button admin_chenge_button" :to="`/adminShippingEdit/${item.id}`">詳細情報</RouterLink>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    data(){
        return{
            select: null,
            howShipping: '-',
        }
    },
    methods: {
        async fetchProduct(){
            const moment = require("moment");

            if(this.item.shipping_flag === 0){
                this.select = 0;
            }else if(this.item.shipping_flag === 1){
                this.select = 1;
                this.howShipping = moment.utc(this.item.updated_at).local().format("YYYY年MM月DD日");
            }

            const itemNumber = this.item.item_numbers.split(',');
            this.item.item_numbers = itemNumber.reduce(function(prev, current) {return Number(prev)+Number(current);});

            this.item.created_at = moment.utc(this.item.created_at).local().format("YYYY年MM月DD日");
        },
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