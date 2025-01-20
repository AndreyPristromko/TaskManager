<template>
    <button @click="confirmDelete" class="delete-btn">
        <span class="delete-icon">×</span>
    </button>
</template>

<script>
import axios from 'axios'

export default {
    name: 'DeleteTask',
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    methods: {
        async confirmDelete() {
            if (confirm('Вы уверены, что хотите удалить эту задачу?')) {
                try {
                    await axios.delete(`/tasks/${this.task.id}`)
                    this.$emit('task-deleted', this.task.id)
                } catch (error) {
                    console.error('Error deleting task:', error)
                }
            }
        }
    }
}
</script>

<style scoped>
.delete-btn {
    background: none;
    border: none;
    color: #9898a3;
    cursor: pointer;
    padding: 4px 8px;
    transition: color 0.2s;
    font-size: 18px;
    line-height: 1;
}

.delete-btn:hover {
    color: #ff4757;
}

.delete-icon {
    display: block;
}
</style> 