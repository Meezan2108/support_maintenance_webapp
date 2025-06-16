<template>
    <Head>
    <title>Create Client List</title>
  </Head>
  <div class="form-container">
    <h2 class="form-title">Add New Client Details</h2>

    <form @submit.prevent="submit" class="form-grid">
      <!-- Section: Client Details -->
      <h3 class="section-title full-width">Client Details</h3>

      <!-- Client Name -->
      <div class="form-group">
        <label class="form-label">Client Name:</label>
        <input v-model="form.name" type="text" required class="input" />
      </div>

      <!-- Location -->
      <div class="form-group">
        <label class="form-label">Location:</label>
        <select v-model="form.location_id" required class="input">
          <option value="" disabled>Select location</option>
          <option v-for="location in locations" :key="location.id" :value="location.id">
            {{ location.name }}
          </option>
        </select>
      </div>

      <!-- Divider -->
      <hr class="section-divider full-width" />

      <!-- Section: Correspondents -->
      <div class="form-row">
        <h3 class="section-title">Correspondents</h3>
        <button type="button" @click="add" class="add-btn"><Plus class="icon" /> Add Correspondent</button>
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
        <button type="button" @click="remove(index)" class="btn-remove" title="Remove"><Trash2 class="icon" /></button>
      </div>

      <hr class="section-divider full-width" />

      <div class="form-group full-width">
        <button type="submit" class="btn-submit">Save Client Details</button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { Head } from "@inertiajs/vue3";
import { Pencil, Trash2, Plus, Info } from 'lucide-vue-next'

const props = defineProps({
  locations: Array,
})

const form = useForm({
  name: '',
  location_id: '',
  correspondents: [{ name: '', email: '', phone: '', position: '', department: '' }],
})

function add() {
  form.correspondents.push({ name: '', email: '', phone: '', position: '', department: '' })
}

function remove(index) {
  form.correspondents.splice(index, 1)
}

function submit() {
  form.post('/clients')
}
</script>

<style scoped>
/* Container & Typography */
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
  color: #2b6cb0; /* blue */
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

/* Full-width utility */
.full-width {
  grid-column: span 2;
}

/* Form Layout */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

/* Form Groups */
.form-group {
  display: flex;
  flex-direction: column;
}

.form-label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #4a5568;
}

/* Inputs & Select */
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

/* Add Correspondent Button */
.add-btn {
  padding: 8px 14px;
  font-size: 14px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  color: #fff;
  background: #17a2b8;
  justify-self: end;
  width: fit-content;
}

/* Row to align title + button */
.form-row {
  grid-column: span 2;
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}

/* Correspondent Grid with Table Headers */
.correspondent-grid {
  grid-column: span 2;
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr)) 40px;
  gap: 1rem;
  align-items: end;
  margin-bottom: 0.5rem;
}

/* Table Header Styling */
.correspondent-grid.header {
  font-weight: 600;
  color: #fff;
  background-color: #4299e1; /* light blue background */
  padding: 0.5rem 0.75rem;
  border-radius: 4px;
}

.correspondent-grid.header .table-header {
  display: flex;
  align-items: center;
  color: #fff;
  font-size: 0.95rem;
}

/* Remove Button */
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

.btn-remove:hover {
  background-color: #b02a37;
}

/* Submit Button */
.btn-submit {
  background-color: #3182ce;
  color: #fff;
  padding: 0.75rem 1.25rem;
  font-size: 1rem;
  font-weight: 700;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  width: 100%;
  transition: background-color 0.2s ease;
}

.btn-submit:hover {
  background-color: #2b6cb0;
}
</style>
