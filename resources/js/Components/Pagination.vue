<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <nav v-if="links.length > 3">
        <ul class="pagination">
            <li
                v-for="(link, key) in links"
                :key="key"
                class="page-item"
                :class="{ 'active': link.active, 'disabled': !link.url }"
            >
                <Link
                    class="page-link"
                    :href="link.url || '#'"
                >
                    <span v-html="link.label"></span>
                </Link>
            </li>
        </ul>
    </nav>
</template>