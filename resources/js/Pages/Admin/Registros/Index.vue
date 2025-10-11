<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    registros: {
        type: Object
    },
    filters: {
        type: Object
    }
});

const searchDate = ref(props.filters?.date || '');

const search = () => {
    router.get(route('admin.registros.index'), { date: searchDate.value }, {
        preserveState: true,
        replace: true,
    });
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleString();
}
</script>

<template>
    <Head title="Registros de Caja" />

    <AuthenticatedLayout>
        <template #header>Historial de Cajas Cerradas</template>

        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <div class="input-group">
                        <input type="date" class="form-control" v-model="searchDate">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Buscar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th># Caja</th>
                            <th>Fecha Apertura</th>
                            <th>Fecha Cierre</th>
                            <th>Total Vendido</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="registros.data.length === 0">
                            <td colspan="5" class="text-center">No se encontraron registros.</td>
                        </tr>
                        <tr v-for="registro in registros.data" :key="registro.id">
                            <td>{{ registro.id }}</td>
                            <td>{{ formatDate(registro.fecha_apertura) }}</td>
                            <td>{{ formatDate(registro.fecha_cierre) }}</td>
                            <td>${{ parseFloat(registro.pedidos_sum_precio_total || 0).toFixed(2) }}</td>
                            <td>
                                <Link :href="route('admin.registros.show', registro.id)" class="btn btn-sm btn-info">
                                    Ver Detalles
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                 <Pagination :links="registros.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>