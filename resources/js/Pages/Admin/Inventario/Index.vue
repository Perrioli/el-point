<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    items: {
        type: Array
    }
});

const deleteItem = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este ítem?')) {
        router.delete(route('admin.inventario.destroy', id));
    }
};
</script>

<template>

    <Head title="Inventario" />

    <AuthenticatedLayout>
        <template #header>Gestión de Inventario</template>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Ítems</h3>
                <div class="card-tools">
                    <Link :href="route('admin.inventario.create')" class="btn btn-primary">Añadir Ítem</Link>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Ítem (Ingrediente/Producto)</th>
                            <th>Cantidad</th>
                            <th>Costo</th>
                            <th class="text-center" style="width: 150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="items.length === 0">
                            <td colspan="5" class="text-center">No hay ítems registrados en el inventario.</td>
                        </tr>
                        <tr v-for="item in items" :key="item.id_stock">
                            <td>{{ item.id_stock }}</td>
                            <td>{{ item.producto_stock }}</td>
                            <td>{{ item.cantidad }}</td>
                            <td>${{ item.costo }}</td>
                            <td class="text-center">
                                <Link :href="route('admin.inventario.edit', item.id_stock)" class="btn btn-sm btn-info">
                                Editar
                                </Link>
                                <button @click="deleteItem(item.id_stock)"
                                    class="btn btn-sm btn-danger ml-1">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>