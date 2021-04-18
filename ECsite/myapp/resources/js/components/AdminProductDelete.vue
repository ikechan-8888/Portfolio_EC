<template>
    <div v-if="item">
        <div class="deleteCheck_modal">
            <Loading class="loadingblock" v-show="loading" :key="loadingType.id" :item="loadingType" />
            <h2 class="delete_title">商品を削除しますか？</h2>
            <div class="admin_delete_content">
                <div class="admin_check_buttons">
                    <h2 class="admin_buy_font">販売状況：</h2>
                    <h2 class="button admin_buy_button admin_check_button select_start_butoon" v-show="item.status === 1">販売中</h2>
                    <h2 class="button admin_buy_button admin_check_button select_stop_butoon" v-show="item.status === 0">停止中</h2>
                </div>
                <div class="admin_delete_products">
                    <h2 class="admin_delete_product">商品ID：{{item.productId}}</h2>
                    <h2 class="admin_delete_product">商品名：{{item.name}}</h2>
                </div>
            </div>
            <div class="admin_deletes">
                <button class="button admin_delete" @click="deleteProduct">削除する</button>
                <button class="button admin_delete" @click="close">削除しない</button>
            </div>
        </div>
    </div>
</template>
<script>
import { OK } from '../util';
import Loading from '../components/Loading.vue'

export default {
    components: {
        Loading
    },
    props: {
        item:{
            type: Object,
            required: true
        }
    },
    data(){
        return {
            tab: 1,
            loading: false,
            loadingType: {
                id: 0,
                type: 1
            },
        }
    },
    methods: {
        close(){
            this.$emit('close', {
                display: null,
            })
        },
        async update(){
            this.$emit('update')
        },
        async deleteProduct(){
            this.loading = true;

            const response = await axios.delete('/api/deleteProduct', {data: this.item});

            if(response.status !== OK){
                this.$store.commit('error/setCode', response.status);
                return false;
            }

            await this.update();

            this.loading = false;
        }
    }
}
</script>