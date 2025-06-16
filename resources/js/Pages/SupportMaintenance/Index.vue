<script setup>
import { ref, computed } from 'vue';
import { Head } from "@inertiajs/vue3";
import { usePage, router } from '@inertiajs/vue3';
import { Pencil, Trash2, Plus, Info } from 'lucide-vue-next';

const currentPage = ref(1);
const perPage = 10;
const records = usePage().props.records;
const search = ref('');
const filters = ref({
  ticket_id: '',
  project_name: '',
  client_name: '',
  issue_type: '',
  status: '',
});

const filteredRecords = computed(() => {
  return records.filter((r) => {
    return (
      (r.ticket_id?.toLowerCase().includes(filters.value.ticket_id.toLowerCase())) &&
      (r.project?.project_name?.toLowerCase().includes(filters.value.project_name.toLowerCase())) &&
      (r.project?.client?.name?.toLowerCase().includes(filters.value.client_name.toLowerCase())) &&
      (r.issue_type?.name?.toLowerCase().includes(filters.value.issue_type.toLowerCase())) &&
      (filters.value.status === '' || r.status?.toLowerCase() === filters.value.status.toLowerCase())
    );
  });
});

const paginatedRecords = computed(() => {
  const start = (currentPage.value - 1) * perPage;
  return filteredRecords.value.slice(start, start + perPage);
});

const totalPages = computed(() => {
  return Math.ceil(filteredRecords.value.length / perPage);
});

function goToPage(page) {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
  }
}

function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleString(undefined, {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

function viewRecord(id) {
  router.get(route('support-maintenance.show', id));
}

function editRecord(id) {
  router.get(route('support-maintenance.edit', id));
}

function deleteRecord(id) {
  if (confirm('Are you sure you want to delete this ticket?')) {
    router.delete(route('support-maintenance.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        alert('Ticket deleted successfully.');
        router.visit(route('support-maintenance.index'), {
          replace: true,
          preserveScroll: true,
        });
      },
      onError: () => {
        alert('Failed to delete the ticket.');
      }
    });
  }
}

function createRecord() {
  router.get(route('support-maintenance.create'));
}
</script>

<template>
  <Head>
    <title>Support & Maintenance</title>
  </Head>

  <div class="page-wrapper">
    <div class="header">
      <h1>Support & Maintenance Tickets</h1>
      <button @click="createRecord" class="create-btn">
        <Plus class="icon" /> Create Ticket
      </button>
    </div>

    <div class="card">
      <table class="record-table">
        <thead>
          <tr>
            <th>Actions</th>
            <th>Ticket ID</th>
            <th>Request Date</th>
            <th>Project Name</th>
            <th>Client Name</th>
            <th>Issue Type</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Updated At</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="filteredRecords.length === 0">
            <td :colspan="9" class="no-data">
              <Info class="no-data-icon" />
              No records found.
            </td>
          </tr>

          <tr v-for="record in paginatedRecords" :key="record.id">
            <td class="actions-cell">
              <div class="action-btns">
                <button @click="viewRecord(record.id)" class="icon-btn yellow" title="View">
                  <Info class="icon" />
                </button>
                <button @click="editRecord(record.id)" class="icon-btn blue" title="Edit">
                  <Pencil class="icon" />
                </button>
                <button @click="deleteRecord(record.id)" class="icon-btn red" title="Delete">
                  <Trash2 class="icon" />
                </button>
              </div>
            </td>
            <td>{{ record.ticket_id }}</td>
            <td>{{ record.request_date }}</td>
            <td>{{ record.project?.project_name }}</td>
            <td>{{ record.project?.client?.name }}</td>
            <td>{{ record.issue_type?.name }}</td>
            <td>
              <span :class="['badge', record.status?.toLowerCase()]">
                {{ record.status }}
              </span>
            </td>
            <td>{{ formatDate(record.created_at) }}</td>
            <td>{{ formatDate(record.updated_at) }}</td>
          </tr>
        </tbody>
      </table>

      <div class="pagination">
        <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1">Previous</button>

        <span v-for="page in totalPages" :key="page">
          <button
            @click="goToPage(page)"
            :class="{ active: currentPage === page }"
          >
            {{ page }}
          </button>
        </span>

        <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages">Next</button>
      </div>
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

.card {
  background: #fff;
  padding: 10px;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  overflow-x: auto;
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
  vertical-align: middle;
}

.actions-cell {
  padding: 0.5rem 0.75rem;
  text-align: center;
  vertical-align: middle;
}

.action-btns {
  display: flex;
  gap: 0.4rem;
  justify-content: center;
  align-items: center;
}

.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 6px;
  border-radius: 6px;
  cursor: pointer;
  border: none;
  width: 32px;
  height: 32px;
}

.icon-btn .icon {
  width: 16px;
  height: 16px;
}

.icon-btn.blue {
  background: #e0f0ff;
  color: #007bff;
}

.icon-btn.red {
  background: #ffe0e0;
  color: #dc3545;
}

.icon-btn.yellow {
  background: #fffacc;
  color: #856404;
}

.icon-btn:hover {
  filter: brightness(0.95);
}

.badge {
  padding: 0.3rem 0.6rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: bold;
  text-transform: uppercase;
  display: inline-block;
}

.badge.pending {
  background-color: #fff3cd;
  color: #856404;
}

.badge.done {
  background-color: #d4edda;
  color: #155724;
}

.badge.cancelled {
  background-color: #f8d7da;
  color: #721c24;
}

.pagination {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 1rem;
  flex-wrap: wrap;
}

.pagination button {
  padding: 6px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  background: white;
  cursor: pointer;
  font-size: 0.9rem;
}

.pagination button:hover:not(:disabled) {
  background-color: #f0f0f0;
}

.pagination button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.pagination .active {
  background-color: #1d4ed8;
  color: white;
  font-weight: bold;
}

.no-data {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
  font-style: italic;
}

.no-data-icon {
  width: 20px;
  height: 20px;
  vertical-align: middle;
  margin-right: 0.5rem;
}
</style>
