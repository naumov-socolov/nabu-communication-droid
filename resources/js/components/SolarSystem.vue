<template>
    <div class="solar-system">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <img :src=this.techDetails.img class="planet">
                    <a :href=this.sendMessageLink class="name">{{ this.techDetails.title }}</a> ::
                    <template v-for="response in this.solarSystemResponses">
                        <Message :response="response"></Message>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Message from '../components/Message.vue';

    export default {
        components: {
            Message,
        },
        props:
            {
                techDetails: {
                    type: Object,
                    default: {},
                },
            },
        data() {
            return {
                solarSystemResponses: [],
            };
        },
        mounted() {
            this.listenToDistantSolarSystem();
        },
        computed: {
            sendMessageLink() {
                return '/api/inner/user/solar-system/' + this.techDetails.id + '/send-message?amount=50';
            },
        },
        methods: {
            listenToDistantSolarSystem() {
                const SolarSystem = this;

                var channel = window.Echo.channel('my-channel');
                channel.listen('.my-event', function (data) {
                    console.log(SolarSystem.solarSystemResponses);
                    SolarSystem.solarSystemResponses.push(data);
                    console.log(JSON.stringify(data));
                });
            },
        },
    };
</script>

<style>
    .solar-system {
        width: 300px;
        text-align: left;
    }

    .card {
        height: 30px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: .1rem;
    }

    .planet {
        width: 32px;
        height: 32px;
        padding-right: 7px;
        vertical-align: middle
    }

    .name {
        color: #00788c;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        padding-right: 30px;
    }
</style>
