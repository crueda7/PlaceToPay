<script setup>
import {useStore} from "vuex";
import {useForm} from "@inertiajs/inertia-vue3";
import Swal from "sweetalert2";
import DangerButton from '@/Components/DangerButton.vue';

const store = useStore();

const props = defineProps({
    product: Object,
    showButton: Boolean,
});

function confirm() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b22937',
        cancelButtonColor: '#808080',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            this.remove();
        }
    })
}

async function remove() {
    let data = {
        id: props.product.cart_id,
    };

    store.commit('setLoading', true);

    await axios.delete(route('shoppingCarts.destroy', data)).then((response) => {
        store.commit('setLoading', false);

        response.data.status == 1 ?  useForm().get(route('shoppingCarts.index')) : '';

        Toast.fire({
            icon: response.data.status == 1 ? 'success' : 'error',
            title: response.data.message,
        })
    }).catch((error) => {
        store.commit('setLoading', false);

        Toast.fire({
            icon: 'error',
            title: error,
        });
    });
}

function back() {
    redirect(route('shoppingCarts.index'));
}
</script>

<template>
    <div class="flex h-auto flex-col md:flex-row rounded-lg bg-white dark:bg-gray-800 shadow-lg my-4 p-4">
        <img class="w-full object-cover md:w-48 rounded-t-lg md:rounded-none md:rounded-l-lg" :src="product.image" alt=""/>

        <div class="w-full p-6 flex flex-col justify-start">
            <div class="py-2 flex justify-between items-center">
                <h5 class="py-2 text-xl font-semibold tracking-tight text-gray-900 dark:text-white"
                    v-html="product.title"></h5>

                <template v-if="showButton">
                    <DangerButton class="ml-4" @click="confirm()">Remove 😥</DangerButton>
                </template>
            </div>

            <p class="py-2 text-lg font-medium tracking-tight text-gray-400 dark:text-white"
               v-html="product.description"></p>

            <div class="py-2 flex justify-end items-center">
                <span class="text-2xl font-semibold text-gray-900 dark:text-white" v-html="new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(product.price)"></span>
            </div>
        </div>
    </div>
</template>
