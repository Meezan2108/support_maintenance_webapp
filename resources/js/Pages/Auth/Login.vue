<script>
import Layout from "@/Layouts/AuthLayout.vue";

export default {
  layout: Layout,
};
</script>

<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import VAlert from "@/Shared/VAlert";
import { VueRecaptcha } from "vue-recaptcha";

import VButtonSubmit from "@/Shared/Buttons/VButtonSubmit";
import FlashNotification from "@/Components/FlashNotification.vue"; // ✅ Flash message component

const showPassword = ref(false);
const elRecaptcha = ref(null);

const form = useForm({
  email: "",
  password: "",
  remember_me: false,
  recaptcha: "",
});

const recaptchav2_sitekey = computed(() => usePage().props.recaptchav2_sitekey);

const onCaptchaVerify = (token) => {
  form.recaptcha = token;
};

const tooglePassword = () => {
  showPassword.value = !showPassword.value;
};

const login = () => {
  form.post("/login", {
    preserveScroll: true,
    onError: (errors) => {
      if (form.recaptcha) {
        elRecaptcha.value.reset();
      }
    },
  });
};
</script>

<template>
  <Head>
    <title>Login</title>
    <meta name="description" content="Welcome to Lembaga Koko Malaysia" />
  </Head>

  <!-- Flash notification component -->
  <FlashNotification />

  <form @submit.prevent="login" method="post">
    <VAlert :isShowValidation="false" />

    <div class="form-group mb-3">
      <input
        type="email"
        name="email"
        v-model="form.email"
        class="form-control"
        :class="{ 'border-error': form.errors.email }"
        placeholder="Email"
      />
      <div v-if="form.errors.email" class="row">
        <div class="col-12 text-danger font-error">{{ form.errors.email }}</div>
      </div>
    </div>

    <div class="mb-3">
      <div class="form-group d-flex align-items-center">
        <input
          :type="showPassword ? 'text' : 'password'"
          class="form-control"
          :class="{ 'border-error': form.errors.password }"
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

      <div v-if="form.errors.password" class="row">
        <div class="col-12 text-danger font-error">{{ form.errors.password }}</div>
      </div>
    </div>

    <div class="form-check mb-3">
      <input
        class="form-check-input"
        type="checkbox"
        v-model="form.remember_me"
        id="rememberPasswordCheck"
      />
      <label class="form-check-label" for="rememberPasswordCheck">Remember Me</label>
    </div>

    <!--
    <div class="mb-3">
      <VueRecaptcha
        :sitekey="recaptchav2_sitekey"
        ref="elRecaptcha"
        @verify="onCaptchaVerify"
      />
      <div v-if="form.errors.recaptcha" class="row">
        <div class="col-12 text-danger font-error">{{ form.errors.recaptcha }}</div>
      </div>
    </div>
    -->

    <div class="d-grid">
      <VButtonSubmit type="submit" :isProcessing="form.processing">Login</VButtonSubmit>
    </div>

    <div class="footer">
      <p>&copy; Powered By - ST Advisory 2025</p>
    </div>

    <!--
    <hr class="my-4" />
    <div class="d-grid mb-2">
      <a class="btn text-uppercase fw-bold" href="#">
        <i class="fas fa-key me-2"></i> Sign in with CRIMS SSO
      </a>
    </div>
    -->
  </form>
</template>

<style>
.toogle-password {
  margin-left: -30px;
  cursor: pointer;
  color: #929aac;
}

.footer {
  text-align: center;
  margin-top: 70px;
  color: #929aac;
}
</style>
