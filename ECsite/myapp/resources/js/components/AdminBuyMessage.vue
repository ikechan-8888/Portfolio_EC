<template>
    <div class="admin_message_list">
        <p>新規に商品が購入されました</p>
        <p>購入日:{{item.created_day}}</p>
        <p>購入ID:{{item.id}}</p>
        <p>発送状態:{{item.shipping_status}}</p>
    </div>
</template>
<script>
export default {
    props: {
        item: {
            type: Object,
            required: true
        }
    }
}
</script>