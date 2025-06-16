<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import VAlert from "@/Shared/VAlert.vue";

let props = defineProps({
    user: Object,
});
</script>

<template>
    <Head>
        <title>Profile</title>
    </Head>

    <div class="p-3">
        <div class="card mb-3">
            <div class="card-body">
                <h4>Profile</h4>
            </div>
        </div>

        <VAlert />

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3 row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label fw-bold"
                                >Name</label
                            >
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control-plaintext"
                                    id="staticEmail"
                                    :value="user.name"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3 row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label fw-bold"
                                >Email</label
                            >
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control-plaintext"
                                    id="staticEmail"
                                    :value="user.email"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- <div class="mb-3 row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label fw-bold"
                                >Telp.</label
                            >
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control-plaintext"
                                    id="staticEmail"
                                    :value="user.tel_no"
                                />
                            </div>
                        </div> -->
                    </div>
                    <div class="col-lg-6">
                        <!-- <div class="mb-3 row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label fw-bold"
                                >Fax</label
                            >
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control-plaintext"
                                    id="staticEmail"
                                    :value="user.fax_no"
                                />
                            </div>
                        </div> -->
                    </div>

                    <div class="col-lg-6">
                        <!-- <div class="mb-3 row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label fw-bold"
                                >Institution</label
                            >
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control-plaintext"
                                    id="staticEmail"
                                    :value="user.institution"
                                />
                            </div>
                        </div> -->
                    </div>
                    <div class="col-lg-6">
                        <!-- <div class="mb-3 row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label fw-bold"
                                >Division</label
                            >
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control-plaintext"
                                    id="staticEmail"
                                    :value="user.division"
                                />
                            </div>
                        </div> -->
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3 row">
                            <label
                                for="staticEmail"
                                class="col-sm-3 col-form-label fw-bold"
                                >Role</label
                            >
                            <div class="col-sm-9">
                                <input
                                    type="text"
                                    readonly
                                    class="form-control-plaintext"
                                    id="staticEmail"
                                    :value="
                                        user.roles
                                            .map((item) => item.name)
                                            .join(',')
                                    "
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
