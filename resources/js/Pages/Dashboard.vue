<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps, computed, ref, onMounted } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

const props = defineProps({
    totalFacturado: Number,
    productosMasVendidos: Array,
    pedidosPendientes: Number,
    pedidosListos: Number,
    cajaActualTotal: Number,
    currentFilter: String,
});

const showMoney = ref(true);

onMounted(() => {
    const savedPreference = localStorage.getItem('dashboard_show_money');
    if (savedPreference !== null) {
        showMoney.value = savedPreference === 'true';
    }
});

const toggleMoney = () => {
    showMoney.value = !showMoney.value;
    localStorage.setItem('dashboard_show_money', showMoney.value);
};

const topProductsOptions = computed(() => ({
    chart: { type: 'bar', toolbar: { show: false } },
    xaxis: { categories: props.productosMasVendidos.map(p => p.nombre) },
    colors: ['#007bff'],
    plotOptions: { bar: { borderRadius: 4, horizontal: true } },
    title: { text: 'Productos Más Vendidos', align: 'left' },
}));
const topProductsSeries = computed(() => [{
    name: 'Cantidad Vendida',
    data: props.productosMasVendidos.map(p => p.total_vendido)
}]);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            Dashboard
        </template>

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ pedidosPendientes }}</h3>
                        <p>Pedidos Pendientes</p>
                    </div>
                    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                    <Link :href="route('admin.pedidos.index')" class="small-box-footer">Ir a Pedidos <i class="fas fa-arrow-circle-right"></i></Link>
                </div>
            </div>
             <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ pedidosListos }}</h3>
                        <p>Pedidos Listos</p>
                    </div>
                    <div class="icon"><i class="fas fa-bell"></i></div>
                    <Link :href="route('admin.pedidos.index')" class="small-box-footer">Ir a Pedidos <i class="fas fa-arrow-circle-right"></i></Link>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><i class="fas fa-tv"></i></h3>
                        <p>Pantalla Pública</p>
                    </div>
                    <div class="icon"><i class="fas fa-desktop"></i></div>
                    <a :href="route('pantalla.index')" target="_blank" class="small-box-footer">Abrir Pantalla <i class="fas fa-external-link-alt"></i></a>
                </div>
            </div>
             <div class="col-lg-3 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3><i class="fas fa-boxes"></i></h3>
                        <p>Inventario</p>
                    </div>
                    <div class="icon"><i class="fas fa-dolly"></i></div>
                    <Link :href="route('admin.inventario.index')" class="small-box-footer">Gestionar <i class="fas fa-arrow-circle-right"></i></Link>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Productos Más Vendidos</h3>
                    </div>
                    <div class="card-body">
                        <VueApexCharts height="350" :options="topProductsOptions" :series="topProductsSeries" />
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Total Facturado</h3>
                             <div class="card-tools">
                                <div class="btn-group">
                                    <Link :href="route('dashboard', { filter: 'daily' })" class="btn btn-xs" :class="{'btn-primary': currentFilter === 'daily', 'btn-default': currentFilter !== 'daily'}">Día</Link>
                                    <Link :href="route('dashboard', { filter: 'weekly' })" class="btn btn-xs" :class="{'btn-primary': currentFilter === 'weekly', 'btn-default': currentFilter !== 'weekly'}">Sem</Link>
                                    <Link :href="route('dashboard', { filter: 'monthly' })" class="btn btn-xs" :class="{'btn-primary': currentFilter === 'monthly', 'btn-default': currentFilter !== 'monthly'}">Mes</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center pt-0">
                        <div class="d-flex justify-content-center align-items-center">
                            <h1 class="display-4 font-weight-bold mb-0">
                                <span v-if="showMoney">${{ totalFacturado.toFixed(2) }}</span>
                                <span v-else>****</span>
                            </h1>
                            <button @click="toggleMoney" class="btn btn-link text-muted ml-3" style="font-size: 1.2rem;">
                                <i class="fas" :class="showMoney ? 'fa-eye' : 'fa-eye-slash'"></i>
                            </button>
                        </div>
                        <p class="text-muted mt-2">Según el filtro seleccionado</p>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header border-0">
                        <h3 class="card-title">Caja Actual (En Vivo)</h3>
                    </div>
                    <div class="card-body text-center pt-0">
                        <div class="d-flex justify-content-center align-items-center">
                            <h1 class="display-4 font-weight-bold text-success mb-0">
                                <span v-if="showMoney">${{ cajaActualTotal.toFixed(2) }}</span>
                                <span v-else>****</span>
                            </h1>
                            <button @click="toggleMoney" class="btn btn-link text-success ml-3" style="font-size: 1.2rem;">
                                <i class="fas" :class="showMoney ? 'fa-eye' : 'fa-eye-slash'"></i>
                            </button>
                        </div>
                        <p class="text-muted mt-2">Ventas de la sesión actual</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>