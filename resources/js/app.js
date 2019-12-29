import Vue from 'vue';
import SolarSystems from './components/SolarSystems.vue';

window.addEventListener("DOMContentLoaded", function (event) {
    new Vue({
        el: '#app',
        components: {SolarSystems},
    });
});
