<template>
    <div class="delivery-detail">
        <div class="delivery-detail__pane">
            <div class="delivery-detail__input">
                <input class="delivery-detail__radio" type="radio" name="deliveryAddress" @change.prevent="selectDelivery">
            </div>
            <div class="delivery-detail__content">
                <h5 class="delivery-detail__name delivery-detail__contents">
                    名前：{{item.delivery_name}}
                </h5>
                <h5 class="delivery-detail__code delivery-detail__contents">
                    郵便：{{item.postal_code}}
                </h5>
                <h5 class="delivery-detail__address delivery-detail__contents">
                    住所：{{item.delivery_address}}
                </h5>
                <h5 class="delivery-detail__tel delivery-detail__contents">
                    電話：{{item.tel}}
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
        selectDelivery(){
            this.$emit('selectDelivery', {
                id: this.item.id,
                name: this.item.delivery_name,
                code: this.item.postal_code,
                address: this.item.delivery_address,
                tel: this.item.tel,
            })
        }
    }
}
</script>