<script setup>
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {computed} from "vue";
import {useStore} from 'vuex';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CardProductDetail from "@/Components/CardProductDetail.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const store = useStore();

const props = defineProps({
    shoppingCarts: Array,
});

const total = computed(() => {
    let total = 0;

    props.shoppingCarts.forEach(item => {
        total += item.price;
    })

    return total;
})

async function send() {
    useForm().get(route('orders.create'));
}

function back() {
    useForm().get(route('products.index'));
}
</script>

<template>
    <Head title="ShopingCart" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-600 rounded">
                        <h2 class="py-2 text-3xl font-bold tracking-tight text-orange-500">Your product bag 🛍</h2>

                        <div class="grid grid-cols-1 gap-8 p-6 bg-white dark:bg-gray-800 rounded border-gray-200">
                            <template v-if="shoppingCarts.length > 0">
                                <div class="grid grid-cols-1 gap-8 p-6 bg-white dark:bg-gray-600 rounded">
                                    <template v-for="(product, index) in shoppingCarts" :key="'product'+index">
                                        <CardProductDetail :product="product" :showButton="true" />
                                    </template>

                                    <div class="grid grid-cols-1 gap-8 p-6">
                                        <div class="py-2 flex justify-end items-center">
                                        <span class="text-3xl font-bold text-gray-900 dark:text-white">
                                            Total:
                                            <span class="text-3xl font-bold text-orange-500" v-html="'$'+total"></span>
                                        </span>
                                        </div>

                                        <div class="py-2 flex justify-end items-center">
                                            <PrimaryButton class="ml-4 h-14" @click="send()">Continue 🔐</PrimaryButton>
                                        </div>
                                    </div>
                                </div>


                            </template>

                            <template v-else>
                                <div class="grid grid-cols-1 gap-8 p-6 bg-white dark:bg-gray-600 rounded">
                                    <div class="bg-white dark:bg-gray-600 max-w-sm text-center mx-auto">
                                        <div class="p-6">
                                            <h5 class="text-gray-900 text-xl font-medium mb-2">Empty shopping cart 😢</h5>
                                            <p class="text-gray-700 text-base mb-4 dark:text-gray-800">Add new products to your shopping cart</p>
                                            <PrimaryButton class="ml-4" @click="back()">Let´s go shopping 🎁</PrimaryButton>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
