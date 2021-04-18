<template>
    <div class="buy-detail">
        <figure class="photo-detail__pane photo-detail__buyImage">
            <img class="buy-detail__photo" :src="`http://localhost/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
        </figure>
        <div class="buy-detail__content">
            <h5 class="buy-detail__name">
                {{item.name}}
            </h5>
            <h5 class="buy-detail__color">
                カラー：{{item.color}}
            </h5>
            <h5 class="buy-detail__size">
                サイズ：{{item.item_size}}
            </h5>
            <h5 class="buy-detail__number">
                数量：{{item.item_number}}
            </h5>
        </div>
        <div class="buy-detail__contentprice">
            <h5 class="buy-detail__price">
                ¥{{item.price.toLocaleString()}}
            </h5>
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

    }
}
</script>