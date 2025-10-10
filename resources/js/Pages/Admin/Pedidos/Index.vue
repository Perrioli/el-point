<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import PedidoCard from '@/Pages/Admin/Pedidos/Partials/PedidoCard.vue';

const props = defineProps({
    pedidos: { type: Array },
    caja: { type: Object }
});

const activeTab = ref('pendientes');

const pedidosPendientes = computed(() => props.pedidos.filter(p => p.status === 'pendiente'));
const pedidosListos = computed(() => props.pedidos.filter(p => p.status === 'listo'));
const pedidosEntregados = computed(() => props.pedidos.filter(p => p.status === 'entregado'));

const deletePedido = (id) => {
    if (confirm('¿Estás seguro de que deseas cancelar este pedido?')) {
        router.delete(route('admin.pedidos.destroy', id));
    }
};

</script>

<template>

    <Head title="Pedidos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between align-items-center">
                <span>Gestión de Pedidos (Caja Abierta)</span>
                <div>
                    <Link :href="route('admin.pedidos.create')" class="btn btn-primary mr-2">Registrar Pedido</Link>
                    <Link :href="route('admin.caja.update', caja.id)" method="patch" as="button" class="btn btn-danger">
                    Cerrar Caja</Link>
                </div>
            </div>
        </template>

        <div class="card">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link" :class="{ 'active': activeTab === 'pendientes' }"
                            @click.prevent="activeTab = 'pendientes'" href="#">
                            Pendientes <span class="badge badge-warning">{{ pedidosPendientes.length }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ 'active': activeTab === 'listos' }"
                            @click.prevent="activeTab = 'listos'" href="#">
                            Listos para Retirar <span class="badge badge-info">{{ pedidosListos.length }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" :class="{ 'active': activeTab === 'entregados' }"
                            @click.prevent="activeTab = 'entregados'" href="#">
                            Entregados <span class="badge badge-success">{{ pedidosEntregados.length }}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div v-if="activeTab === 'pendientes'">
                    <p v-if="pedidosPendientes.length === 0" class="text-center text-muted">No hay pedidos pendientes.
                    </p>
                    <PedidoCard v-for="pedido in pedidosPendientes" :key="pedido.id_pedido" :pedido="pedido" />
                </div>

                <div v-if="activeTab === 'listos'">
                    <p v-if="pedidosListos.length === 0" class="text-center text-muted">No hay pedidos listos para
                        retirar.</p>
                    <PedidoCard v-for="pedido in pedidosListos" :key="pedido.id_pedido" :pedido="pedido" />
                </div>

                <div v-if="activeTab === 'entregados'">
                    <p v-if="pedidosEntregados.length === 0" class="text-center text-muted">No hay pedidos entregados.
                    </p>
                    <PedidoCard v-for="pedido in pedidosEntregados" :key="pedido.id_pedido" :pedido="pedido" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>