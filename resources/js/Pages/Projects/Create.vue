<template>
  <Head>
    <title>Create Project</title>
  </Head>
  <form @submit.prevent="submit" class="form-container">
    <h1 class="form-title">Create Project</h1>

    <div class="grid-container">
      <!-- Project Name -->
      <div class="form-group">
        <label class="form-label">Project Name</label>
        <input
          type="text"
          v-model="form.project_name"
          required
          :class="['input', { 'input-error': form.errors.project_name }]"
        />
        <div v-if="form.errors.project_name" class="error-message">{{ form.errors.project_name }}</div>
      </div>

      <!-- Client -->
      <div class="form-group">
        <label class="form-label">Client</label>
        <select
          v-model="form.client_id"
          required
          :class="['input', { 'input-error': form.errors.client_id }]"
        >
          <option value="" disabled>Select Client</option>
          <option v-for="client in clients" :key="client.id" :value="client.id">
            {{ client.name }}
          </option>
        </select>
        <div v-if="form.errors.client_id" class="error-message">{{ form.errors.client_id }}</div>
      </div>

      <!-- Developer -->
      <div class="form-group">
        <label class="form-label">Developer</label>
        <select
          v-model="form.developer_id"
          required
          :class="['input', { 'input-error': form.errors.developer_id }]"
        >
          <option value="" disabled>Select Developer</option>
          <option v-for="developer in developers" :key="developer.id" :value="developer.id">
            {{ developer.name }}
          </option>
        </select>
        <div v-if="form.errors.developer_id" class="error-message">{{ form.errors.developer_id }}</div>
      </div>

      <!-- Description -->
      <div class="form-group col-span-2">
        <label class="form-label">Description</label>
        <textarea
          v-model="form.description"
          rows="4"
          :class="['input textarea', { 'input-error': form.errors.description }]"
        ></textarea>
        <div v-if="form.errors.description" class="error-message">{{ form.errors.description }}</div>
      </div>

      <!-- Start Date -->
      <div class="form-group">
        <label class="form-label">Start Date</label>
        <input
          type="date"
          v-model="form.start_date"
          required
          :class="['input', { 'input-error': form.errors.start_date }]"
        />
        <div v-if="form.errors.start_date" class="error-message">{{ form.errors.start_date }}</div>
      </div>

      <!-- End Date -->
      <div class="form-group">
        <label class="form-label">End Date</label>
        <input
          type="date"
          v-model="form.end_date"
          required
          :class="['input', { 'input-error': form.errors.end_date }]"
        />
        <div v-if="form.errors.end_date" class="error-message">{{ form.errors.end_date }}</div>
      </div>
    </div>

    <hr class="section-divider" />
    <h2 class="section-header">Stabilization Period</h2>

    <div class="grid-container">
      <div class="form-group">
        <label class="form-label">Stabilization Start Date</label>
        <input type="date" v-model="form.stabilization_start_date" class="input" />
      </div>
      <div class="form-group">
        <label class="form-label">Stabilization End Date</label>
        <input type="date" v-model="form.stabilization_end_date" class="input" />
      </div>
    </div>

    <hr class="section-divider" />
    <h2 class="section-header">Warranty</h2>

    <div class="grid-container">
      <div class="form-group">
        <label class="form-label">Warranty Start Date</label>
        <input type="date" v-model="form.warranty_start_date" class="input" />
      </div>
      <div class="form-group">
        <label class="form-label">Warranty End Date</label>
        <input type="date" v-model="form.warranty_end_date" class="input" />
      </div>
    </div>

    <hr class="section-divider" />
    <h2 class="section-header">Support & Maintenance</h2>

    <div class="grid-container">
      <div class="form-group">
        <label class="form-label">Support Start Date</label>
        <input type="date" v-model="form.support_start_date" class="input" />
      </div>
      <div class="form-group">
        <label class="form-label">Support End Date</label>
        <input type="date" v-model="form.support_end_date" class="input" />
      </div>
    </div>

    <button type="submit" class="btn-submit">Submit</button>
  </form>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { Head } from '@inertiajs/vue3'

defineProps({ clients: Array, developers: Array })

const form = useForm({
  project_name: '',
  client_id: '',
  developer_id: '',
  description: '',
  start_date: '',
  end_date: '',
  stabilization_start_date: '',
  stabilization_end_date: '',
  warranty_start_date: '',
  warranty_end_date: '',
  support_start_date: '',
  support_end_date: ''
})

function submit() {
  form.post('/projects', {
    onSuccess: () => {
      console.log('Project created successfully.')
    },
    onError: () => {
      console.log('Validation errors:', form.errors)
    }
  })
}
</script>

<style scoped>
/* Your styles unchanged */
.form-container {
  background: #fff;
  padding: 2rem;
  max-width: 900px;
  margin: auto;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.form-title {
  font-size: 1.75rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  text-align: center;
  color: #2d3748;
}

.grid-container {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 1.5rem;
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

.input-error {
  border-color: #e53e3e !important;
  background-color: #fff5f5;
}

.error-message {
  color: #e53e3e;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.textarea {
  resize: vertical;
}

.btn-submit {
  margin-top: 2rem;
  display: block;
  width: 100%;
  background-color: #3182ce;
  color: #fff;
  padding: 0.75rem 1.25rem;
  font-size: 1rem;
  font-weight: bold;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.btn-submit:hover {
  background-color: #2b6cb0;
}

.section-divider {
  border: 1px solid #ddd;
  margin: 2rem 0 1rem 0;
}

.section-header {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 30px;
  color: #e53e3e;
}
</style>
