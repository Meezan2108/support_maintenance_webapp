<template>
    <Head>
    <title>Create Support & Maintenance</title>
  </Head>
  <form @submit.prevent="submit" class="form-container">
    <h1 class="form-title">Create Support Maintenance Ticket</h1>

    <div class="grid-container">
      <!-- Ticket ID -->
      <div class="form-group">
        <label class="form-label">Ticket ID</label>
        <input type="text" v-model="form.ticket_id" disabled
               :class="['input', { 'input-error': form.errors.ticket_id }]" />
        <div v-if="form.errors.ticket_id" class="error-message">{{ form.errors.ticket_id }}</div>
      </div>

      <!-- Project Name -->
      <div class="form-group">
        <label class="form-label">Project Name</label>
        <select v-model="form.project_id" @change="handleProjectChange"
                :class="['input', { 'input-error': form.errors.project_id }]">
          <option value="" disabled>Select project</option>
          <option v-for="project in projects" :key="project.id" :value="project.id">
            {{ project.project_name }}
          </option>
        </select>
        <div v-if="form.errors.project_id" class="error-message">{{ form.errors.project_id }}</div>
      </div>

      <!-- Client Name -->
      <div class="form-group">
        <label class="form-label">Client Name</label>
        <input type="text" :value="selectedClientDisplayName" readonly
               :class="['input', { 'input-error': form.errors.client_id }]" />
        <div v-if="form.errors.client_id" class="error-message">{{ form.errors.client_id }}</div>
      </div>

      <!-- Request Date -->
      <div class="form-group">
        <label class="form-label">Request Date</label>
        <input type="date" v-model="form.request_date"
               :class="['input', { 'input-error': form.errors.request_date }]" />
        <div v-if="form.errors.request_date" class="error-message">{{ form.errors.request_date }}</div>
      </div>

      <!-- Reported By -->
      <div class="form-group">
        <label class="form-label">Reported By</label>
        <select v-model="form.reported_by" @change="handleCorrespondentChange"
                :class="['input', { 'input-error': form.errors.reported_by }]">
          <option value="" disabled>Select correspondent</option>
          <option v-for="person in filteredCorrespondents" :key="person.id" :value="person.name">
            {{ person.name }}
          </option>
        </select>
        <div v-if="form.errors.reported_by" class="error-message">{{ form.errors.reported_by }}</div>
      </div>

      <!-- Department / Unit -->
      <div class="form-group">
        <label class="form-label">Department/Unit</label>
        <input type="text" v-model="form.department_unit"
               :class="['input', { 'input-error': form.errors.department_unit }]" disabled />
        <div v-if="form.errors.department_unit" class="error-message">{{ form.errors.department_unit }}</div>
      </div>

      <!-- Issue Type -->
      <div class="form-group">
        <label class="form-label">Issue Type</label>
        <select v-model="form.issue_type_id"
                :class="['input', { 'input-error': form.errors.issue_type_id }]">
          <option value="" disabled>Select issue</option>
          <option v-for="type in issue_types" :key="type.id" :value="type.id">
            {{ type.name }}
          </option>
        </select>
        <div v-if="form.errors.issue_type_id" class="error-message">{{ form.errors.issue_type_id }}</div>
      </div>

      <!-- Reported To -->
      <div class="form-group">
        <label class="form-label">Reported To</label>
        <select v-model="form.reported_to"
                :class="['input', { 'input-error': form.errors.reported_to }]">
          <option value="" disabled>Select ST Member</option>
          <option v-for="member in st_members" :key="member.id" :value="member.id">
            {{ member.full_name }}
          </option>
        </select>
        <div v-if="form.errors.reported_to" class="error-message">{{ form.errors.reported_to }}</div>
      </div>

      <!-- Priority -->
      <div class="form-group">
        <label class="form-label">Priority</label>
        <select v-model="form.priority"
                :class="['input', { 'input-error': form.errors.priority }]">
          <option value="Low">Low</option>
          <option value="Medium">Medium</option>
          <option value="High">High</option>
        </select>
        <div v-if="form.errors.priority" class="error-message">{{ form.errors.priority }}</div>
      </div>

      <!-- Status -->
      <div class="form-group">
        <label class="form-label">Status</label>
        <select v-model="form.status"
                :class="['input', { 'input-error': form.errors.status }]">
          <option value="Pending">Pending</option>
          <option value="Done">Done</option>
          <option value="Cancelled">Cancelled</option>
        </select>
        <div v-if="form.errors.status" class="error-message">{{ form.errors.status }}</div>
      </div>

      <!-- Starting Date -->
      <div class="form-group">
        <label class="form-label">Starting Date</label>
        <input type="date" v-model="form.starting_date"
               :class="['input', { 'input-error': form.errors.starting_date }]" />
        <div v-if="form.errors.starting_date" class="error-message">{{ form.errors.starting_date }}</div>
      </div>

      <!-- Completion Date -->
      <div class="form-group">
        <label class="form-label">Completion Date</label>
        <input type="date" v-model="form.completion_date" @change="calculateDuration"
               :class="['input', { 'input-error': form.errors.completion_date }]" />
        <div v-if="form.errors.completion_date" class="error-message">{{ form.errors.completion_date }}</div>
      </div>

      <!-- Duration -->
      <div class="form-group">
        <label class="form-label">Duration (Days)</label>
        <input type="number" v-model="form.duration_days"
               :class="['input', { 'input-error': form.errors.duration_days }]" disabled />
        <div v-if="form.errors.duration_days" class="error-message">{{ form.errors.duration_days }}</div>
      </div>

      <!-- Solution Summary -->
      <div class="form-group col-span-2">
        <label class="form-label">Solution Summary</label>
        <textarea v-model="form.solution_summary" rows="4"
                  :class="['input textarea', { 'input-error': form.errors.solution_summary }]"></textarea>
        <div v-if="form.errors.solution_summary" class="error-message">{{ form.errors.solution_summary }}</div>
      </div>

      <!-- Follow Up Required -->
      <div class="form-group">
        <label class="form-label">Follow Up Required</label>
        <select v-model="form.follow_up_required"
                :class="['input', { 'input-error': form.errors.follow_up_required }]">
          <option value="Yes">Yes</option>
          <option value="No">No</option>
        </select>
        <div v-if="form.errors.follow_up_required" class="error-message">{{ form.errors.follow_up_required }}</div>
      </div>

      <!-- Remarks -->
      <div class="form-group col-span-2">
        <label class="form-label">Remarks</label>
        <textarea v-model="form.remarks" rows="3"
                  :class="['input textarea', { 'input-error': form.errors.remarks }]"></textarea>
        <div v-if="form.errors.remarks" class="error-message">{{ form.errors.remarks }}</div>
      </div>
    </div>

    <button type="submit" class="btn-submit">Submit</button>
  </form>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from "@inertiajs/vue3";
import { useForm, usePage, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const { projects, issue_types, st_members, ticket_id } = usePage().props;

const form = useForm({
  ticket_id: '',
  project_id: '',
  client_id: '',
  request_date: '',
  reported_by: '',
  department_unit: '',
  issue_type_id: '',
  description: '',
  reported_to: '',
  priority: 'Low',
  status: 'Pending',
  starting_date: '',
  completion_date: '',
  duration_days: '',
  solution_summary: '',
  follow_up_required: 'No',
  remarks: ''
});

const selectedClientDisplayName = computed(() => {
  const project = projects.find(p => p.id === form.project_id);
  return project?.client?.name ?? '';
});

const clientCorrespondents = ref([]);
const filteredCorrespondents = computed(() => clientCorrespondents.value);

function handleProjectChange() {
  const project = projects.find(p => p.id === form.project_id);
  if (project) {
    form.client_id = project.client_id;
    clientCorrespondents.value = project.client.correspondents || [];
  }
}

function handleCorrespondentChange() {
  const correspondent = clientCorrespondents.value.find(c => c.name === form.reported_by);
  if (correspondent) {
    form.department_unit = correspondent.department || '';
  }
}

function calculateDuration() {
  if (form.starting_date && form.completion_date) {
    const start = dayjs(form.starting_date);
    const end = dayjs(form.completion_date);
    form.duration_days = end.diff(start, 'day');
  }
}

onMounted(() => {
  form.ticket_id = ticket_id;
  handleProjectChange();
  handleCorrespondentChange();
});

function submit() {
  form.post(route('support-maintenance.store'));
}
</script>

<style scoped>
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
</style>
