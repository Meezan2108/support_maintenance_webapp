<script setup>
import { usePage } from "@inertiajs/vue3";

const appBaseUrl = usePage().props.appBaseUrl;
</script>

<template>
    <div class="min-vh-100 d-flex flex-lg-row flex-column-reverse">
        <div
            id="left-container"
            class="d-flex justify-content-center mx-3 mx-lg-0"
        >
            <div class="my-lg-auto row justify-content-center flex-grow-1">
                <div class="col-12 col-md-8">
                    <div class="text-center mb-5">
                        <div
                            class="circle-wrapper mx-auto d-flex flex-row align-items-center justify-content-center"
                        >
                            <img
                                id="logo"
                                :src="
                                    appBaseUrl + '/assets/images/st-adv-logo.png'
                                "
                                class="mx-2"
                            />

                                <img
                                id="logo"
                                :src="
                                    appBaseUrl + '/assets/images/datablu-logo.png'
                                "
                                class="mx-2"
                            />
                        </div>
                        <div id="logo-title" class="text-center">
                            <!-- <span class="d-none d-lg-block">
                                ST Advisory 2025
                            </span> -->
                            <span class="d-block d-lg-none">
                                ST Support & Maintenance System 
                                <br />
                                (ST DMS v25.01)
                            </span>
                        </div>
                    </div>

                    <slot />
                </div>
            </div>
        </div>

        <div id="right-container" class="d-flex flex-column">
            <div class="overlay d-flex align-items-center">
                <div class="row w-100">
                    <div class="col-10">
                        <h3 class="d-none d-lg-block">
                            ST Support & Maintenance System <br />
                            (ST DMS v25.01)
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#left-container {
    flex-grow: 2;
    margin-top: -50px;
}

@media only screen and (min-width: 1000px) {
    #left-container {
        min-width: 562px;
    }
}

#logo {
    width: 170px;
}

/* .circle-wrapper {
    width: 110px;
    height: 110px;
    background: white;
    border-radius: 90px;
} */

.circle-wrapper {
    width: 110px;
    height: 110px;
    background: white;
    margin-top: 70px;
}

#left-container #logo-title {
    font-weight: bold;
}

#right-container {
    background-image: url("/assets/images/st_branches_bg.png");
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    flex-grow: 3;
    min-height: 200px;
}

#right-container .overlay {
    background: linear-gradient(
        360deg,
        rgba(0, 0, 0, 0.7) 0%, 
        rgba(0, 0, 0, 0) 122.37%
    );
    flex-grow: 1;
}

#right-container h3 {
    margin-left: 100px;
    font-size: 3rem;
    line-height: 3.8rem;
    color: #fff;
    z-index: 100;
}
</style>
