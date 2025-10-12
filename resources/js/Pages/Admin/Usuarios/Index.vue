<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    usuarios: Array,
});

const deleteUser = (id) => {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        router.delete(route('admin.usuarios.destroy', id));
    }
};
</script>

<template>
    <Head title="Gestión de Usuarios" />

    <AuthenticatedLayout>
        <template #header>Gestión de Usuarios</template>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Listado de Usuarios</h3>
                <div class="card-tools">
                    <Link :href="route('admin.usuarios.create')" class="btn btn-primary">Crear Usuario</Link>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Completo</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="usuario in usuarios" :key="usuario.id">
                                <td>{{ usuario.id }}</td>
                                <td>{{ usuario.nombre_completo }}</td>
                                <td>{{ usuario.username }}</td>
                                <td>{{ usuario.email }}</td>
                                <td><span class="badge badge-info">{{ usuario.rol }}</span></td>
                                <td class="text-center">
                                    <Link :href="route('admin.usuarios.edit', usuario.id)" class="btn btn-sm btn-info">Editar</Link>
                                    <button v-if="$page.props.auth.user.id !== usuario.id" @click="deleteUser(usuario.id)" class="btn btn-sm btn-danger ml-1">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>