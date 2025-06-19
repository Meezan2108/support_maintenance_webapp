<template>
  <Head>
    <title>List of Projects</title>
  </Head>

  <div class="page-wrapper">
    <div class="header">
      <h1>Projects List</h1>
      <Link :href="route('projects.create')" class="create-btn">
        <Plus class="icon" /> Add Project
      </Link>
    </div>

    <div class="card">
      <table class="record-table">
        <thead>
          <tr>
            <th>Actions</th>
            <th>Project Name</th>
            <th>Client</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Created At</th>
            <th>Updated At</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="projects.length === 0">
            <td colspan="8" class="no-data">
              <Info class="no-data-icon" />
              No projects found.
            </td>
          </tr>

          <tr v-for="project in projects" :key="project.id">
            <td>
              <div class="actions">
                <Link :href="route('projects.show', project.id)" class="icon-btn yellow" title="View">
                  <Info class="icon" />
                </Link>
                <Link :href="route('projects.edit', project.id)" class="icon-btn blue" title="Edit">
                  <Pencil class="icon" />
                </Link>
                <button @click="deleteProject(project.id)" class="icon-btn red" title="Delete">
                  <Trash2 class="icon" />
                </button>

                <button
                  v-if="project.status === 'Planned'"
                  @click="updateStatus(project.id, 'In Progress')"
                  class="icon-btn orange"
                  title="Mark as In Progress"
                >
                  <Loader class="icon spin" />
                </button>

                <button
                  v-else-if="project.status === 'In Progress'"
                  @click="updateStatus(project.id, 'Completed')"
                  class="icon-btn green"
                  title="Mark as Completed"
                >
                  <Check class="icon" />
                </button>
              </div>
            </td>

            <td>{{ project.project_name }}</td>
            <td>{{ project.client_name }}</td>
            <td>
              <span :class="['status-pill', project.status.toLowerCase().replace(/\s/g, '-')]">
                {{ project.status }}
              </span>
            </td>
            <td>{{ formatDate(project.start_date) }}</td>
            <td>{{ formatDate(project.end_date) }}</td>
            <td>{{ formatDateTime(project.created_at) }}</td>
            <td>{{ formatDateTime(project.updated_at) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { route } from 'ziggy-js'
import { Head } from '@inertiajs/vue3'
import { Check, Loader, Pencil, Trash2, Plus, Info } from 'lucide-vue-next'

// Day.js + timezone support
import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'
import timezone from 'dayjs/plugin/timezone'

dayjs.extend(utc)
dayjs.extend(timezone)

const localZone = 'Asia/Brunei' // Set to your local timezone

defineProps({ projects: Array })

function formatDate(date) {
  return dayjs.utc(date).tz(localZone).format('MMMM D, YYYY')
}

function formatDateTime(date) {
  return dayjs.utc(date).tz(localZone).format('MMMM D, YYYY — h:mm A')
}

function deleteProject(id) {
  if (confirm('Are you sure you want to delete this project?')) {
    Inertia.delete(route('projects.destroy', id), {
      preserveScroll: true,
      onSuccess: () => Inertia.visit(route('projects.index'), { replace: true }),
      onError: (errors) => {
        alert('❌ Failed to delete project.')
        console.error(errors)
      }
    })
  }
}

function updateStatus(id, newStatus) {
  if (confirm(`Are you sure you want to mark this project as "${newStatus}"?`)) {
    Inertia.put(route('projects.update-status', id), { status: newStatus }, {
      onSuccess: () => Inertia.reload({ only: ['projects'] }),
      onError: (errors) => {
        alert('❌ Failed to update project status.')
        console.error(errors)
      }
    })
  }
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
  text-decoration: none;
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
  height: 72px;
}

.record-table tr:nth-child(even) {
  background: #fdfdfd;
}

.actions {
  display: flex;
  flex-wrap: nowrap;
  gap: 0.4rem;
  align-items: center;
  justify-content: flex-start;
  white-space: nowrap;
  min-width: 240px;
}

.icon-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  min-width: 32px;
  min-height: 32px;
  max-height: 32px;
  border-radius: 6px;
  padding: 4px;
  cursor: pointer;
  border: none;
  font-weight: 600;
  font-size: 0.85rem;
  transition: background 0.2s ease;
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

.icon-btn.yellow {
  background: #efff9e;
  color: #495057;
}

.icon-btn.green {
  background: #d1fae5;
  color: #065f46;
}

.icon-btn.orange {
  background: #ffedd5;
  color: #c2410c;
}

.icon-btn:hover {
  filter: brightness(0.95);
}

.icon.spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
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

.status-pill {
  display: inline-block;
  padding: 4px 12px;
  font-size: 0.75rem;
  font-weight: 600;
  border-radius: 9999px;
  text-transform: uppercase;
  white-space: nowrap;
}

.status-pill.planned {
  background-color: #f3f4f6;
  color: #6b7280;
  border: 1px solid #d1d5db;
}

.status-pill.in-progress {
  background-color: #fef3c7;
  color: #b45309;
  border: 1px solid #fde68a;
}

.status-pill.completed {
  background-color: #d1fae5;
  color: #065f46;
  border: 1px solid #6ee7b7;
}
</style>
