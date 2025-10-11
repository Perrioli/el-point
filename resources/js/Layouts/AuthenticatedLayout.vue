<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    title: String,
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>

    <Head :title="title" />

    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        {{ $page.props.auth.user.nombre_completo }} <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header">Opciones</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" @click.prevent="logout" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <Link :href="route('dashboard')" class="brand-link">
            <span class="brand-text font-weight-light">El Point - Admin</span>
            </Link>

            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <Link :href="route('dashboard')" class="nav-link"
                                :class="{ 'active': $page.url === '/dashboard' }">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('admin.productos.index')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/admin/productos') }">
                            <i class="nav-icon fas fa-wine-bottle"></i>
                            <p>Productos</p>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('admin.inventario.index')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/admin/inventario') }">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>Inventario</p>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('admin.pedidos.index')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/admin/pedidos') }">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Pedidos</p>
                            </Link>
                        </li>
                        <li class="nav-item">
                            <Link :href="route('admin.registros.index')" class="nav-link"
                                :class="{ 'active': $page.url.startsWith('/admin/registros') }">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Registros</p>
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div v-if="$page.props.flash && $page.props.flash.success" class="container-fluid mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $page.props.flash.success }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <slot name="header" />
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    <slot />
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <strong>Copyright &copy; 2025-{{ new Date().getFullYear() }} <a href="#">El Point</a>.</strong>
            Todos los derechos reservados.
        </footer>
    </div>
</template>