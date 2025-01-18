<template>
    <div class="tasks-container">
        <div class="tasks-header">
            <h1>Задачи</h1>
        </div>

        <div class="tasks-list">
            <div v-if="loading" class="loading">
                Загрузка задач...
            </div>
            
            <div v-else class="task-item" v-for="task in tasks" :key="task.id">
                <div class="task-content">
                    <h3 class="task-title">{{ task.title }}</h3>
                    <p class="task-description">{{ task.description }}</p>
                </div>
                <div class="task-footer">
                    <select 
                        v-model="task.status"
                        class="status-select"
                        @change="updateTaskStatus(task.id, task.status)"
                    >
                        <option value="todo">К выполнению</option>
                        <option value="in_progress">В процессе</option>
                        <option value="done">Выполнено</option>
                    </select>
                    <span class="task-date">{{ task.deadline }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'TasksList',
    data() {
        return {
            tasks: [],
            loading: true
        }
    },
    methods: {
        async fetchTasks() {
            try {
                const response = await axios.get('/tasks')
                this.tasks = response.data
                this.loading = false
            } catch (error) {
                console.error('Error fetching tasks:', error)
                this.loading = false
            }
        },
        async updateTaskStatus(taskId, status) {
            try {
                await axios.patch(`/tasks/${taskId}`, { status })
            } catch (error) {
                console.error('Error updating task status:', error)
            }
        }
    },
    mounted() {
        this.fetchTasks()
    }
}
</script>

<style scoped>
.tasks-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.tasks-header {
    margin-bottom: 30px;
}

.tasks-header h1 {
    font-size: 24px;
    font-weight: 600;
    color: #fff;
}

.tasks-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.task-item {
    background: #2f3042;
    border-radius: 8px;
    padding: 16px 20px;
    border: 1px solid #383950;
    transition: box-shadow 0.2s ease;
}

.task-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.task-content {
    margin-bottom: 12px;
}

.task-title {
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    margin-bottom: 6px;
}

.task-description {
    font-size: 14px;
    color: #9898a3;
    line-height: 1.4;
}

.task-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #383950;
}

.status-select {
    background: #383950;
    border: none;
    color: #fff;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    outline: none;
}

.status-select:hover {
    background: #424360;
}

.task-date {
    color: #9898a3;
    font-size: 14px;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #9898a3;
    font-size: 16px;
}
</style>
