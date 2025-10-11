<script setup>
// 1. Cambiamos el layout que importamos
import ScreenLayout from '@/Layouts/ScreenLayout.vue';
import { onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

defineProps({
    pedidosPendientes: Array,
    pedidosListos: Array,
});

let intervalId = null;

onMounted(() => {
    intervalId = setInterval(() => {
        router.reload({ preserveState: true, preserveScroll: true });
    }, 10000);
});

onUnmounted(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <ScreenLayout>
        <div class="row h-100">
            <div class="col-6 d-flex flex-column">
                <div class="card bg-warning h-100">
                    <div class="card-header text-center">
                        <h2 class="card-title text-dark font-weight-bold">EN PREPARACIÓN</h2>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                        <div v-if="pedidosPendientes.length === 0" class="text-center h3 text-dark">
                            -
                        </div>
                        <div v-for="pedido in pedidosPendientes" :key="pedido.id_pedido" class="text-center mb-3">
                            <h1 class="display-3 font-weight-bold text-dark">{{ pedido.persona }} #{{ pedido.numero_caja }}</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 d-flex flex-column">
                 <div class="card bg-success h-100">
                    <div class="card-header text-center">
                        <h2 class="card-title text-white font-weight-bold">LISTO PARA RETIRAR</h2>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center">
                         <div v-if="pedidosListos.length === 0" class="text-center h3">
                            -
                        </div>
                        <div v-for="pedido in pedidosListos" :key="pedido.id_pedido" class="text-center mb-3">
                             <h1 class="display-3 font-weight-bold">{{ pedido.persona }} #{{ pedido.numero_caja }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ScreenLayout>
</template>