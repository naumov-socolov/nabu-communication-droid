<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body" v-for="solarSystem in this.solarSystems">
                        <SolarSystem
                            :techDetails="solarSystem"
                        >
                        </SolarSystem>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import SolarSystem from '../components/SolarSystem.vue';

    export default {
        components: {
            SolarSystem,
        },
        data() {
            return {
                solarSystems: [],
            };
        },
        beforeMount() {
            this.getSolarSystems();
        },
        methods: {
            getSolarSystems() {
                axios
                    .get('/api/inner/user/solar-system/index')
                    .then(response => {
                        this.solarSystems = response.data.data;
                    });
            },
        },
    };
</script>
