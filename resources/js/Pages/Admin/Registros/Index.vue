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
    if (!dateString) return 'N/A';
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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th># Caja</th>
                            <th>Fecha Apertura</th>
                            <th>Fecha Cierre</th>
                            <th>Efectivo</th>
                            <th>Transferencia</th>
                            <th>Total</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="registro in registros.data" :key="registro.id">
                            <td>{{ registro.id }}</td>
                            <td>{{ formatDate(registro.fecha_apertura) }}<br><small class="text-muted">{{
                                    registro.opened_by?.nombre_completo }}</small></td>
                            <td>{{ formatDate(registro.fecha_cierre) }}<br><small class="text-muted">{{
                                    registro.closed_by?.nombre_completo }}</small></td>

                            <td class="text-success font-weight-bold">
                                ${{ parseFloat(registro.total_efectivo || 0).toFixed(2) }}
                            </td>

                            <td class="text-primary font-weight-bold">
                                ${{ parseFloat(registro.total_transferencia || 0).toFixed(2) }}
                            </td>

                            <td>${{ parseFloat(registro.total_general || 0).toFixed(2) }}</td>

                            <td class="text-center">
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