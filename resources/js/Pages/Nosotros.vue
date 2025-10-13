<script setup>
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Sobre Nosotros" />
    <PublicLayout>
        <div class="container text-white">
            <h1 class="text-center display-4 my-4">Seccion en desarrollo</h1>
            <div class="row align-items-center">
                <div class="col-md-6 mb-4">
                    <img src="/images/equipo.jpg" class="img-fluid rounded" alt="El equipo de El Point">
                </div>
                <div class="col-md-6">
                    <p class="lead">
                        Aquí va la historia del bar. Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                        Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, 
                        ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, 
                        varius a, semper congue, euismod non, mi.
                    </p>
                    <p>
                        Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum 
                        diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, 
                        consequat in, pretium a, enim. Pellentesque congue.
                    </p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>