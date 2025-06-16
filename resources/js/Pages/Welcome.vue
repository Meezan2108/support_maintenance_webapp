<script>
import Layout from "@/Layouts/BlankLayout.vue";

export default {
    // Using shorthand syntax...
    layout: Layout,
};
</script>
<script setup>
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head>
        <title>Welcome</title>
        <meta name="description" content="Welcome to Lembaga Koko Malaysia" />
    </Head>

    <div
        class="container-fluid min-vh-100 d-flex flex-column align-items-center justify-content-center"
    >
        <div class="text-center">
            <img src="/assets/images/st-adv-logo.png" class="mb-3" />
            <div class="d-flex flex-column">
                <a
                    :href="'/auth_callback?employeeID=' + 1"
                    class="btn btn-primary mb-1"
                >
                    Login as Super Admin
                </a>

                <a
                    :href="'/auth_callback?employeeID=' + 2"
                    class="btn btn-primary mb-1"
                >
                    Login as LKM Director
                </a>

                <a
                    :href="'/auth_callback?employeeID=' + 3"
                    class="btn btn-primary mb-1"
                >
                    Login as R&D Coordinator
                </a>

                <a
                    :href="'/auth_callback?employeeID=' + 4"
                    class="btn btn-primary mb-1"
                >
                    Login as Division Director
                </a>

                <a
                    :href="'/auth_callback?employeeID=' + 5"
                    class="btn btn-primary mb-1"
                >
                    Login as Public
                </a>
            </div>
        </div>
    </div>
</template>

<style>
img{
    width: 300px;
}
</style>
