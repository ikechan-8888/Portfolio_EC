<template>
    <div class="boughtproduct-detail">
        <figure class="photo-detail__pane photo-detail__boughtproduct">
            <img class="boughtproduct-detail__photo" :src="`http://localhost/storage/${item.img_name}`" :alt="`Photo by ${item.name}`">
        </figure>
        <div class="boughtproduct-detail__content">
            <h5 class="boughtproduct-detail__name">
                {{item.name}}
            </h5>
            <div class="boughtproduct-detail__contents">
                <h5 class="boughtproduct-detail__color">
                    カラー：{{item.color}}
                </h5>
                <span class="slash">
                    /
                </span>
                <h5 class="boughtproduct-detail__size">
                    サイズ：{{item.item_size}}
                </h5>
            </div>
            <div class="boughtproduct-detail__contents">
                <h5 class="boughtproduct-detail__number">
                    数量：{{item.item_number}}
                </h5>
                <span class="slash">
                    /
                </span>
                <h5 class="boughtproduct-detail__price">
                    ¥{{item.price.toLocaleString()}}
                </h5>
            </div>
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