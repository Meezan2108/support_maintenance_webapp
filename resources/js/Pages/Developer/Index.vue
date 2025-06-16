<template>
  <Head>
    <title>Developer List</title>
  </Head>

  <div class="page-wrapper">
    <div class="header">
      <h1>Developer List</h1>
    </div>

    <div class="card">
      <!-- Success and error messages above the input -->
      <div v-if="successMessage" class="flash-message success">{{ successMessage }}</div>
      <div v-if="errorMessage" class="flash-message error">{{ errorMessage }}</div>

      <form @submit.prevent="submit" class="form">
        <input
          v-model="form.name"
          type="text"
          placeholder="Developer Name"
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
            <th>Name</th>
            <th>Created At</th>
            <th>Updated At</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="developers.length === 0">
            <td colspan="4" class="no-data">
              <Info class="no-data-icon" />
              No developers found.
            </td>
          </tr>
          <tr v-for="developer in developers" :key="developer.id">
            <td class="actions">
              <button @click="edit(developer)" class="icon-btn blue" title="Edit">
                <Pencil class="icon" />
              </button>
              <button @click="remove(developer.id)" class="icon-btn red" title="Delete">
                <Trash2 class="icon" />
              </button>
            </td>
            <td>{{ developer.name }}</td>
            <td>{{ formatDate(developer.created_at) }}</td>
            <td>{{ formatDate(developer.updated_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Head, router } from '@inertiajs/vue3';
import { Pencil, Trash2, Info } from 'lucide-vue-next';

defineProps({ developers: Array })

const form = useForm({ name: '' })
const editingId = ref(null)
const successMessage = ref('')
const errorMessage = ref('')

const submit = () => {
  if (!form.name.trim()) {
    errorMessage.value = '⚠️ Please enter a developer name.'
    clearMessage()
    return
  }

  errorMessage.value = ''

  if (editingId.value) {
    form.put(route('developers.update', editingId.value), {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '✅ Developer updated successfully!'
        editingId.value = null
        form.reset()
        clearMessage()
      },
    })
  } else {
    form.post(route('developers.store'), {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '✅ Developer added successfully!'
        form.reset()
        clearMessage()
      },
    })
  }
}

const edit = (developer) => {
  form.name = developer.name
  editingId.value = developer.id
  errorMessage.value = ''
  successMessage.value = ''
}

const cancelEdit = () => {
  form.reset()
  editingId.value = null
  errorMessage.value = ''
  successMessage.value = ''
}

const remove = (id) => {
  if (confirm('Are you sure you want to delete this developer?')) {
    router.delete(route('developers.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '🗑️ Developer deleted successfully!'
        clearMessage()
      },
    })
  }
}

function formatDate(dateString) {
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
