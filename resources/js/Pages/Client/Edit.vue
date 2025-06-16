<template>
    <Head>
    <title>Edit Client List</title>
  </Head>
  <div class="form-container">
    <h2 class="form-title">Edit Client</h2>

    <form @submit.prevent="submit" class="form-grid">
      <!-- Client Details Section -->
      <h3 class="section-title full-width">Client Details</h3>

      <div class="form-group">
        <label class="form-label">Client Name:</label>
        <input v-model="form.name" type="text" required class="input" />
      </div>

      <div class="form-group">
        <label class="form-label">Location:</label>
        <select v-model="form.location_id" required class="input">
          <option value="" disabled>Select a location</option>
          <option v-for="location in locations" :key="location.id" :value="location.id">
            {{ location.name }}
          </option>
        </select>
      </div>

      <hr class="section-divider full-width" />

      <!-- Correspondents Section -->
      <div class="form-row">
        <h3 class="section-title">Correspondents</h3>
        <button type="button" @click="add" class="add-btn">➕ Add Correspondent</button>
      </div>

      <!-- Table Headers -->
      <div class="correspondent-grid header">
        <span class="table-header">Name</span>
        <span class="table-header">Email</span>
        <span class="table-header">Phone</span>
        <span class="table-header">Position</span>
        <span class="table-header">Department</span>
        <span class="table-header"></span>
      </div>

      <!-- Table Rows -->
      <div
        v-for="(corr, index) in form.correspondents"
        :key="index"
        class="correspondent-grid"
      >
        <input v-model="corr.name" type="text" required placeholder="Name" class="input" />
        <input v-model="corr.email" type="email" placeholder="Email" class="input" />
        <input v-model="corr.phone" type="text" placeholder="Phone" class="input" />
        <input v-model="corr.position" type="text" placeholder="Position" class="input" />
        <input v-model="corr.department" type="text" placeholder="Department" class="input" />
        <button type="button" @click="remove(index)" class="btn-remove" title="Remove">❌</button>
      </div>

      <hr class="section-divider full-width" />

      <div class="form-group full-width">
        <button type="submit" class="btn-submit">💾 Update</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { Head } from "@inertiajs/vue3";

const props = defineProps({
  client: Object,
  locations: Array,
})

const form = useForm({
  name: props.client.name || '',
  location_id: props.client.location_id || '',
  correspondents: props.client.correspondents?.map(c => ({
    name: c.name || '',
    email: c.email || '',
    phone: c.phone || '',
    position: c.position || '',
    department: c.department || ''
  })) || [],
})

function add() {
  form.correspondents.push({
    name: '',
    email: '',
    phone: '',
    position: '',
    department: ''
  })
}

function remove(index) {
  form.correspondents.splice(index, 1)
}

function submit() {
  form.put(`/clients/${props.client.id}`)
}
</script>

<style scoped>
.form-container {
  background: #fff;
  padding: 2rem 1.5rem;
  max-width: 900px;
  margin: 2rem auto;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  box-sizing: border-box;
  overflow-x: hidden;
}

.form-title {
  font-size: 1.75rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: #2b6cb0;
  text-align: left;
}

.section-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 1rem 0 0.5rem;
  color: #2d3748;
  text-align: left;
}

.section-divider {
  border: 0;
  border-top: 1px solid #e2e8f0;
  margin: 1.5rem 0;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.full-width {
  grid-column: span 2;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #4a5568;
}

.input {
  padding: 0.5rem 0.75rem;
  border: 1px solid #cbd5e0;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.2s ease;
}

.input:focus {
  border-color: #3182ce;
  outline: none;
  box-shadow: 0 0 0 1px #3182ce;
}

.form-row {
  grid-column: span 2;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

.correspondent-grid.header {
  font-weight: 600;
  background: #ebf8ff;
  padding: 0.5rem 0.75rem;
  color: #2b6cb0;
  border-radius: 4px;
}

.table-header {
  display: flex;
  align-items: center;
}

.correspondent-grid {
  grid-column: span 2;
  display: grid;
    grid-template-columns: repeat(5, 1fr) 50px;
  gap: 1rem;
  align-items: end;
  margin-bottom: 0.5rem;
    overflow-x: auto;
}

.btn-remove {
  width: 40px;
  height: 40px;
  font-size: 1.1rem;
  padding: 0;
  border: none;
  border-radius: 6px;
  background: #dc3545;
  color: white;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: background-color 0.2s ease;
}

.input {
  min-width: 0;
  width: 100%;
}


.btn-remove:hover {
  background-color: #b02a37;
}

.add-btn,
.btn-submit {
  padding: 8px 14px;
  font-size: 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  color: #fff;
}

.add-btn {
  background: #17a2b8;
}

.btn-submit {
  background: #28a745;
  font-weight: 700;
  font-size: 1rem;
  padding: 0.75rem 1.25rem;
  width: 100%;
  transition: background-color 0.2s ease;
}

.btn-submit:hover {
  background-color: #218838;
}
</style>
