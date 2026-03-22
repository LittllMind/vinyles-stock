<template>
    <div class="card">
        <div class="card-header">
            Test Component Vue.js
        </div>
        <div class="card-body">
            <h5 class="card-title">Bougies-Stock Application</h5>
            <p class="card-text">Composant Vue.js chargé avec succès !</p>
            <button class="btn btn-primary" @click="increment">
                Cliquez moi ({{ count }})
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const count = ref(0);

const increment = () => {
    count.value++;
};
</script>

<style scoped>
.card {
    margin: 20px 0;
}
</style>
