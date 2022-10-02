<script setup>
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {computed, onMounted} from "vue";
import {useStore} from "vuex";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import CardProductDetail from "@/Components/CardProductDetail.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';

const store = useStore();

const props = defineProps({
    order: Object,
    cart: Object,
});

const total = computed(() => {
    let total = 0;

    props.cart.forEach(item => {
        total += item.price;
    })

    return total;
});

const form = useForm({
    customer_name: '',
    customer_email: '',
    customer_mobile: '',
    status: '',
    user_id: '',
    cart: [],
});

onMounted(() => {
    console.log(props.order);
    console.log(props.cart);

    if (Object.keys(props.cart).length === 0) {
        back();
    }
});

async function send() {
    let order = props.order;

    store.commit('setLoading', true);

    await axios.post('/storeOrders', {
        customer_name: order.customer_name,
        customer_email: order.customer_email,
        customer_mobile: form.customer_mobile,
        status: 'PENDING',
        user_id: order.user_id,
        cart: props.cart,
    }).then((response) => {
        store.commit('setLoading', false);
        useForm().get(route('orders.index'));
        window.open(response.data.processUrl, '_blank');
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
}
</script>

<template>
    <Head title="Order" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white dark:bg-gray-600">
                        <h2 class="py-2 text-3xl font-bold tracking-tight text-orange-500">Information
                            order 🤑</h2>

                        <div class="grid grid-cols-1 gap-8 p-6 bg-white dark:bg-gray-800 rounded border-gray-200">
                            <form class="p-6 dark:bg-gray-600 rounded" @submit.prevent="send()">
                                <div>
                                    <InputLabel class="dark:text-gray-800" for="name" value="Name"/>
                                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.customer_name" :model-value="order.customer_name" required autofocus autocomplete="name"/>
                                    <InputError class="mt-2" :message="form.errors.customer_name"/>
                                </div>

                                <div class="mt-4">
                                    <InputLabel class="dark:text-gray-800" for="email" value="Email"/>
                                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.customer_email" :model-value="order.customer_email" required autocomplete="username"/>
                                    <InputError class="mt-2" :message="form.errors.customer_email"/>
                                </div>

                                <div class="mt-4">
                                    <InputLabel class="dark:text-gray-800" for="mobile" value="Mobile"/>
                                    <TextInput id="mobile" type="text" class="mt-1 block w-full" v-model="form.customer_mobile" required autofocus autocomplete="name"/>
                                    <InputError class="mt-2" :message="form.errors.customer_mobile"/>
                                </div>

                                <h2 class="pt-6 text-2xl font-bold tracking-tight text-gray-500 dark:text-white">Detail order 🛒</h2>

                                <div class="grid grid-cols-1 gap-8 p-6 bg-white dark:bg-gray-600 rounded border-gray-200">
                                    <template v-for="(product, index) in cart" :key="'product'+index">
                                        <CardProductDetail :product="product" :showButton="false"></CardProductDetail>
                                    </template>

                                    <div class="py-2 flex justify-end items-center">
                                        <span class="text-3xl font-bold text-gray-900 dark:text-white">
                                            Total:
                                            <span class="text-3xl font-bold text-orange-500" v-html="'$'+total"></span>
                                        </span>
                                    </div>

                                    <div class="py-2 flex justify-end items-center">
                                        <PrimaryButton class="ml-4 h-14">Pay ❤</PrimaryButton>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-8 p-6">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
