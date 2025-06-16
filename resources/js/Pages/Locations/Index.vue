<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { Pencil, Trash2, Info } from 'lucide-vue-next'

const props = defineProps({
  locations: Array,
  urlStore: String,
})

const form = useForm({ id: null, name: '' })
const successMessage = ref('')
const errorMessage = ref('')
const editingId = ref(null)

const submit = () => {
  if (!form.name.trim()) {
    errorMessage.value = '⚠️ Please enter a location name.'
    clearMessage()
    return
  }

  errorMessage.value = ''

  if (editingId.value) {
    form.put(`/locations/${editingId.value}`, {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '✅ Location updated successfully!'
        editingId.value = null
        clearMessage()
      },
    })
  } else {
    form.post(props.urlStore, {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '✅ Location added successfully!'
        clearMessage()
      },
    })
  }
}

const edit = (location) => {
  form.name = location.name
  editingId.value = location.id
  errorMessage.value = ''
  successMessage.value = ''
}

const cancelEdit = () => {
  form.name = ''
  editingId.value = null
  errorMessage.value = ''
  successMessage.value = ''
}

const destroy = (id) => {
  if (confirm('Are you sure you want to delete this location?')) {
    Inertia.delete(`/locations/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '🗑️ Location deleted successfully!'
        clearMessage()
      },
    })
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function clearMessage() {
  setTimeout(() => {
    successMessage.value = ''
    errorMessage.value = ''
  }, 3000)
}
</script>

<template>
  <Head>
    <title>Location</title>
  </Head>

  <div class="page-wrapper">
    <div class="header">
      <h1>Locations</h1>
    </div>

    <div class="card">
      <!-- Success and error messages above the input -->
      <div v-if="successMessage" class="flash-message success">{{ successMessage }}</div>
      <div v-if="errorMessage" class="flash-message error">{{ errorMessage }}</div>

      <form @submit.prevent="submit" class="form">
        <input
          v-model="form.name"
          type="text"
          placeholder="Location Name"
          class="filter-input"
        />
        <button type="submit" class="create-btn">
          {{ editingId ? 'Update' : 'Add' }}
        </button>
        <button
          v-if="editingId"
          type="button"
          @click="cancelEdit"
          class="create-btn btn-gray"
        >
          Cancel
        </button>
      </form>
    </div>

    <div class="card">
      <table class="record-table">
        <thead>
          <tr>
            <th></th>
            <th>Location Name</th>
            <th>Created At</th>
            <th>Updated At</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="locations.length === 0">
            <td colspan="4" class="no-data">
              <Info class="no-data-icon" />
              No locations found.
            </td>
          </tr>
          <tr v-for="location in locations" :key="location.id">
            <td class="actions">
              <button @click="edit(location)" class="icon-btn blue" title="Edit">
                <Pencil class="icon" />
              </button>
              <button @click="destroy(location.id)" class="icon-btn red" title="Delete">
                <Trash2 class="icon" />
              </button>
            </td>
            <td>{{ location.name }}</td>
            <td>{{ formatDate(location.created_at) }}</td>
            <td>{{ formatDate(location.updated_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
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

.card {
  background: #fff;
  padding: 1rem;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  margin-bottom: 1.5rem;
}

.form {
  display: flex;
  gap: 0.75rem;
  align-items: center;
  margin-bottom: 1rem;
}

.filter-input {
  flex: 1;
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 1rem;
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
  transition: background 0.2s;
}

.create-btn:hover {
  background-color: #2563eb;
}

.btn-gray {
  background-color: #9ca3af;
}

.btn-gray:hover {
  background-color: #6b7280;
}

.flash-message {
  padding: 0.75rem;
  margin-bottom: 1rem;
  border-radius: 6px;
  text-align: left;
  font-weight: 600;
}

.flash-message.success {
  background-color: #d4edda;
  color: #155724;
  border: 1px solid #c3e6cb;
}

.flash-message.error {
  background-color: #fff1f0;
  color: #cf1322;
  border: 1px solid #ffa39e;
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
}

.actions {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
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
