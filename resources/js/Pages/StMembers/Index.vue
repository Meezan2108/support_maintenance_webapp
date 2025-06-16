<template>
  <Head>
    <title>ST Members</title>
  </Head>

  <div class="page-wrapper">
    <div class="header">
      <h1>ST Members</h1>
    </div>

    <div class="card">
      <div v-if="successMessage" class="flash-message success">{{ successMessage }}</div>
      <div v-if="errorMessage" class="flash-message error">{{ errorMessage }}</div>

      <form @submit.prevent="submit" class="form">
        <input v-model="form.full_name" type="text" placeholder="Full Name" class="filter-input" />
        <input v-model="form.hired_date" type="date" class="filter-input" />
        <select v-model="form.status" class="filter-input">
          <option value="" disabled>Select status</option>
          <option value="Active">Active</option>
          <option value="Inactive">Inactive</option>
        </select>
        <button type="submit" class="create-btn">
          {{ form.id ? 'Update' : 'Add' }}
        </button>
        <button v-if="form.id" type="button" @click="formReset" class="create-btn btn-gray">
          Cancel
        </button>
      </form>
    </div>

    <div class="card">
      <table id="st-members-table" class="record-table">
        <thead>
          <tr>
            <th></th>
            <th>Full Name</th>
            <th>Hired Date</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="members.length === 0">
            <td colspan="6" class="no-data">
              <Info class="no-data-icon" />
              No members found.
            </td>
          </tr>
          <tr v-for="member in members" :key="member.id">
            <td class="actions">
              <button @click="edit(member)" class="icon-btn blue" title="Edit">
                <Pencil class="icon" />
              </button>
              <button @click="destroy(member.id)" class="icon-btn red" title="Delete">
                <Trash2 class="icon" />
              </button>
            </td>
            <td>{{ member.full_name }}</td>
            <td>{{ member.hired_date }}</td>
            <td>{{ member.status }}</td>
            <td>{{ formatDate(member.created_at) }}</td>
            <td>{{ formatDate(member.updated_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { Pencil, Trash2, Info } from 'lucide-vue-next'
import { Head } from "@inertiajs/vue3";
import DataTable from 'datatables.net-bs5'
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css'

const props = defineProps({
  members: Array,
  urlStore: String,
  flash: Object,
})

const successMessage = ref(props.flash?.success || '')
const errorMessage = ref(props.flash?.error || '')

const form = useForm({
  id: null,
  full_name: '',
  hired_date: '',
  status: '',
})

let dataTableInstance = null

function submit() {
  if (!form.full_name || !form.hired_date || !form.status) {
    errorMessage.value = '⚠️ Please fill in all fields.'
    clearMessage()
    return
  }

  errorMessage.value = ''

  if (form.id) {
    form.put(`/st-members/${form.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '✅ Member updated successfully!'
        formReset()
        clearMessage()
      },
    })
  } else {
    form.post(props.urlStore, {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '✅ Member added successfully!'
        formReset()
        clearMessage()
      },
    })
  }
}

function edit(member) {
  form.id = member.id
  form.full_name = member.full_name
  form.hired_date = member.hired_date
  form.status = member.status
}

function destroy(id) {
  if (confirm('Are you sure you want to delete this member?')) {
    Inertia.delete(`/st-members/${id}`, {
      preserveScroll: true,
      onSuccess: () => {
        successMessage.value = '🗑️ Member deleted successfully!'
        clearMessage()
      },
    })
  }
}

function formReset() {
  form.reset()
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

onMounted(() => {
  dataTableInstance = new DataTable('#st-members-table')
})

watch(() => props.members, () => {
  if (dataTableInstance) {
    dataTableInstance.destroy()
    dataTableInstance = new DataTable('#st-members-table')
  }
})
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
