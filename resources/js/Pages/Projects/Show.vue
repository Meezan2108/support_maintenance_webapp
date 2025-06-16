<template>
  <Head>
    <title>View Project</title>
  </Head>

  <div class="project-card">
    <Link href="/projects" class="btn-back">Back to Project List</Link>

    <div class="card-header">
      <h1>{{ project.project_name }}</h1>
    </div>

    <div class="card-body">
      <div class="info-row">
        <span class="label">Client Name:</span>
        <span>{{ project.client?.name || 'No client' }}</span>
      </div>

      <div class="info-row">
        <span class="label">Status:</span>
        <span>{{ project.status }}</span>
      </div>

      <div class="info-row">
        <span class="label">Start Date:</span>
        <span>{{ project.start_date || 'N/A' }}</span>
      </div>

      <div class="info-row">
        <span class="label">End Date:</span>
        <span>{{ project.end_date || 'N/A' }}</span>
      </div>

      <div class="info-row">
        <span class="label">Developer:</span>
        <span>{{ project.developer?.name || 'N/A' }}</span>
      </div>

      <div class="info-row description">
        <span class="label">Description:</span>
        <p>{{ project.description || 'No description provided.' }}</p>
      </div>

      <!-- New Sections with Durations -->

      <hr class="section-divider" />

      <h2 class="section-header">Stabilization Period</h2>
      <div class="info-row">
        <span class="label">Stabilization Start Date:</span>
        <span>{{ project.stabilization_start_date || 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Stabilization End Date:</span>
        <span>{{ project.stabilization_end_date || 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Duration:</span>
        <span>{{ stabilizationDuration !== null ? stabilizationDuration + ' days' : 'N/A' }}</span>
      </div>

      <hr class="section-divider" />

      <h2 class="section-header">Warranty</h2>
      <div class="info-row">
        <span class="label">Warranty Start Date:</span>
        <span>{{ project.warranty_start_date || 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Warranty End Date:</span>
        <span>{{ project.warranty_end_date || 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Duration:</span>
        <span>{{ warrantyDuration !== null ? warrantyDuration + ' days' : 'N/A' }}</span>
      </div>

      <hr class="section-divider" />

      <h2 class="section-header">Support & Maintenance</h2>
      <div class="info-row">
        <span class="label">Support Start Date:</span>
        <span>{{ project.support_start_date || 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Support End Date:</span>
        <span>{{ project.support_end_date || 'N/A' }}</span>
      </div>
      <div class="info-row">
        <span class="label">Duration:</span>
        <span>{{ supportDuration !== null ? supportDuration + ' days' : 'N/A' }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  project: Object,
})

function dateDiffInDays(start, end) {
  if (!start || !end) return null
  const startDate = new Date(start)
  const endDate = new Date(end)
  const diffTime = endDate - startDate
  if (diffTime < 0) return null
  return Math.ceil(diffTime / (1000 * 60 * 60 * 24))
}

const stabilizationDuration = computed(() => 
  dateDiffInDays(props.project.stabilization_start_date, props.project.stabilization_end_date)
)

const warrantyDuration = computed(() => 
  dateDiffInDays(props.project.warranty_start_date, props.project.warranty_end_date)
)

const supportDuration = computed(() => 
  dateDiffInDays(props.project.support_start_date, props.project.support_end_date)
)
</script>

<style scoped>
.project-card {
  max-width: 800px;
  margin: 40px auto;
  background: #fff;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
  font-family: 'Segoe UI', sans-serif;
}

.card-header {
  margin-bottom: 1.5rem;
  margin-top: 20px;
  background-color: maroon;
  padding: 1rem;
  border-radius: 8px;
}

.card-header h1 {
  font-size: 1.75rem;
  font-weight: bold;
  color: #ffffff;
}

.subheading {
  font-size: 1rem;
  color: #718096;
  margin-top: 0.25rem;
}

.card-body {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.info-row {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  border-bottom: 1px solid #edf2f7;
  font-size: 1rem;
  color: #2d3748;
}

.label {
  font-weight: 600;
  color: #4a5568;
  min-width: 160px;
}

.description {
  flex-direction: column;
}

.description p {
  margin-top: 0.5rem;
  color: #4a5568;
  line-height: 1.5;
}

.card-footer {
  margin-top: 10px;
  text-align: right;
  margin-bottom: 30px;
}

.btn-back {
  background-color: #4a5568;
  color: #fff;
  padding: 0.6rem 1.25rem;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
  font-size: 0.95rem;
  transition: background-color 0.2s ease;
}

.btn-back:hover {
  background-color: #2d3748;
}

.section-divider {
  margin: 2rem 0;
  border: 1px solid #ddd;
}

.section-header {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 1rem;
  color: #e53e3e;
}
</style>
