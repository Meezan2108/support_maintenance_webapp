<template>
    <Head>
    <title>View Client List</title>
  </Head>
  <div class="client-container">
    <Link href="/clients" class="btn-back"> Back to Client List</Link>
    <h1 class="page-title">{{ client.name }}</h1>

    <div class="meta-box">
      <span><strong>Location:</strong> {{ client.location?.name ?? 'Not assigned' }}</span>
    </div>

    <div v-if="client.correspondents.length" class="correspondents-section">
      <h2 class="section-title">Correspondents</h2>
      <div class="table-wrapper">
        <table class="modern-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Position</th>
              <th>Department</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="correspondent in client.correspondents" :key="correspondent.id">
              <td>{{ correspondent.name }}</td>
              <td>{{ correspondent.email || 'N/A' }}</td>
              <td>{{ correspondent.phone || 'N/A' }}</td>
              <td>{{ correspondent.position || 'N/A' }}</td>
              <td>{{ correspondent.department || 'N/A' }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-else class="no-correspondents">
      <p>No correspondents added.</p>
    </div>

    
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { Head } from "@inertiajs/vue3";

defineProps({
  client: Object,
})
</script>

<style scoped>
.client-container {
  max-width: 960px;
  margin: 40px auto;
  background-color: #ffffff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #1f2937;
}

.page-title {
  font-size: 1.875rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: #111827;
}

.meta-box {
  background: #f9fafb;
  padding: 1rem 1.25rem;
  margin-bottom: 2rem;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 0.95rem;
  color: #374151;
}

.section-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: #1f2937;
}

.table-wrapper {
  overflow-x: auto;
}

.modern-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.95rem;
  background-color: white;
}

.modern-table th {
  background-color: #f3f4f6;
  text-align: left;
  padding: 0.75rem;
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #e5e7eb;
}

.modern-table td {
  padding: 0.75rem;
  border-bottom: 1px solid #e5e7eb;
  color: #4b5563;
}

.modern-table tr:hover {
  background-color: #f9fafb;
}

.btn-back {
  display: inline-block;
  margin-top: 10px;
  margin-bottom: 30px;
  background-color: #2563eb;
  color: #ffffff;
  padding: 0.65rem 1.25rem;
  font-size: 0.95rem;
  font-weight: 600;
  text-decoration: none;
  border-radius: 8px;
  transition: background-color 0.2s ease;
}

.btn-back:hover {
  background-color: #1d4ed8;
}

.no-correspondents {
  padding: 1rem;
  background-color: #fef3c7;
  color: #92400e;
  border: 1px solid #fde68a;
  border-radius: 8px;
  margin-bottom: 2rem;
}
</style>
