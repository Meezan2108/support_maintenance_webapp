<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Head } from "@inertiajs/vue3";


const { flash } = usePage().props;
const showSuccessMessage = ref(false);
const successMessage = ref('');
const { ticket } = usePage().props;

function priorityClass(priority) {
  if (priority === 'High') return 'priority-high';
  if (priority === 'Medium') return 'priority-medium';
  if (priority === 'Low') return 'priority-low';
  return '';
}

function statusClass(status) {
  if (status === 'Done') return 'status-done';
  if (status === 'Pending') return 'status-pending';
  if (status === 'Cancelled') return 'status-cancelled';
  return '';
}


onMounted(() => {
  if (flash && flash.success) {
    successMessage.value = flash.success;
    showSuccessMessage.value = true;

    // Auto-hide after 3 seconds
    setTimeout(() => {
      showSuccessMessage.value = false;
    }, 8000);
  }
});

</script>

<template>
<Head>
    <title>View Support Maintenance</title>
  </Head>
<!-- <div v-if="showSuccessMessage" class="popup-message">
  {{ successMessage }}
</div> -->

<div v-if="showSuccessMessage" class="popup-message">
  <span>{{ successMessage }}</span>
  <button class="close-btn" @click="showSuccessMessage = false">×</button>
</div>



  <div class="container">
    <div class="header">
      <h1>Support Ticket Details</h1>
      <div class="buttons">
        <!-- Use Inertia Link with correct href -->
        <Link :href="route('support-maintenance.edit', ticket.id)" class="btn edit">Edit</Link>
        <Link :href="route('support-maintenance.index')" class="btn back">Back</Link>
      </div>
    </div>

    <form class="form-grid">
      <div class="form-group">
        <label for="ticket_id">Ticket ID</label>
        <input id="ticket_id" type="text" readonly :value="ticket.ticket_id" />
      </div>

      <div class="form-group">
        <label for="project">Project</label>
        <input id="project" type="text" readonly :value="ticket.project?.project_name ?? '-'" />
      </div>

      <div class="form-group">
        <label for="client">Client</label>
        <input id="client" type="text" readonly :value="ticket.client?.name ?? '-'" />
      </div>

      <div class="form-group">
        <label for="request_date">Request Date</label>
        <input id="request_date" type="text" readonly :value="ticket.request_date" />
      </div>

      <div class="form-group">
        <label for="reported_to_client">Reported To (Client)</label>
        <input id="reported_to_client" type="text" readonly :value="ticket.reported_by ?? '-'" />
      </div>

      <div class="form-group">
        <label for="department_unit">Department/Unit</label>
        <input id="department_unit" type="text" readonly :value="ticket.department_unit || '-'" />
      </div>

      <div class="form-group">
        <label for="issue_type">Issue Type</label>
        <input id="issue_type" type="text" readonly :value="ticket.issue_type?.name ?? '-'" />
      </div>

      <div class="form-group">
        <label for="reported_to_st">Reported To (ST)</label>
        <input id="reported_to_st" type="text" readonly :value="ticket.reported_to?.full_name ?? '-'" />
      </div>

      <div class="form-group">
        <label for="priority">Priority</label>
        <input
          id="priority"
          type="text"
          readonly
          :value="ticket.priority"
          :class="priorityClass(ticket.priority)"
        />
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <input
          id="status"
          type="text"
          readonly
          :value="ticket.status"
          :class="statusClass(ticket.status)"
        />
      </div>

      <div class="form-group">
        <label for="starting_date">Starting Date</label>
        <input id="starting_date" type="text" readonly :value="ticket.starting_date || '-'" />
      </div>

      <div class="form-group">
        <label for="completion_date">Completion Date</label>
        <input id="completion_date" type="text" readonly :value="ticket.completion_date || '-'" />
      </div>

      <div class="form-group">
        <label for="duration_days">Duration (Days)</label>
        <input id="duration_days" type="text" readonly :value="ticket.duration_days ?? '-'" />
      </div>

      <div class="form-group full-width">
        <label for="solution_summary">Solution Summary</label>
        <textarea id="solution_summary" readonly>{{ ticket.solution_summary || '-' }}</textarea>
      </div>

      <div class="form-group">
        <label for="follow_up_required">Follow Up Required</label>
        <input id="follow_up_required" type="text" readonly :value="ticket.follow_up_required" />
      </div>

      <div class="form-group full-width">
        <label for="remarks">Remarks</label>
        <textarea id="remarks" readonly>{{ ticket.remarks || '-' }}</textarea>
      </div>
    </form>
  </div>
</template>

<style scoped>
/* Your existing styles */
.container {
  max-width: 900px;
  margin: 2rem auto;
  padding: 1.5rem;
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 0 20px rgb(0 0 0 / 0.1);
  font-family: system-ui, sans-serif;
  color: #333;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.header h1 {
  font-size: 2rem;
  font-weight: 600;
  color: #2563eb; /* blue-700 */
}

.buttons {
  display: flex;
  gap: 0.5rem;
}

.btn {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: background-color 0.2s ease-in-out;
}

.btn.edit {
  background-color: #fbbf24; /* yellow-400 */
}
.btn.edit:hover {
  background-color: #d97706; /* yellow-700 */
}

.btn.back {
  background-color: #6b7280; /* gray-500 */
}
.btn.back:hover {
  background-color: #374151; /* gray-700 */
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem 2rem;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group.full-width {
  grid-column: 1 / -1;
}

label {
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 0.25rem;
}

input[readonly],
textarea[readonly] {
  background-color: #f9fafb; /* gray-50 */
  border: 1px solid #d1d5db; /* gray-300 */
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
  font-size: 1rem;
  color: #4b5563; /* gray-600 */
  cursor: not-allowed;
  resize: none;
}

textarea {
  min-height: 5rem;
  line-height: 1.3;
}

.priority-high {
  color: #b91c1c; /* red-700 */
  font-weight: 700;
}

.priority-medium {
  color: #ca8a04; /* yellow-600 */
}

.priority-low {
  color: #15803d; /* green-700 */
}

.status-done {
  background-color: #bbf7d0; /* green-200 */
  color: #15803d; /* green-700 */
  border-radius: 0.375rem;
  padding-left: 0.5rem;
  font-weight: 600;
}

.status-pending {
  background-color: #fef3c7; /* yellow-200 */
  color: #ca8a04; /* yellow-700 */
  border-radius: 0.375rem;
  padding-left: 0.5rem;
  font-weight: 600;
}

.status-cancelled {
  background-color: #fecaca; /* red-200 */
  color: #b91c1c; /* red-700 */
  border-radius: 0.375rem;
  padding-left: 0.5rem;
  font-weight: 600;
}

.popup-message {
  position: relative;
  background-color: #d1fae5;
  color: #065f46;
  padding: 1rem 2rem 1rem 1rem;
  border-radius: 0.375rem;
  margin-bottom: 1rem;
  text-align: left;
  font-weight: 600;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.popup-message .close-btn {
  position: absolute;
  right: 0.75rem;
  top: 0.5rem;
  background: transparent;
  border: none;
  font-size: 1.25rem;
  cursor: pointer;
  color: #065f46;
  font-weight: bold;
}



.popup-message {
  background-color: #d1fae5; /* green-100 */
  color: #065f46; /* green-800 */
  padding: 1rem;
  border-radius: 0.375rem;
  margin-bottom: 1rem;
  text-align: center;
  font-weight: 600;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}


/* Responsive */
@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}
</style>
