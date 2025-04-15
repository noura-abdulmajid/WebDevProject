<template>
  <div class="admin-info-container">
    <h2>Admin Information</h2>
    
    <div v-if="isSuperAdmin" class="admin-list">
      <div v-for="admin in admins" :key="admin.id" class="admin-card">
        <div class="admin-header">
          <h3>{{ admin.name }}</h3>
          <span :class="['role-badge', admin.role]">{{ admin.role }}</span>
        </div>
        <div class="admin-details">
          <p><strong>Email:</strong> {{ admin.email }}</p>
          <p><strong>ID:</strong> {{ admin.id }}</p>
          <p><strong>Created At:</strong> {{ formatDate(admin.created_at) }}</p>
          <p><strong>Last Login:</strong> {{ formatDate(admin.last_login) }}</p>
        </div>
      </div>
    </div>
    
    <div v-else class="no-access">
      <p>You don't have permission to view this information</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axiosClient from '@/services/axiosClient.js';
import apiConfig from '@/config/apiURL.js';

const admins = ref([]);
const loading = ref(false);
const error = ref(null);

const isSuperAdmin = computed(() => {
  return localStorage.getItem('admin_role') === 'super_admin';
});

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleString('en-US');
};

const fetchAdmins = async () => {
  if (!isSuperAdmin.value) return;
  
  loading.value = true;
  error.value = null;
  
  try {
    const token = localStorage.getItem('jwt');
    const response = await axiosClient.get(apiConfig.admin.list, {
      headers: { Authorization: `Bearer ${token}` }
    });
    admins.value = response.data.admins;
  } catch (err) {
    console.error('Failed to fetch admin list:', err);
    error.value = 'Failed to fetch admin list';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  if (isSuperAdmin.value) {
    fetchAdmins();
  }
});
</script>

<style scoped>
.admin-info-container {
  padding: 20px;
}

.admin-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.admin-card {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.role-badge {
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 0.8em;
  font-weight: bold;
}

.role-badge.super_admin {
  background-color: #ff4444;
  color: white;
}

.role-badge.admin {
  background-color: #4CAF50;
  color: white;
}

.admin-details {
  font-size: 0.9em;
}

.admin-details p {
  margin: 8px 0;
}

.no-access {
  text-align: center;
  padding: 40px;
  background: #f5f5f5;
  border-radius: 8px;
  color: #666;
}
</style> 