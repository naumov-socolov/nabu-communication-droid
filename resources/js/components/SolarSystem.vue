<template>
    <div class="solar-system">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <img :src=this.techDetails.img class="planet">
                    <a :href=this.sendMessageLink>{{ this.techDetails.title }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:
            {
                techDetails: {
                    type: Object,
                    default: {},
                },
            },
        computed: {
            sendMessageLink() {
                return '/api/inner/user/solar-system/' + this.techDetails.id + '/send-message?amount=50';
            },
        }
    };
</script>

<style>
    .solar-system .planet {
        width: 24px;
        height: 24px;
        padding-right: 7px;
    }
</style>
