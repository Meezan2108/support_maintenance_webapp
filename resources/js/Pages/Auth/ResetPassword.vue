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
    email: String,
    token: String,
    url_submit: String,
});

const showPassword = ref(false);
const form = useForm({
    email: props.email,
    token: props.token,
    password: "",
    password_confirmation: "",
});

const tooglePassword = function (event) {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    form.post(props.url_submit, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head>
        <title>Login</title>
        <meta name="description" content="Welcome to Lembaga Koko Malaysia" />
    </Head>

    <form @submit.prevent="submit">
        <h3 class="text-center mb-3">Reset Password</h3>

        <VAlert />

        <div class="form-group mb-3">
            <input
                type="email"
                name="email"
                v-model="form.email"
                class="form-control"
                readonly
                placeholder="Email"
            />
        </div>
        <div class="form-group d-flex align-items-center mb-3">
            <input
                :type="showPassword ? 'text' : 'password'"
                class="form-control"
                placeholder="Password"
                v-model="form.password"
            />
            <i
                class="fas fa-eye toogle-password"
                :class="{
                    'fa-eye': showPassword,
                    'fa-eye-slash': !showPassword,
                }"
                @click="tooglePassword"
            ></i>
        </div>
        <div class="form-group d-flex align-items-center mb-3">
            <input
                :type="showPassword ? 'text' : 'password'"
                class="form-control"
                placeholder="Password"
                v-model="form.password_confirmation"
            />
            <i
                class="fas fa-eye toogle-password"
                :class="{
                    'fa-eye': showPassword,
                    'fa-eye-slash': !showPassword,
                }"
                @click="tooglePassword"
            ></i>
        </div>

        <div class="d-grid">
            <button
                class="btn btn-success btn-login"
                type="submit"
                :disabled="form.processing"
            >
                Submit New password
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

<style>
.toogle-password {
    margin-left: -30px;
    cursor: pointer;
    color: #929aac;
}
</style>
