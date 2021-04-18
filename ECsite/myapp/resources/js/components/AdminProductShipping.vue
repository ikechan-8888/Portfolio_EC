<template>
    <div class="shippingProduct-detail">
        <figure class="photo-detail__pane photo-detail__shippingProducts">
            <img class="shippingProduct-detail__photo" :src="`http://localhost/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
        </figure>
        <h5 class="shippingProduct-detail__id">
            対象商品ID：{{item.product_id}}
        </h5>
        <h5 class="shippingProduct-detail__name">
            対象商品名：{{item.name}}
        </h5>
        <h5 class="shippingProduct-detail__size">
            対象サイズ：{{item.item_size}}
        </h5>
        <h5 class="shippingProduct-detail__number">
            発送数量：{{item.item_number}}個
        </h5>
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

    }
}
</script>