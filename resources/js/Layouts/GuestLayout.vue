<script setup>
import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Loading />

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-800">
        <div>
            <Link href="/">
                <img src="https://static.placetopay.com/placetopay-logo.svg" class="h-10" alt="Evertec Logo">
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-500 shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
