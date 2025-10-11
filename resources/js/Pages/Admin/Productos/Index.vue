<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    productos: {
        type: Array
    }
});

const deleteProduct = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        router.delete(route('admin.productos.destroy', id));
    }
};
</script>

<template>

    <Head title="Productos" />

    <AuthenticatedLayout>
        <template #header>
            Gestión de Productos
        </template>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Productos</h3>
                <div class="card-tools">
                    <Link :href="route('admin.productos.create')" class="btn btn-primary">Crear Producto</Link>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Precio</th>
                            <th class="text-center" style="width: 150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="productos.length === 0">
                            <td colspan="5" class="text-center">No hay productos registrados.</td>
                        </tr>
                        <tr v-for="producto in productos" :key="producto.id_producto">
                            <td>{{ producto.id_producto }}</td>
                            <td>
                                <img v-if="producto.foto_url" :src="`/storage/${producto.foto_url}`"
                                    :alt="producto.nombre" class="img-thumbnail" width="75">
                                <span v-else>N/A</span>
                            </td>
                            <td>{{ producto.nombre }}</td>
                            <td>${{ producto.precio }}</td>
                            <td class="text-center">
                                <Link :href="route('admin.productos.edit', producto.id_producto)"
                                    class="btn btn-sm btn-info">
                                Editar</Link>
                                <button @click="deleteProduct(producto.id_producto)"
                                    class="btn btn-sm btn-danger ml-1">Eliminar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>