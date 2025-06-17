<template>
  <div class="user-list-container">
    <div v-for="user in userList" :key="user.id" class="user-card">
      <div v-if="!user.isEditing" class="user-info">
        <p class="user-name">{{ user.name }}</p>
        <p class="user-email">{{ user.email }}</p>
        <p class="user-role">{{ user.role }}</p>
      </div>

      <div v-else class="user-edit-form">
        <input
          v-model="user.editData.name"
          class="edit-input"
          placeholder="Name"
          type="text"
        />
        <input
          v-model="user.editData.email"
          class="edit-input"
          placeholder="Email"
          type="email"
        />
        <input
          v-model="user.editData.role"
          class="edit-input"
          placeholder="Role"
          type="text"
        />
      </div>

      <div class="action-buttons">
        <template v-if="!user.isEditing">
          <button @click="startEdit(user)" class="edit-button">Edit</button>
          <button @click.prevent="deleteUser(user.id)" class="delete-button">Delete</button>
        </template>

        <template v-else>
          <button @click="saveUser(user)" class="save-button">Save</button>
          <button @click="cancelEdit(user)" class="cancel-button">Cancel</button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { axiosInstance } from '@/plugins/axios';
import { ref, onMounted } from 'vue';

const userList = ref([]);

onMounted(() => {
  getUserList();
});

const getUserList = async () => {
  try {
    const response = await axiosInstance.get("admin/user");
    const users = Array.isArray(response.data.data) ? response.data.data : [];
    userList.value = users.map(user => ({
      ...user,
      isEditing: false,
      editData: { ...user }
    }));
  } catch (error) {
    console.error("Gagal mendapat user");
  }
};

const startEdit = (user) => {
  user.editData = { ...user };
  user.isEditing = true;
};

const cancelEdit = (user) => {
  user.isEditing = false;
  user.editData = { ...user };
};

const saveUser = async (user) => {
  try {
    const response = await axiosInstance.put(`user/${user.id}`, {
      name: user.editData.name,
      email: user.editData.email,
      role: user.editData.role
    });

    if (response.data && response.data.data) {
      Object.assign(user, response.data.data);
    } else {
      Object.assign(user, user.editData);
    }

    user.isEditing = false;

  } catch (error) {
    console.error("Gagal mengupdate user");
  }
};

const deleteUser = async (id) => {
  try {
    await axiosInstance.delete(`admin/user/${id}`);
    await getUserList();
  } catch (error) {
    console.error("Gagal menghapus user");
  }
};
</script>

<style scoped>
.user-list-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 1rem;
  font-family: sans-serif;
}

.user-card {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  padding: 1.5rem;
  transition: all 0.2s ease-in-out;
}

.user-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.user-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.user-info p {
  margin: 0;
}

.user-name {
  font-size: 1.2rem;
  font-weight: 600;
  color: #2d3748;
}

.user-email {
  font-size: 1rem;
  color: #718096;
}

.user-role {
  font-size: 0.875rem;
  color: #4a5568;
  background-color: #edf2f7;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-weight: 500;
  align-self: flex-start;
}

.user-edit-form {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex-grow: 1;
  margin-right: 1rem;
}

.edit-input {
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
  transition: border-color 0.2s ease-in-out;
}

.edit-input:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}

.edit-button, .delete-button, .save-button, .cancel-button {
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 0.375rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
  font-size: 0.875rem;
}

.edit-button {
  background-color: #3b82f6;
  color: white;
}

.edit-button:hover {
  background-color: #2563eb;
}

.delete-button {
  background-color: #ef4444;
  color: white;
}

.delete-button:hover {
  background-color: #dc2626;
}

.save-button {
  background-color: #10b981;
  color: white;
}

.save-button:hover {
  background-color: #059669;
}

.cancel-button {
  background-color: #6b7280;
  color: white;
}

.cancel-button:hover {
  background-color: #4b5563;
}
</style>
