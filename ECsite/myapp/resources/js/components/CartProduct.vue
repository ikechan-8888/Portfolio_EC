<template>
    <div class="photo-detail photo-detail__cart">
        <figure class="photo-detail__pane photo-detail__cartImage">
            <img :src="`http://localhost/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
        </figure>
        <div class="photo-detail__cartContents">
            <div class="photo-detail__cartDetailContents">
                <h5 class="photo-detail__cartName">
                    {{item.name}}
                </h5>
                <h5 class="photo-detail__cartColor">
                    カラー：{{item.color}}
                </h5>
                <h5 class="photo-detail__cartSize">
                    サイズ：{{item.item_size}}
                </h5>
            </div>
        </div>
        <div class="photo-detail__cartDataPrice">
            <h5 class="photo-detail__cartPrice">
                ¥{{item.price.toLocaleString()}}
            </h5>
        </div>
        <div class="photo-detail__cartCount">
            <div class="oneMinus">
                <button :disabled="item.item_number <= 1" :class="{ 'disabled_cart': item.item_number <= 1 }" @click.prevent="onOneMinus" class="OneButton LeftOneButton"><i class="fas fa-minus oneIcon"></i></button>
            </div>
            <span class="photo-detail__cartNumber">{{item.item_number}}</span>
            <div class="onePlus">
                <button @click.prevent="onOnePlus" class="OneButton RightOneButton"><i class="fas fa-plus oneIcon"></i></button>
            </div>
        </div>
        <div class="photo-detail__cartDelete">
            <button class="button button--delete" @click.prevent="onDelete" >
                削除
            </button>
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
    methods: {
        onOnePlus(){
            this.$emit('onOnePlus', {
                productId: this.item.product_id,
                loginId: this.item.login_id,
                itemNumber: this.item.item_number,
                itemSize: this.item.item_size,
            })
        },
        onOneMinus(){
            this.$emit('onOneMinus', {
                productId: this.item.product_id,
                loginId: this.item.login_id,
                itemNumber: this.item.item_number,
                itemSize: this.item.item_size,
            })
        },
        onDelete(){
            this.$emit('onDelete', {
                productId: this.item.product_id,
                loginId: this.item.login_id,
                itemSize: this.item.item_size,
            })
        }
    }
}
</script>