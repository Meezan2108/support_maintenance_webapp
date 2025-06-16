<script>
import Layout from "../Layouts/AdminLayout.vue";

export default {
    layout: Layout,
};
</script>
<script setup>
import { Head } from "@inertiajs/vue3";
import VHeaderBreadcrumb from "@/Shared/VHeaderBreadcrumb.vue";

const breadcrumbs = [
    {
        url: "#",
        label: "Dashboard",
    },
];
</script>

<template>
    <Head>
        <title>Dashboard</title>
    </Head>

    <div class="p-3">
        <!-- <VHeaderBreadcrumb :breadcrumbs="breadcrumbs" /> -->
        <div class="header">
            <h1>Dashboard</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <p>Dashboard is temporarily unavailable at the moment...</p>
                <!-- <iframe
                    title="CRIMS DASHBOARD v2.2"
                    width="100%"
                    height="700"
                    src="https://app.powerbi.com/view?r=eyJrIjoiOGFkYzg5MjQtNmM2Zi00MzJmLTg2NjMtM2E4YzhmMWM5OGY4IiwidCI6IjIxMGI0NTUzLWEwOTktNDYyMi05YTlhLTdjZWRkMjc3Mzk1NCIsImMiOjEwfQ%3D%3D"
                    frameborder="0"
                    allowFullScreen="true"
                ></iframe> -->
            </div>
        </div>
    </div>
</template>


<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.header h1 {
  font-size: 2rem;
  font-weight: bold;
  color: #2c3e50;
}
</style>