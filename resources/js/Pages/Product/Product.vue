<script setup>
import {Head} from '@inertiajs/inertia-vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CardProduct from "@/Components/CardProduct.vue";

const props = defineProps({
    products: Array,
});
</script>

<template>
    <Head title="Product" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-600 rounded">
                        <h2 class="py-2 text-3xl font-bold tracking-tight text-orange-500">List of products 😎</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-6 bg-white dark:bg-gray-800 rounded border-b border-gray-200">
                            <template v-for="(product, index) in products" :key="'product-'+index">
                                <CardProduct :product="product" />
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
