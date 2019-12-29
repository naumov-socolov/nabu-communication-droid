<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
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
