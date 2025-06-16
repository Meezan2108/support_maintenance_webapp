<template>
    <Head>
    <title>Edit Support Maintenance</title>
  </Head>
  <form @submit.prevent="submit" class="form-container">
    <h1 class="form-title">Edit Support Maintenance Ticket</h1>

    <div class="grid-container">
      <!-- Ticket ID -->
      <div class="form-group">
        <label class="form-label">Ticket ID</label>
        <input type="text" v-model="form.ticket_id" disabled class="input" />
      </div>

      <!-- Project Name -->
      <div class="form-group">
        <label class="form-label">Project Name</label>
        <select v-model="form.project_id" @change="handleProjectChange" class="input">
          <option value="" disabled>Select project</option>
          <option v-for="project in projects" :key="project.id" :value="project.id">
            {{ project.project_name }}
          </option>
        </select>
      </div>

      <!-- Client Name -->
      <div class="form-group">
        <label class="form-label">Client Name</label>
        <input type="text" :value="selectedClientDisplayName" readonly class="input" />
      </div>

      <!-- Request Date -->
      <div class="form-group">
        <label class="form-label">Request Date</label>
        <input type="date" v-model="form.request_date" class="input" />
      </div>

      <!-- Reported By -->
      <div class="form-group">
        <label class="form-label">Reported By</label>
        <select v-model="form.reported_by" @change="handleCorrespondentChange" class="input">
          <option value="" disabled>Select correspondent</option>
          <option v-for="person in filteredCorrespondents" :key="person.id" :value="person.name">
            {{ person.name }}
          </option>
        </select>
      </div>

      <!-- Department / Unit -->
      <div class="form-group">
        <label class="form-label">Department/Unit</label>
        <input type="text" v-model="form.department_unit" class="input" disabled />
      </div>

      <!-- Issue Type -->
      <div class="form-group">
        <label class="form-label">Issue Type</label>
        <select v-model="form.issue_type_id" class="input">
          <option value="" disabled>Select issue</option>
          <option v-for="type in issue_types" :key="type.id" :value="type.id">
            {{ type.name }}
          </option>
        </select>
      </div>

      <!-- Reported To -->
      <div class="form-group">
        <label class="form-label">Reported To</label>
        <select v-model="form.reported_to" class="input">
          <option value="" disabled>Select ST Member</option>
          <option v-for="member in st_members" :key="member.id" :value="member.id">
            {{ member.full_name }}
          </option>
        </select>
      </div>

      <!-- Priority -->
      <div class="form-group">
        <label class="form-label">Priority</label>
        <select v-model="form.priority" class="input">
          <option value="Low">Low</option>
          <option value="Medium">Medium</option>
          <option value="High">High</option>
        </select>
      </div>

      <!-- Status -->
      <div class="form-group">
        <label class="form-label">Status</label>
        <select v-model="form.status" class="input">
          <option value="Pending">Pending</option>
          <option value="Done">Done</option>
          <option value="Cancelled">Cancelled</option>
        </select>
      </div>

      <!-- Starting Date -->
      <div class="form-group">
        <label class="form-label">Starting Date</label>
        <input type="date" v-model="form.starting_date" class="input" />
      </div>

      <!-- Completion Date -->
      <div class="form-group">
        <label class="form-label">Completion Date</label>
        <input type="date" v-model="form.completion_date" @change="calculateDuration" class="input" />
      </div>

      <!-- Duration -->
      <div class="form-group">
        <label class="form-label">Duration (Days)</label>
        <input type="number" v-model="form.duration_days" class="input" disabled />
      </div>

      <!-- Solution Summary -->
      <div class="form-group col-span-2">
        <label class="form-label">Solution Summary</label>
        <textarea v-model="form.solution_summary" rows="4" class="input textarea"></textarea>
      </div>

      <!-- Follow Up Required -->
      <div class="form-group">
        <label class="form-label">Follow Up Required</label>
        <select v-model="form.follow_up_required" class="input">
          <option value="Yes">Yes</option>
          <option value="No">No</option>
        </select>
      </div>

      <!-- Remarks -->
      <div class="form-group col-span-2">
        <label class="form-label">Remarks</label>
        <textarea v-model="form.remarks" rows="3" class="input textarea"></textarea>
      </div>
    </div>

    <div class="mt-4">
      <button type="submit" class="btn-submit">Update</button>
    </div>
  </form>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { Head } from "@inertiajs/vue3";

const { ticket, projects, issue_types, st_members, reported_by_obj } = usePage().props;
const clientCorrespondents = ref([]);

// Initialize the form with ticket values
const form = useForm({
  ticket_id: ticket.ticket_id || '',
  project_id: ticket.project_id || '',
  client_id: ticket.client_id || '',
  request_date: ticket.request_date || '',
  reported_by: ticket.reported_by || '',
  department_unit: ticket.department_unit || '',
  issue_type_id: ticket.issue_type_id || '',
  description: ticket.description || '',
  reported_to: ticket.reported_to || '',
  priority: ticket.priority || 'Low',
  status: ticket.status || 'Pending',
  starting_date: ticket.starting_date || '',
  completion_date: ticket.completion_date || '',
  duration_days: ticket.duration_days || '',
  solution_summary: ticket.solution_summary || '',
  follow_up_required: ticket.follow_up_required || 'No',
  remarks: ticket.remarks || '',
});

// Display selected client name
const selectedClientDisplayName = computed(() => {
  return ticket?.project?.client?.name ?? '';
});

// List of correspondents to be shown in dropdown
const filteredCorrespondents = computed(() => clientCorrespondents.value);

// When project changes, update client_id and correspondent list
function handleProjectChange() {
  const project = projects.find(p => p.id === form.project_id);
  if (project) {
    form.client_id = project.client_id;
    let correspondents = [...(project.client?.correspondents ?? [])];

    // Push reported_by_obj only if it's not already in the list
    if (
      reported_by_obj &&
      !correspondents.some(c => String(c.id) === String(reported_by_obj.id))
    ) {
      correspondents.push(reported_by_obj);
    }

    clientCorrespondents.value = correspondents;
  }
}

// When correspondent changes, populate department/unit
function handleCorrespondentChange() {
  const correspondent = clientCorrespondents.value.find(c => c.id == form.reported_by);
  if (correspondent) {
    form.department_unit = correspondent.department || '';
  }
}

// Calculate duration in days from starting to completion date
function calculateDuration() {
  if (form.starting_date && form.completion_date) {
    const start = dayjs(form.starting_date);
    const end = dayjs(form.completion_date);
    form.duration_days = end.diff(start, 'day');
  }
}

// Initialize data on mount
onMounted(() => {
  if (ticket.project?.client?.correspondents) {
    let correspondents = [...ticket.project.client.correspondents];

    // Only push reported_by_obj if it's not already in the list
    if (
      reported_by_obj &&
      !correspondents.some(c => String(c.id) === String(reported_by_obj.id))
    ) {
      correspondents.push(reported_by_obj);
    }

    clientCorrespondents.value = correspondents;
  }

  // Sync related fields if they already exist
  if (form.project_id) {
    handleProjectChange();
  }

  if (form.reported_by) {
    handleCorrespondentChange();
  }
});

// Submit the form
function submit() {
  form.put(route('support-maintenance.update', ticket.id), {
    onSuccess: () => {
      router.visit(route('support-maintenance.show', ticket.id));
    },
  });
}
</script>


<!-- <script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { Head } from "@inertiajs/vue3";

const { ticket, projects, issue_types, st_members, reported_by_obj } = usePage().props;
const clientCorrespondents = ref([]);

const form = useForm({
  ticket_id: ticket.ticket_id || '',
  project_id: ticket.project_id || '',
  client_id: ticket.client_id || '',
  request_date: ticket.request_date || '',
  reported_by: ticket.reported_by || '',
  department_unit: ticket.department_unit || '',
  issue_type_id: ticket.issue_type_id || '',
  description: ticket.description || '',
  reported_to: ticket.reported_to || '',
  priority: ticket.priority || 'Low',
  status: ticket.status || 'Pending',
  starting_date: ticket.starting_date || '',
  completion_date: ticket.completion_date || '',
  duration_days: ticket.duration_days || '',
  solution_summary: ticket.solution_summary || '',
  follow_up_required: ticket.follow_up_required || 'No',
  remarks: ticket.remarks || '',
});

const selectedClientDisplayName = computed(() => {
  return ticket?.project?.client?.name ?? '';
});


const filteredCorrespondents = computed(() => clientCorrespondents.value);

function handleProjectChange() {
  const project = projects.find(p => p.id === form.project_id);
  if (project) {
    form.client_id = project.client_id;
    clientCorrespondents.value = project.client?.correspondents ?? [];
  }
}

function handleCorrespondentChange() {
  const correspondent = clientCorrespondents.value.find(c => c.id == form.reported_by);
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
  console.log('Ticket:', ticket);

  if (ticket.project?.client?.correspondents) {
  const correspondents = [...ticket.project.client.correspondents];

  if (
    reported_by_obj &&
    !correspondents.some(c => String(c.id) === String(reported_by_obj.id))
  ) {
    correspondents.push(reported_by_obj);
  }

  clientCorrespondents.value = correspondents;
}


  // if (ticket.project?.client?.correspondents) {
  //   clientCorrespondents.value = ticket.project.client.correspondents;

  //  const exists = clientCorrespondents.value.some(c => c.id === form.reported_by);
  //   if (!exists && reported_by_obj) {
  //     clientCorrespondents.value.push(reported_by_obj); // 👈 Now reported_by_obj is used
  //   }
  // }



    // ✅ Add this to sync form with project data on load
  if (form.project_id) {
    handleProjectChange();
  }

  if (form.reported_by) {
    handleCorrespondentChange();
  }
});


function submit() {
  form.put(route('support-maintenance.update', ticket.id), {
    onSuccess: () => {
      router.visit(route('support-maintenance.show', ticket.id)) // ✅ Go to show page instead
    },
  });
}

</script> -->

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
