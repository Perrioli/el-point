<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    nombre_completo: '',
    username: '',
    email: '',
    rol: 'Empleado', // Rol por defecto
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admin.usuarios.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Crear Usuario" />
    <AuthenticatedLayout>
        <template #header>Crear Nuevo Usuario</template>
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Datos del Usuario</h3></div>
            <form @submit.prevent="submit">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nombre Completo</label>
                        <input type="text" v-model="form.nombre_completo" class="form-control" required>
                        <div v-if="form.errors.nombre_completo" class="text-danger">{{ form.errors.nombre_completo }}</div>
                    </div>
                    <div class="form-group">
                        <label>Nombre de Usuario</label>
                        <input type="text" v-model="form.username" class="form-control" required>
                        <div v-if="form.errors.username" class="text-danger">{{ form.errors.username }}</div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" v-model="form.email" class="form-control" required>
                        <div v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</div>
                    </div>
                    <div class="form-group">
                        <label>Rol</label>
                        <select v-model="form.rol" class="form-control">
                            <option value="Empleado">Empleado</option>
                            <option value="Cajero">Cajero</option>
                            <option value="Administrador">Administrador</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" v-model="form.password" class="form-control" required>
                        <div v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</div>
                    </div>
                    <div class="form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" v-model="form.password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Guardar</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>