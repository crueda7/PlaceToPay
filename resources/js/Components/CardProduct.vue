<script setup>
import {useStore} from 'vuex';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const store = useStore();

const props = defineProps({
    product: Object,
});

async function send() {
    let data = {
        product_id: props.product.id,
    };

    store.commit('setLoading', true);

    await axios.post(route('shoppingCarts.store'), data).then((response) => {
        store.commit('setLoading', false);

        Toast.fire({
            icon: response.data.status === 1 ? 'success': 'error',
            title: response.data.message,
        });
    }).catch((error) => {
        store.commit('setLoading', false);

        Toast.fire({
            icon: 'error',
            title: error,
        });
    });
}
</script>

<template>
    <div class="h-full bg-white rounded-lg shadow-md dark:bg-gray-600 dark:border-gray-700">
        <div class="px-5 pb-5">
            <div class="py-2 flex justify-center">
                <img class="mt-2 rounded h-60" :src="product.image" alt="product image">
            </div>

            <h5 class="py-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white" v-html="product.title"></h5>
            <p class="py-2 text-lg font-medium tracking-tight text-gray-400 dark:text-white" v-html="product.description"></p>

            <div class="py-2 flex justify-between items-center">
                <span class="text-3xl font-bold text-gray-900 dark:text-white" v-html="'$'+product.price"></span>
                <PrimaryButton class="ml-4" @click="send()">Add 👀</PrimaryButton>
            </div>
        </div>
    </div>
</template>
