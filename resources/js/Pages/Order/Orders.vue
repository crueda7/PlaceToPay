<script setup>
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {useStore} from "vuex";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {formatDate} from 'human-pretty-date';

const store = useStore();

const props = defineProps({
    orders: Object,
});


function calculateTotal(details) {
    let total = 0;

    details.forEach((detail) => {
        total += detail.product.price;
    });

    return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(total)
}

async function retryPayment(orderId) {
    store.commit('setLoading', true);

    await axios.post(route('orders.retry'), {
        order_id: orderId,
    }).then((response) => {
        store.commit('setLoading', false);
        if (response.data.status === 2) {
            Toast.fire({
                icon: 'error',
                title: response.data.message,
            });
            return
        }
        window.location.replace(`${route('orders.index')}#${orderId}`)
    }).catch((error) => {
        store.commit('setLoading', false);

        Toast.fire({
            icon: 'error',
            title: error,
        });
    });
}

async function tryAgainPayment(orderId) {
    store.commit('setLoading', true);

    await axios.post(route('orders.try'), {
        order_id: orderId,
    }).then((response) => {
        store.commit('setLoading', false);
        if (response.data.status === 2) {
            Toast.fire({
                icon: 'error',
                title: response.data.message,
            });
            return
        }
        if (response.data.processUrl.length > 10)
            window.location.replace(response.data.processUrl)
    }).catch((error) => {
        store.commit('setLoading', false);
        Toast.fire({
            icon: 'error',
            title: error,
        });
    });
}

function back() {
    useForm().get(route('products.index'));
};
</script>

<template>
    <Head title="Orders"/>

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-600">
                        <h2 class="py-2 text-3xl font-bold tracking-tight text-orange-500">Orders 🤩</h2>

                        <div class="grid grid-cols-1 gap-8 p-6 bg-white dark:bg-gray-800 rounded border-gray-200">
                            <template v-if="orders.length > 0">
                                <div class="p-6overflow-x-auto relative shadow-md sm:rounded-lg">
                                    <table
                                        class="w-full text-sm text-left text-gray-500 dark:bg-gray-600 dark:text-gray-400 rounded">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="py-3 px-6" scope="col">No°</th>
                                            <th class="py-3 px-6" scope="col">Detail</th>
                                            <th class="py-3 px-6" scope="col">Total</th>
                                            <th class="py-3 px-4" scope="col">Status</th>
                                            <th class="py-3 px-6" scope="col">Process ID</th>
                                            <th class="py-3 px-6" scope="col">Date</th>
                                            <th class="py-3 px-6" scope="col"></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <template v-for="(order, index) in orders" :key="'order-'+index">
                                            <tr class="border-b" :id="order.id">
                                                <td class="py-4 px-6" v-html="order.id"></td>
                                                <td class="py-4 px-6">
                                                    <ul class="space-y-1 max-w-md list-disc list-inside text-gray-500 dark:text-gray-400">
                                                        <li v-for="(detail, index) in order.order_details"
                                                            :key="detail.id">
                                                            {{ detail.product.title }} - Quantity
                                                            ({{ detail.quantity }})
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td class="py-4 px-6"
                                                    v-html="calculateTotal(order.order_details)"></td>
                                                <td class="py-4 px-6">
                                                    <p>
                                                        <span
                                                            v-if="order.status === 'ERROR' || order.status === 'REJECTED'"
                                                            class="bg-red-100 text-red-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-red-200 dark:text-red-900"
                                                            v-html="order.status"></span>
                                                        <span
                                                            v-if="order.status === 'APPROVED' "
                                                            class="bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900"
                                                            v-html="order.status"></span>
                                                        <span
                                                            v-if="order.status === 'OK'"
                                                            class="bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">APPROVED</span>
                                                        <span
                                                            v-if="order.status === 'PENDING'"
                                                            class="bg-yellow-100 text-yellow-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-200 dark:text-yellow-900"
                                                            v-html="order.status"></span>
                                                    </p>
                                                </td>
                                                <td class="py-4 px-6" v-html="order.requestId"></td>
                                                <td class="py-4 px-6"
                                                    v-html="formatDate(new Date(order.created_at))"></td>
                                                <td v-if="order.status === 'PENDING'" class="py-4 px-6">
                                                    <div class="py-2 flex justify-end items-center">
                                                        <PrimaryButton class="ml-4" @click="retryPayment(order.id)">
                                                            Retry 🙏
                                                        </PrimaryButton>
                                                    </div>
                                                </td>
                                                <td v-if="order.status === 'REJECTED' || order.status === 'ERROR'"
                                                    class="py-4 px-6">
                                                    <div class="py-2 flex justify-end items-center">
                                                        <PrimaryButton class="ml-4" @click="tryAgainPayment(order.id)">
                                                            Try Again 😄
                                                        </PrimaryButton>
                                                    </div>
                                                </td>

                                            </tr>
                                        </template>
                                        </tbody>
                                    </table>
                                </div>
                            </template>

                            <template v-else>
                                <div class="grid grid-cols-1 gap-8 p-6 bg-white dark:bg-gray-600 rounded">
                                    <div class="bg-white dark:bg-gray-600 max-w-sm text-center mx-auto">
                                        <div class="p-6">
                                            <h5 class="text-gray-900 text-xl font-medium mb-2">No orders have been
                                                placed 😢</h5>
                                            <p class="text-gray-700 text-base mb-4">Add new products to your shopping
                                                cart</p>
                                            <PrimaryButton class="ml-4" @click="back()">Let´s go shopping 🎁
                                            </PrimaryButton>
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
