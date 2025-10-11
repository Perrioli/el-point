<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

defineProps({
    pedidosPendientes: Array,
});

let intervalId = null;

// La pantalla se refrescará cada 15 segundos para buscar nuevos pedidos
onMounted(() => {
    intervalId = setInterval(() => {
        router.reload({ preserveState: true, preserveScroll: true });
    }, 15000); // 15 segundos
});

onUnmounted(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <Head title="Pantalla de Barra" />
    <AuthenticatedLayout>
        <template #header>
            Pedidos en Preparación
        </template>

        <div v-if="pedidosPendientes.length === 0" class="text-center p-5">
            <h3 class="text-muted">No hay pedidos pendientes.</h3>
        </div>

        <div v-else class="row">
            <div v-for="pedido in pedidosPendientes" :key="pedido.id_pedido" class="col-md-4">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title font-weight-bold">#{{ pedido.numero_caja }} - {{ pedido.persona }}</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li v-for="producto in pedido.productos" :key="producto.id_producto" class="h5">
                                <strong>{{ producto.pivot.cantidad }}x</strong> {{ producto.nombre }}
                            </li>
                        </ul>
                        <p v-if="pedido.comentarios" class="text-muted border-top pt-2 mt-2">
                            <em>{{ pedido.comentarios }}</em>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <Link 
                            :href="route('admin.pedidos.listo', pedido.id_pedido)" 
                            method="patch" 
                            as="button" 
                            class="btn btn-lg btn-warning"
                        >
                            Marcar como Listo
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>