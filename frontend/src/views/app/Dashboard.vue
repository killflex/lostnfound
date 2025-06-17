<template>

  <div class="user-list-container">
    <div v-for="user in userList" :key="user.id" class="user-card">
      <div class="user-info">
        <p class="user-name">{{ user.name }}</p>
        <p class="user-email">{{ user.email }}</p>
        <p class="user-role">{{ user.role }}</p>
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
      userList.value = response.data.data;
    } catch (error) {
      console.error("Failed to fetch user list:", error);
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
  }

  .user-card {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
    transition: all 0.3s ease;
  }

  .user-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
  }

  .user-info p {
    margin: 0;
  }

  .user-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.25rem;
  }

  .user-email {
    font-size: 1rem;
    color: #718096;
    margin-bottom: 0.5rem;
  }

  .user-role {
    font-size: 0.875rem;
    color: #4a5568;
    background-color: #edf2f7;
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-weight: 500;
  }

</style>