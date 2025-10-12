<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    usuario: Object,
});

const form = useForm({
    nombre_completo: props.usuario.nombre_completo,
    username: props.usuario.username,
    email: props.usuario.email,
    rol: props.usuario.rol,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('admin.usuarios.update', props.usuario.id));
};
</script>
<template>
    <Head title="Editar Usuario" />
    <AuthenticatedLayout>
        <template #header>Editar Usuario: {{ usuario.nombre_completo }}</template>
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Datos del Usuario</h3></div>
            <form @submit.prevent="submit">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nueva Contraseña (opcional)</label>
                        <input type="password" v-model="form.password" class="form-control">
                        <div v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</div>
                    </div>
                     <div class="form-group">
                        <label>Confirmar Nueva Contraseña</label>
                        <input type="password" v-model="form.password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" :disabled="form.processing">Actualizar</button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>