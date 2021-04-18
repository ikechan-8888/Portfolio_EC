<template>
    <div class="admin_product_list">
        <div class="admin_product_left">
            <div class="admin_buy_buttons">
                <h2 class="button admin_buy_button" :class="{'select_start_butoon': select === 1}">販売中</h2>
                <h2 class="button admin_buy_button" :class="{'select_stop_butoon': select === 0}">停止中</h2>
            </div>
            <h2 class="admin_product_font admin_product_id">{{item.id}}</h2>
            <h2 class="admin_product_font admin_product_name">{{item.name}}</h2>
        </div>
        <div class="admin_product_center" v-show="display === 0">
            <p class="admin_product_font admin_product_price">金額:¥{{item.price}}</p>
            <p class="admin_product_font admin_product_img">画像名:{{item.img_name}}</p>
            <p class="admin_product_font admin_product_category">カテゴリー:{{categoryName}}</p>
            <p class="admin_product_font admin_product_color">色:{{item.color}}</p>
        </div>
        <div class="admin_product_center" v-show="display === 1">
            <p class="admin_product_font admin_product_size">Sサイズ: <span class="admin_red_font">{{item.ssize_items}}</span>/{{item.ssize_register_items}}</p>
            <p class="admin_product_font admin_product_size">Mサイズ: <span class="admin_red_font">{{item.msize_items}}</span>/{{item.msize_register_items}}</p>
            <p class="admin_product_font admin_product_size">Lサイズ: <span class="admin_red_font">{{item.lsize_items}}</span>/{{item.lsize_register_items}}</p>
            <p class="admin_product_font admin_product_size">XLサイズ: <span class="admin_red_font">{{item.xlsize_items}}</span>/{{item.xlsize_register_items}}</p>
        </div>
        <div class="admin_product_center" v-show="display === 2">
            <p class="admin_product_font admin_product_day">登録日: {{item.created_at}}</p>
            <p class="admin_product_font admin_product_day">更新日: {{item.updated_at}}</p>
        </div>
        <div class="admin_chenge_buttons">
            <RouterLink class="button admin_chenge_button" :to="`/adminProductEdit/${item.id}`">編集</RouterLink>
        </div>
        <div class="admin_chenge_buttons">
            <button class="button admin_delete_button" @click="deleteModal">削除</button>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        },
        display:{
            type: Number,
            required: true
        }
    },
    data(){
        return{
            select: null,
            categoryName: '',
        }
    },
    methods: {
        async fetchProduct(){
            const moment = require("moment");

            if(this.item.display_status === 0){
                this.select = 0;
            }else if(this.item.display_status === 1){
                this.select = 1;
            }

            if(this.item.category_status === 0){
                this.categoryName = "トップス";
            }else if(this.item.category_status === 1){
                this.categoryName = "ボトムス";
            }

            this.item.created_at = moment.utc(this.item.created_at).local().format("YYYY年MM月DD日");
            this.item.updated_at = moment.utc(this.item.updated_at).local().format("YYYY年MM月DD日");
        },
        deleteModal(){
            this.$emit('deleteModal', {
                productId: this.item.id,
                productDisplayStatus: this.item.display_status,
                productName: this.item.name,
                productImg: this.item.img_name,
            })
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