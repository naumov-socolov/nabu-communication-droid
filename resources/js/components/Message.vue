<template>
    <span class="message">
        🖐
    </span>
</template>

<script>
    export default {
        props: {
            response: {
                type: Object,
                default: {},
            },
        },
        mounted() {
            console.log("status: ", this.response.status)
        },
    };
</script>

<style>
    .message {
        font-size: 16px;
    }
</style>
