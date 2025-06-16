<script setup></script>

<template>
    <div class="component-loading-overlay"></div>
    <div
        class="component-loading d-flex justify-content-center align-items-center"
    >
        <div class="loading-content bg-white py-3 px-5">
            <div class="spinner-border spinner-border-sm ms-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading ...
        </div>
    </div>
</template>

<style>
.component-loading-wrapper {
    position: relative;
}

.component-loading-overlay {
    background: rgba(255, 255, 255, 0.7);
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    z-index: 2;
}

.component-loading {
    position: absolute;
    top: 0px;
    bottom: 0px;
    left: 0px;
    right: 0px;
    z-index: 3;
}
</style>
