<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PedidoCard from '@/Pages/Admin/Pedidos/Partials/PedidoCard.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    registro: {
        type: Object,
        required: true,
    }
});

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString();
}
</script>

<template>
    <Head :title="`Detalle de Caja #${registro.id}`" />

    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between align-items-center">
                <span>Detalle de Caja #{{ registro.id }}</span>
                 <Link :href="route('admin.registros.index')" class="btn btn-secondary">Volver a Registros</Link>
            </div>
        </template>

        <div class="card mb-4">
            <div class="card-header"><h3 class="card-title">Resumen de la Jornada</h3></div>
            <div class="card-body">
                <p><strong>Apertura:</strong> {{ formatDate(registro.fecha_apertura) }}</p>
                <p><strong>Cierre:</strong> {{ formatDate(registro.fecha_cierre) }}</p>
                <p class="h4"><strong>Total Vendido:</strong> ${{ parseFloat(registro.pedidos.reduce((sum, p) => sum + parseFloat(p.precio_total), 0)).toFixed(2) }}</p>
            </div>
        </div>

        <h4 class="mb-3">Pedidos de esta jornada ({{ registro.pedidos.length }})</h4>
        <div v-if="registro.pedidos.length > 0">
            <PedidoCard v-for="pedido in registro.pedidos" :key="pedido.id_pedido" :pedido="pedido" />
        </div>
         <p v-else class="text-muted">No se realizaron pedidos en esta jornada.</p>

    </AuthenticatedLayout>
</template>