<template>
    <div class="tasks-container">
        <div class="tasks-header">
            <h1>Задачи</h1>
        </div>
        <CreateTask class="create-task-button" @task-created="onTaskCreated" />

        <div class="tasks-list">
            <div v-if="loading" class="loading">
                Загрузка задач...
            </div>
            
            <div v-else class="task-item" v-for="task in tasks" :key="task.id">
                <!-- Режим просмотра -->
                <div v-if="!task.isEditing" class="task-content">
                    <div class="task-header">
                        <h3 class="task-title">{{ task.title }}</h3>
                        <button @click="startEdit(task)" class="edit-btn">✎</button>
                    </div>
                    <p class="task-description">{{ task.description }}</p>
                </div>

                <!-- Режим редактирования -->
                <TaskEdit 
                    v-else 
                    :task="task"
                    @task-updated="onTaskUpdated"
                    @edit-cancelled="cancelEdit(task)"
                />

                <div class="task-footer">
                    <StatusSelect 
                        :task="task"
                        @toggle-select="toggleSelect"
                        @close-select="closeSelect"
                        @status-updated="onStatusUpdated"
                        @status-update-failed="onStatusUpdateFailed"
                    />
                    <span class="task-date">{{ formatDate(task.deadline) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { clickOutside } from '../directives/clickOutside'
import StatusSelect from './StatusSelect.vue'
import CreateTask from './CreateTask.vue'
import TaskEdit from './TaskEdit.vue'

export default {
    name: 'TasksList',
    components: {
        StatusSelect,
        CreateTask,
        TaskEdit
    },
    directives: {
        clickOutside
    },
    data() {
        return {
            tasks: [],
            loading: true,
            hoveredOption: null,
            statusOptions: [
                { value: 'todo', text: 'К выполнению' },
                { value: 'in_progress', text: 'В процессе' },
                { value: 'done', text: 'Выполнено' }
            ]
        }
    },
    methods: {
        async fetchTasks() {
            try {
                const response = await axios.get('/tasks')
                this.tasks = response.data.map(task => ({
                    ...task,
                    isEditing: false,
                    editTitle: task.title,
                    editDescription: task.description,
                    editDeadline: task.deadline
                }))
                this.loading = false
            } catch (error) {
                console.error('Error fetching tasks:', error)
                this.loading = false
            }
        },
        startEdit(task) {
            task.isEditing = true;
            task.editTitle = task.title;
            task.editDescription = task.description;
            task.editDeadline = task.deadline;
        },
        async saveEdit(task) {
            try {
                await axios.patch(`/tasks/${task.id}`, {
                    title: task.editTitle,
                    description: task.editDescription,
                    deadline: task.editDeadline
                });

                task.title = task.editTitle;
                task.description = task.editDescription;
                task.deadline = task.editDeadline;
                task.isEditing = false;
            } catch (error) {
                console.error('Error updating task:', error);
            }
        },
        cancelEdit(task) {
            task.isEditing = false;
            task.editTitle = task.title;
            task.editDescription = task.description;
            task.editDeadline = task.deadline;
        },
        toggleSelect(task) {
            this.tasks.forEach(t => {
                if (t.id !== task.id) {
                    t.isSelectOpen = false;
                }
            });
            task.isSelectOpen = !task.isSelectOpen;
        },
        closeSelect(task) {
            if (task) {
                task.isSelectOpen = false;
            }
        },
        onStatusUpdated({ task, status }) {
            task.status = status;
            task.isSelectOpen = false;
        },
        onStatusUpdateFailed({ task, oldStatus }) {
            task.status = oldStatus;
        },
        formatDate(date) {
            if (!date) return '';
            const [year, month, day] = date.split('-');
            return `${day}.${month}.${year}`;
        },
        onTaskUpdated(updatedTask) {
            const index = this.tasks.findIndex(t => t.id === updatedTask.id)
            if (index !== -1) {
                this.tasks[index] = { ...this.tasks[index], ...updatedTask, isEditing: false }
            }
        },
        onTaskCreated(newTask) {
            this.tasks.push(newTask)
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
    margin-top: -24px;
}

.tasks-header {
    margin-bottom: 20px;
}

.tasks-header h1 {
    font-size: 24px;
    font-weight: 500;
    color: #fff;
    margin: 0;
    line-height: 1;
}

.create-task-button {
    margin-top: 30px;
    margin-bottom: 30px;
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

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.task-title {
    font-size: 16px;
    font-weight: 500;
    color: #fff;
    margin: 0;
}

.task-description {
    font-size: 14px;
    color: #9898a3;
    line-height: 1.4;
    margin: 0;
}

.task-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #383950;
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

.edit-btn {
    background: none;
    border: none;
    color: #9898a3;
    cursor: pointer;
    font-size: 16px;
    padding: 4px 8px;
    transition: color 0.2s;
}

.edit-btn:hover {
    color: #fff;
}

.edit-input {
    width: 100%;
    background: #383950;
    border: 1px solid #4a4b61;
    border-radius: 4px;
    padding: 8px 12px;
    color: #fff;
    font-size: 16px;
    margin-bottom: 8px;
}

.edit-textarea {
    width: 100%;
    background: #383950;
    border: 1px solid #4a4b61;
    border-radius: 4px;
    padding: 8px 12px;
    color: #fff;
    font-size: 14px;
    min-height: 80px;
    resize: vertical;
    margin-bottom: 12px;
}

.edit-buttons {
    display: flex;
    gap: 8px;
}

.save-btn, .cancel-btn {
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.save-btn {
    background: #9dff00;
    color: #000;
}

.save-btn:hover {
    background: #8ee600;
}

.cancel-btn {
    background: #4a4b61;
    color: #fff;
}

.cancel-btn:hover {
    background: #5a5b71;
}

.edit-input:focus, .edit-textarea:focus {
    outline: none;
    border-color: #9dff00;
}

.date-input {
    background: #383950;
    border: 1px solid #4a4b61;
    color: #fff;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 14px;
    width: 100%;
    margin-bottom: 10px;
}

.date-input:hover {
    border-color: #9dff00;
}

.date-input:focus {
    border-color: #9dff00;
    outline: none;
    box-shadow: 0 0 0 1px #9dff00;
}

/* Стилизация календаря (работает не во всех браузерах) */
.date-input::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}
</style>
