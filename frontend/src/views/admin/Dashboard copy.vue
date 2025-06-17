<template>
  <div class="user-list-container">
    <div v-for="user in userList" :key="user.id" class="user-card">
      <div class="user-info">
        <p class="user-name">{{ user.name }}</p>
        <p class="user-email">{{ user.email }}</p>
        <p class="user-role">{{ user.role }}</p>
      </div>
      <button @click.prevent="deleteUser(user.id)" class="delete-button">Delete</button>
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
    // Ensure response.data.data is an array
    userList.value = Array.isArray(response.data.data) ? response.data.data : [];
  } catch (error) {
    console.error("Failed to fetch user list:", error);
    // Optionally, provide user feedback here
  }
};

const deleteUser = async (id) => {
  try {
    await axiosInstance.delete(`admin/user/${id}`);
    // Refresh the list after a successful deletion
    await getUserList();
  } catch (error) {
    console.error(`Failed to delete user with id ${id}:`, error);
    // Optionally, provide user feedback here
  }
};
</script>

<style scoped>
.user-list-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  padding: 1rem;
  background-color: #f7fafc;
  font-family: sans-serif; /* Added for better typography */
}

.user-card {
  display: flex; /* Key Change: Use flexbox for alignment */
  justify-content: space-between; /* Key Change: Pushes info and button to opposite ends */
  align-items: center; /* Key Change: Vertically aligns items in the middle */
  background-color: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  padding: 1.5rem;
  transition: all 0.2s ease-in-out; /* Smoothed transition */
}

.user-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.user-info {
  display: flex;
  flex-direction: column; /* Stacks user info vertically */
  gap: 0.25rem; /* Adds small space between text elements */
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
  align-self: flex-start; /* Ensures the badge doesn't stretch */
}

.delete-button {
  background-color: #ef4444; /* Red color for destructive action */
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 0.375rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease-in-out;
}

.delete-button:hover {
  background-color: #dc2626; /* Darker red on hover */
}
</style>