<template>
  <div class="admin-users-wrapper">
    <div class="admin-users-container">
      <h1>Admin Users Management</h1>
      
      <!-- Permission Check -->
      <div v-if="!isSuperAdmin" class="permission-denied">
        <h2>Access Denied</h2>
        <p>You do not have permission to access this page.</p>
        <router-link to="/admin/dashboard" class="back-button">Back to Dashboard</router-link>
      </div>

      <!-- Loading State -->
      <div v-else-if="loading" class="loading-state">
        <div class="spinner"></div>
        <p>Loading admin users...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="error-state">
        <p>{{ error }}</p>
        <button @click="fetchAdmins" class="retry-button">Retry</button>
      </div>

      <!-- Content -->
      <div v-else>
        <!-- Add New Admin Button -->
        <button class="add-admin-button" @click="showAddAdminModal = true">
          Add New Admin
        </button>

        <!-- Admin Users Table -->
        <div class="admin-users-table">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Role</th>
                <th>Status</th>
                <th>Date Joined</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="admin in paginatedAdmins" :key="admin.A_ID">
                <td>{{ admin.A_ID }}</td>
                <td>{{ admin.username }}</td>
                <td>{{ admin.email }}</td>
                <td>{{ admin.first_name }} {{ admin.surname }}</td>
                <td>{{ admin.role }}</td>
                <td>{{ admin.status }}</td>
                <td>{{ formatDate(admin.date_joined) }}</td>
                <td>
                  <button @click="editAdmin(admin)" class="edit-button">Edit</button>
                  <button @click="deleteAdmin(admin.A_ID)" class="delete-button">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
          <button @click="prevPage" :disabled="currentPage === 1">Previous</button>
          <span class="page-info">Page {{ currentPage }} of {{ totalPages }}</span>
          <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
        </div>
      </div>

      <!-- Add/Edit Admin Modal -->
      <div v-if="showAddAdminModal || showEditAdminModal" class="modal">
        <div class="modal-content">
          <h2>{{ showAddAdminModal ? 'Add New Admin' : 'Edit Admin' }}</h2>
          <form @submit.prevent="showAddAdminModal ? addAdmin() : updateAdmin()">
            <div class="form-group">
              <label>Username</label>
              <input v-model="adminForm.username" required>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input v-model="adminForm.email" type="email" required>
            </div>
            <div class="form-group">
              <label>Password</label>
              <input v-model="adminForm.password" type="password" :required="showAddAdminModal" :placeholder="showAddAdminModal ? 'Required' : 'Leave blank to keep current password'">
            </div>
            <div class="form-group">
              <label>First Name</label>
              <input v-model="adminForm.first_name" required>
            </div>
            <div class="form-group">
              <label>Surname</label>
              <input v-model="adminForm.surname" required>
            </div>
            <div class="form-group">
              <label>Role</label>
              <select v-model="adminForm.role" required>
                <option value="admin">Admin</option>
                <option value="super_admin">Super Admin</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select v-model="adminForm.status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
            <div class="modal-buttons">
              <button type="submit" class="save-button" :disabled="loading">Save</button>
              <button type="button" @click="closeModal" class="cancel-button">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axiosClient from '@/services/axiosClient.js';
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import apiConfig from '@/config/apiURL.js';

export default {
  name: 'AdminUsers',
  setup() {
    const router = useRouter();
    const admins = ref([]);
    const showAddAdminModal = ref(false);
    const showEditAdminModal = ref(false);
    const loading = ref(false);
    const error = ref(null);
    const currentPage = ref(1);
    const itemsPerPage = ref(10);
    const adminForm = ref({
      username: '',
      email: '',
      password: '',
      first_name: '',
      surname: '',
      role: 'admin',
      status: 'active'
    });
    const editingAdminId = ref(null);

    const isSuperAdmin = computed(() => {
      return localStorage.getItem('admin_role') === 'super_admin';
    });

    const totalPages = computed(() => {
      return Math.ceil(admins.value.length / itemsPerPage.value);
    });

    const paginatedAdmins = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value;
      const end = start + itemsPerPage.value;
      return admins.value.slice(start, end);
    });

    const prevPage = () => {
      if (currentPage.value > 1) {
        currentPage.value--;
      }
    };

    const nextPage = () => {
      if (currentPage.value < totalPages.value) {
        currentPage.value++;
      }
    };

    const fetchAdmins = async () => {
      if (!isSuperAdmin.value) {
        return;
      }
      loading.value = true;
      error.value = null;
      try {
        console.log('Fetching admin users from:', apiConfig.admin.users);
        const token = localStorage.getItem('jwt');
        if (!token) {
          throw new Error('No admin token found');
        }
        
        const response = await axiosClient.get(apiConfig.admin.users, {
          headers: {
            'Authorization': `Bearer ${token}`
          }
        });
        
        console.log('Admin users response:', response.data);
        
        if (response.data && response.data.data) {
          admins.value = response.data.data;
          currentPage.value = 1;
        } else {
          throw new Error('Invalid response format');
        }
      } catch (error) {
        console.error('Error fetching admins:', error);
        if (error.response) {
          console.error('Error response:', error.response.data);
          if (error.response.status === 401) {
            error.value = 'Your session has expired. Please login again.';
            router.push('/admin-login');
          } else if (error.response.status === 403) {
            error.value = 'You do not have permission to access this page.';
          } else {
            error.value = `Failed to fetch admin users: ${error.response.data.message || 'Unknown error'}`;
          }
        } else if (error.request) {
          console.error('Error request:', error.request);
          error.value = 'Unable to connect to server. Please check your network connection.';
        } else {
          error.value = `Error occurred while fetching admin users: ${error.message}`;
        }
      } finally {
        loading.value = false;
      }
    };

    const addAdmin = async () => {
      if (!isSuperAdmin.value) {
        alert('You do not have permission to add admin users');
        return;
      }
      if (!validateForm()) {
        return;
      }
      loading.value = true;
      try {
        await axiosClient.post(apiConfig.admin.users, adminForm.value, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`
          }
        });
        closeModal();
        fetchAdmins();
        alert('Admin user added successfully');
      } catch (error) {
        console.error('Error adding admin:', error);
        if (error.response?.status === 401) {
          alert('Your session has expired. Please login again.');
          router.push('/admin-login');
        } else if (error.response?.status === 403) {
          alert('You do not have permission to add admin users');
        } else {
          alert('Failed to add admin user');
        }
      } finally {
        loading.value = false;
      }
    };

    const editAdmin = (admin) => {
      editingAdminId.value = admin.A_ID;
      adminForm.value = { ...admin };
      showEditAdminModal.value = true;
    };

    const updateAdmin = async () => {
      if (!isSuperAdmin.value) {
        alert('You do not have permission to update admin users');
        return;
      }
      if (!validateForm()) {
        return;
      }
      loading.value = true;
      try {
        const updateData = { ...adminForm.value };
        if (!updateData.password) {
          delete updateData.password;
        }
        
        await axiosClient.put(apiConfig.admin.user(editingAdminId.value), updateData, {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('jwt')}`
          }
        });
        closeModal();
        fetchAdmins();
        alert('Admin user updated successfully');
      } catch (error) {
        console.error('Error updating admin:', error);
        if (error.response?.status === 401) {
          alert('Your session has expired. Please login again.');
          router.push('/admin-login');
        } else if (error.response?.status === 403) {
          alert('You do not have permission to update admin users');
        } else {
          alert('Failed to update admin user');
        }
      } finally {
        loading.value = false;
      }
    };

    const deleteAdmin = async (id) => {
      if (!isSuperAdmin.value) {
        alert('You do not have permission to delete admin users');
        return;
      }
      if (!confirm('Are you sure you want to delete this admin user?')) {
        return;
      }

      loading.value = true;
      try {
        await axiosClient.delete(apiConfig.admin.user(id), {
          headers: {
            'Authorization': `Bearer ${localStorage.getItem('admin_token')}`
          }
        });
        fetchAdmins();
        alert('Admin user deleted successfully');
      } catch (error) {
        console.error('Error deleting admin:', error);
        if (error.response?.status === 401) {
          alert('Your session has expired. Please login again.');
          router.push('/admin-login');
        } else if (error.response?.status === 403) {
          alert('You do not have permission to delete admin users');
        } else {
          alert('Failed to delete admin user');
        }
      } finally {
        loading.value = false;
      }
    };

    const closeModal = () => {
      showAddAdminModal.value = false;
      showEditAdminModal.value = false;
      adminForm.value = {
        username: '',
        email: '',
        password: '',
        first_name: '',
        surname: '',
        role: 'admin',
        status: 'active'
      };
      editingAdminId.value = null;
    };

    const formatDate = (date) => {
      return new Date(date).toLocaleDateString();
    };

    const validateForm = () => {
      if (!adminForm.value.username) {
        alert('Username is required');
        return false;
      }
      if (!adminForm.value.email) {
        alert('Email is required');
        return false;
      }
      if (!adminForm.value.email.includes('@')) {
        alert('Invalid email format');
        return false;
      }
      if (showAddAdminModal.value && !adminForm.value.password) {
        alert('Password is required');
        return false;
      }
      if (adminForm.value.password && adminForm.value.password.length < 6) {
        alert('Password must be at least 6 characters long');
        return false;
      }
      if (!adminForm.value.first_name) {
        alert('First name is required');
        return false;
      }
      if (!adminForm.value.surname) {
        alert('Surname is required');
        return false;
      }
      if (!adminForm.value.role) {
        alert('Role is required');
        return false;
      }
      if (!adminForm.value.status) {
        alert('Status is required');
        return false;
      }
      return true;
    };

    onMounted(() => {
      if (!isSuperAdmin.value) {
        router.push('/admin/dashboard');
      } else {
        fetchAdmins();
      }
    });

    return {
      admins,
      showAddAdminModal,
      showEditAdminModal,
      adminForm,
      loading,
      error,
      isSuperAdmin,
      currentPage,
      totalPages,
      paginatedAdmins,
      prevPage,
      nextPage,
      addAdmin,
      editAdmin,
      updateAdmin,
      deleteAdmin,
      closeModal,
      formatDate
    };
  }
};
</script>

<style scoped>
.admin-users-wrapper {
  padding: 20px;
}

.admin-users-container {
  max-width: 1200px;
  margin: 0 auto;
}

.admin-users-table {
  margin-top: 20px;
  overflow-x: auto;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

th {
  background-color: #f5f5f5;
  font-weight: bold;
}

tr:hover {
  background-color: #f9f9f9;
}

button {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin: 0 4px;
  transition: all 0.3s ease;
}

button:hover {
  transform: scale(1.05);
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.add-admin-button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  font-size: 16px;
  margin-bottom: 20px;
}

.edit-button {
  background-color: #2196F3;
  color: white;
}

.delete-button {
  background-color: #f44336;
  color: white;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 30px;
  border-radius: 8px;
  width: 500px;
  max-width: 90%;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.modal-content h2 {
  margin-top: 0;
  margin-bottom: 20px;
  color: #333;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
  color: #555;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
  transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #2196F3;
  outline: none;
}

.modal-buttons {
  display: flex;
  justify-content: flex-end;
  margin-top: 30px;
  gap: 10px;
}

.save-button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
}

.cancel-button {
  background-color: #f44336;
  color: white;
  padding: 10px 20px;
}

@media (max-width: 768px) {
  .admin-users-container {
    padding: 10px;
  }

  .modal-content {
    width: 90%;
    padding: 20px;
  }

  th, td {
    padding: 8px;
    font-size: 14px;
  }

  button {
    padding: 6px 12px;
    font-size: 14px;
  }
}

.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px;
  color: #f44336;
}

.retry-button {
  background-color: #2196F3;
  color: white;
  margin-top: 20px;
}

.permission-denied {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px;
  text-align: center;
}

.permission-denied h2 {
  color: #f44336;
  margin-bottom: 20px;
}

.back-button {
  background-color: #2196F3;
  color: white;
  padding: 10px 20px;
  border-radius: 4px;
  text-decoration: none;
  margin-top: 20px;
}

.back-button:hover {
  background-color: #1976D2;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
  gap: 10px;
}

.page-info {
  margin: 0 10px;
  font-weight: bold;
}

.pagination button {
  background-color: #2196F3;
  color: white;
  padding: 8px 16px;
}

.pagination button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
</style>