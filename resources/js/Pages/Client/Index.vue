<template>
  <Head>
    <title>Client List</title>
  </Head>

  <div class="page-wrapper">
    <div class="header">
      <h1>Client List</h1>
      <Link :href="route('clients.create')" class="create-btn">
        <Plus class="icon" /> Add New Client
      </Link>
    </div>

    <div class="card">
      <table class="record-table">
        <thead>
          <tr>
            <th></th>
            <th>Client Name</th>
            <th>Location</th>
            <th>Created At</th>
            <th>Updated At</th>

          </tr>
        </thead>
        <tbody>
          <tr v-if="clients.length === 0">
            <td colspan="5" class="no-data">
              <Info class="no-data-icon" />
              No clients found.
            </td>
          </tr>

          <tr v-for="client in clients" :key="client.id">
            <td class="actions">
              <Link :href="route('clients.show', client.id)" class="icon-btn yellow" title="View">
                <Info class="icon" />
              </Link>
              <Link :href="route('clients.edit', client.id)" class="icon-btn blue" title="Edit">
                <Pencil class="icon" />
              </Link>
              <button @click="deleteClient(client.id)" class="icon-btn red" title="Delete">
                <Trash2 class="icon" />
              </button>
            </td>
            <td>{{ client.name }}</td>
            <td>{{ client.location?.name || 'N/A' }}</td>
            <td>{{ formatDate(client.created_at) }}</td>
            <td>{{ formatDate(client.updated_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from "@inertiajs/vue3";
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { route } from 'ziggy-js'
import { Pencil, Trash2, Plus, Info } from 'lucide-vue-next'

const props = defineProps({
  clients: Array,
})

const clients = ref([...props.clients])

function deleteClient(id) {
  if (confirm("Are you sure you want to delete this client?")) {
    Inertia.delete(route('clients.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        Inertia.visit(route('clients.index'), { replace: true })
      },
      onError: (errors) => {
        alert('❌ Failed to delete client.')
        console.error(errors)
      }
    })
  }
}

function formatDate(datetime) {
  if (!datetime) return '-'
  const date = new Date(datetime)
  return date.toLocaleString('en-MY', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>

<style scoped>
/* Copy of the Support & Maintenance styles */

.page-wrapper {
  padding: 2rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

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

.create-btn {
  display: flex;
  align-items: center;
  background-color: #1d4ed8;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 0.5rem 1rem;
  font-weight: 500;
  cursor: pointer;
  gap: 0.4rem;
  text-decoration: none;
  transition: background 0.2s;
}

.create-btn:hover {
  background-color: #2563eb;
}

.card {
  background: #fff;
  padding: 10px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  overflow-x: auto;
}

.record-table {
  width: 100%;
  border-collapse: collapse;
}

.record-table thead {
  background: #f8f9fa;
  color: #495057;
}

.record-table th,
.record-table td {
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid #e9ecef;
  font-size: 0.95rem;
  vertical-align: top;
}

.actions-header {
  text-align: center;
}

.record-table tr:nth-child(even) {
  background: #fdfdfd;
}

.actions {
  display: flex;
  gap: 0.5rem;
  align-items: center;
  justify-content: center;
  vertical-align: middle;
}

.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 6px;
  border-radius: 6px;
  cursor: pointer;
  border: none;
}

.icon-btn .icon {
  width: 20px;
  height: 20px;
}

.icon-btn.blue {
  background: #e0f0ff;
  color: #007bff;
}

.icon-btn.red {
  background: #ffe0e0;
  color: #dc3545;
}

.icon-btn.yellow {
  background: #efff9e;
  color: #495057;
}

.icon-btn:hover {
  filter: brightness(0.95);
}

.no-data {
  text-align: center;
  padding: 20px;
  color: #999;
}

.no-data-icon {
  width: 24px;
  height: 24px;
  margin-bottom: 8px;
}
</style>
