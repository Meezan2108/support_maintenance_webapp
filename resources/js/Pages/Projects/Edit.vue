<template>
  <Head>
    <title>Edit Project</title>
  </Head>

  <div class="container">
    <h1>Edit Project</h1>

    <form @submit.prevent="updateProject" class="form">
      <!-- Project Basic Info -->
      <div class="form-row">
        <div class="form-col">
          <label>Project Name</label>
          <input v-model="form.project_name" type="text" />
          <span v-if="form.errors.project_name" class="error">{{ form.errors.project_name }}</span>
        </div>

        <div class="form-col">
          <label>Client</label>
          <select v-model="form.client_id">
            <option value="">Select a client</option>
            <option v-for="client in clients" :key="client.id" :value="client.id">{{ client.name }}</option>
          </select>
          <span v-if="form.errors.client_id" class="error">{{ form.errors.client_id }}</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-col">
          <label>Developer</label>
          <select v-model="form.developer_id">
            <option value="">Select a developer</option>
            <option v-for="dev in developers" :key="dev.id" :value="dev.id">{{ dev.name }}</option>
          </select>
          <span v-if="form.errors.developer_id" class="error">{{ form.errors.developer_id }}</span>
        </div>

        <div class="form-col">
          <label>Description</label>
          <textarea v-model="form.description"></textarea>
          <span v-if="form.errors.description" class="error">{{ form.errors.description }}</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-col">
          <label>Start Date</label>
          <input v-model="form.start_date" type="date" />
          <span v-if="form.errors.start_date" class="error">{{ form.errors.start_date }}</span>
        </div>

        <div class="form-col">
          <label>End Date</label>
          <input v-model="form.end_date" type="date" />
          <span v-if="form.errors.end_date" class="error">{{ form.errors.end_date }}</span>
        </div>
      </div>

      <div class="form-row">
        <div class="form-col">
          <label>Status</label>
          <select v-model="form.status">
            <option value="">Select status</option>
            <option value="Planned">Planned</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
          </select>
          <span v-if="form.errors.status" class="error">{{ form.errors.status }}</span>
        </div>
      </div>

      <!-- Stabilization Period -->
      <h2 class="section-header">Stabilization Period</h2>
      <div class="form-row">
        <div class="form-col">
          <label>Stabilization Start Date</label>
          <input type="date" v-model="form.stabilization_start_date" />
          <span v-if="form.errors.stabilization_start_date" class="error">{{ form.errors.stabilization_start_date }}</span>
        </div>
        <div class="form-col">
          <label>Stabilization End Date</label>
          <input type="date" v-model="form.stabilization_end_date" />
          <span v-if="form.errors.stabilization_end_date" class="error">{{ form.errors.stabilization_end_date }}</span>
        </div>
      </div>

      <!-- Warranty -->
      <h2 class="section-header">Warranty</h2>
      <div class="form-row">
        <div class="form-col">
          <label>Warranty Start Date</label>
          <input type="date" v-model="form.warranty_start_date" />
          <span v-if="form.errors.warranty_start_date" class="error">{{ form.errors.warranty_start_date }}</span>
        </div>
        <div class="form-col">
          <label>Warranty End Date</label>
          <input type="date" v-model="form.warranty_end_date" />
          <span v-if="form.errors.warranty_end_date" class="error">{{ form.errors.warranty_end_date }}</span>
        </div>
      </div>

      <!-- Support & Maintenance -->
      <h2 class="section-header">Support & Maintenance</h2>
      <div class="form-row">
        <div class="form-col">
          <label>Support Start Date</label>
          <input type="date" v-model="form.support_start_date" />
          <span v-if="form.errors.support_start_date" class="error">{{ form.errors.support_start_date }}</span>
        </div>
        <div class="form-col">
          <label>Support End Date</label>
          <input type="date" v-model="form.support_end_date" />
          <span v-if="form.errors.support_end_date" class="error">{{ form.errors.support_end_date }}</span>
        </div>
      </div>

      <!-- Buttons -->
      <div class="form-row full-width">
        <button type="submit" class="btn primary">Update Project</button>
        <Link :href="route('projects.index')" class="btn">Cancel</Link>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/inertia-vue3'
import { Link } from '@inertiajs/inertia-vue3'
import { Head } from '@inertiajs/vue3'
import { defineProps } from 'vue'
import { router } from '@inertiajs/inertia-vue3'

const props = defineProps({
  project: Object,
  clients: Array,
  developers: Array,
})

const form = useForm({
  project_name: props.project.project_name,
  client_id: props.project.client_id,
  description: props.project.description,
  developer_id: props.project.developer_id,
  start_date: props.project.start_date,
  end_date: props.project.end_date,
  status: props.project.status,
  stabilization_start_date: props.project.stabilization_start_date,
  stabilization_end_date: props.project.stabilization_end_date,
  warranty_start_date: props.project.warranty_start_date,
  warranty_end_date: props.project.warranty_end_date,
  support_start_date: props.project.support_start_date,
  support_end_date: props.project.support_end_date,
})

function updateProject() {
  form.put(route('projects.update', props.project.id), {
    preserveScroll: true,
    onSuccess: () => {
      router.visit(route('projects.index'))
    },
  })
}
</script>

<style scoped>
.container {
  background: #fff;
  padding: 2rem;
  max-width: 900px;
  margin: auto;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

h1 {
  font-size: 1.75rem;
  font-weight: bold;
  margin-bottom: 1.5rem;
  text-align: center;
  color: #2d3748;
}

.section-header {
  grid-column: span 2;
  font-size: 1.25rem;
  font-weight: 700;
  margin: 2rem 0 1rem;
  color: #2d3748;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.form-col {
  display: flex;
  flex-direction: column;
}

label {
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #4a5568;
}

input,
textarea,
select {
  padding: 0.5rem 0.75rem;
  border: 1px solid #cbd5e0;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.2s ease;
  width: 100%;
}

input:focus,
textarea:focus,
select:focus {
  border-color: #3182ce;
  outline: none;
  box-shadow: 0 0 0 1px #3182ce;
}

textarea {
  resize: vertical;
}

.error {
  color: #e53e3e;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.form-row.full-width {
  display: flex;
  justify-content: center;
  gap: 1rem;
}

.btn {
  padding: 0.75rem 1.25rem;
  font-size: 1rem;
  font-weight: bold;
  border-radius: 0.375rem;
  border: none;
  cursor: pointer;
  transition: background-color 0.2s ease;
  text-align: center;
  text-decoration: none;
}

.btn.primary {
  background-color: #3182ce;
  color: #fff;
}

.btn.primary:hover {
  background-color: #2b6cb0;
}

.btn:not(.primary) {
  background-color: #edf2f7;
  color: #4a5568;
}

.btn:not(.primary):hover {
  background-color: #e2e8f0;
}
</style>
