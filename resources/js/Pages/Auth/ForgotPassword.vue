<script>
import Layout from "@/Layouts/AuthLayout.vue";

export default {
    // Using shorthand syntax...
    layout: Layout,
};
</script>
<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import VAlert from "@/Shared/VAlert";

const props = defineProps({
    url_submit: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(props.url_submit, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head>
        <title>Forgot Password</title>
        <meta name="description" content="Forgot Password" />
    </Head>

    <form @submit.prevent="submit" method="post">
        <h3 class="text-center mb-3">Forgot Password</h3>

        <VAlert />

        <div class="form-group mb-3">
            <input
                type="email"
                name="email"
                v-model="form.email"
                class="form-control"
                placeholder="Email"
            />
        </div>

        <div class="d-grid">
            <button
                class="btn btn-success btn-login"
                type="submit"
                :disabled="form.processing"
            >
                Reset Password
                <div
                    class="spinner-border spinner-border-sm ms-2"
                    role="status"
                    v-if="form.processing"
                >
                    <span class="visually-hidden">Loading...</span>
                </div>
            </button>
        </div>
        <hr class="my-4" />
        <div class="d-grid mb-2">
            <Link class="btn text-uppercase fw-bold" href="/login">
                <i class="fas fa-key me-2"></i> Back to Login Page
            </Link>
        </div>
    </form>
</template>

<style></style>
