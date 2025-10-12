<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Caja Cerrada" />
    <AuthenticatedLayout>
        <template #header>Gestión de Pedidos</template>
        <div class="text-center p-5">
            <h2>La caja se encuentra cerrada.</h2>
            <p>Para empezar a registrar pedidos, por favor, abre una nueva caja.</p>

            <Link v-if="$page.props.auth.can.openRegister" :href="route('admin.caja.store')" method="post" as="button"
                class="btn btn-primary btn-lg">
            <i class="fas fa-cash-register mr-2"></i>Abrir Caja
            </Link>
        </div>
    </AuthenticatedLayout>
</template>