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
            <th></th> <!-- For Actions -->
            <th>Project Name</th>
            <th>Client</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>End Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="projects.length === 0">
            <td colspan="6" class="no-data">
              <Info class="no-data-icon" />
              No projects found.
            </td>
          </tr>

          <tr v-for="project in projects" :key="project.id">
            <td class="actions">
              <Link :href="route('projects.show', project.id)" class="icon-btn yellow" title="View">
                <Info class="icon" />
              </Link>
              <Link :href="route('projects.edit', project.id)" class="icon-btn blue" title="Edit">
                <Pencil class="icon" />
              </Link>
              <button @click="deleteProject(project.id)" class="icon-btn red" title="Delete">
                <Trash2 class="icon" />
              </button>
            </td>
            <td>{{ project.project_name }}</td>
            <td>{{ project.client_name }}</td>
            <td>
              <span :class="['status-pill', project.status.toLowerCase().replace(/\s/g, '-')]">
                {{ project.status }}
              </span>
            </td>

            <td>{{ project.start_date }}</td>
            <td>{{ project.end_date }}</td>
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
import { Head } from "@inertiajs/vue3"
import { Pencil, Trash2, Plus, Info } from 'lucide-vue-next'

defineProps({ projects: Array })

function deleteProject(id) {
  if (confirm('Are you sure you want to delete this project?')) {
    Inertia.delete(route('projects.destroy', id), {
      preserveScroll: true,
      onSuccess: () => {
        Inertia.visit(route('projects.index'), { replace: true })
      },
      onError: (errors) => {
        alert('❌ Failed to delete project.')
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
  vertical-align: top;
}

.record-table tr:nth-child(even) {
  background: #fdfdfd;
}

.actions {
  display: flex;
  gap: 0.5rem;
  align-items: center;
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

.icon-btn.yellow {
  background: #efff9e;
  color: #495057;
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

.status-pill {
  display: inline-block;
  padding: 4px 12px;
  font-size: 0.75rem;
  font-weight: 600;
  border-radius: 9999px;
  font-weight: bold;
  text-transform: uppercase;
  white-space: nowrap;
}

.status-pill.planned {
  background-color: #f3f4f6; /* light gray */
  color: #6b7280;            /* gray-600 */
  border: 1px solid #d1d5db; /* border-gray-300 */
}

.status-pill.in-progress {
  background-color: #fef3c7; /* yellow-100 */
  color: #b45309;            /* yellow-700 */
  border: 1px solid #fde68a; /* yellow-300 */
}

.status-pill.completed {
  background-color: #d1fae5; /* green-100 */
  color: #065f46;            /* green-800 */
  border: 1px solid #6ee7b7; /* green-300 */
}


</style>
