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
                <!-- Режим просмотра -->
                <div v-if="!task.isEditing" class="task-content">
                    <div class="task-header">
                        <h3 class="task-title">{{ task.title }}</h3>
                        <button @click="startEdit(task)" class="edit-btn">✎</button>
                    </div>
                    <p class="task-description">{{ task.description }}</p>
                </div>

                <!-- Режим редактирования -->
                <div v-else class="task-content">
                    <div class="edit-form">
                        <input 
                            type="text" 
                            v-model="task.editTitle" 
                            class="edit-input"
                            placeholder="Название задачи"
                        >
                        <textarea 
                            v-model="task.editDescription" 
                            class="edit-input"
                            placeholder="Описание задачи"
                        ></textarea>
                        <input 
                            type="date" 
                            v-model="task.editDeadline"
                            class="edit-input date-input"
                        >
                        <div class="edit-buttons">
                            <button @click="saveEdit(task)" class="save-btn">Сохранить</button>
                            <button @click="cancelEdit(task)" class="cancel-btn">Отмена</button>
                        </div>
                    </div>
                </div>

                <div class="task-footer">
                    <div class="custom-select" v-click-outside="() => closeSelect(task)">
                        <div 
                            class="select-trigger" 
                            @click.stop="toggleSelect(task)"
                            :class="{ 'active': task.isSelectOpen }"
                        >
                            <span>{{ getStatusText(task.status) }}</span>
                            <span class="select-arrow" :class="{ 'open': task.isSelectOpen }">▼</span>
                        </div>
                        <div class="select-options" v-if="task.isSelectOpen" @click.stop>
                            <div 
                                v-for="option in statusOptions" 
                                :key="option.value"
                                class="select-option"
                                @click="selectOption(task, option.value)"
                                :class="{ 'selected': task.status === option.value }"
                            >
                                {{ option.text }}
                            </div>
                        </div>
                    </div>
                    <span class="task-date">{{ formatDate(task.deadline) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { clickOutside } from '../directives/clickOutside'

export default {
    name: 'TasksList',
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
        async selectOption(task, status) {
            // Сохраняем старый статус на случай ошибки
            const oldStatus = task.status;
            
            // Сразу обновляем UI
            task.status = status;
            task.isSelectOpen = false;
            
            try {
                await axios.patch(`/tasks/${task.id}/status`, { 
                    status: status 
                });
            } catch (error) {
                // В случае ошибки возвращаем старый статус
                console.error('Error updating task:', error);
                task.status = oldStatus;
            }
        },
        closeSelect(task) {
            if (task) {
                task.isSelectOpen = false;
            }
        },
        getStatusText(status) {
            switch (status) {
                case 'todo':
                    return 'К выполнению';
                case 'in_progress':
                    return 'В процессе';
                case 'done':
                    return 'Выполнено';
                default:
                    return 'Неизвестный статус';
            }
        },
        formatDate(date) {
            if (!date) return '';
            const [year, month, day] = date.split('-');
            return `${day}.${month}.${year}`;
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

.custom-select {
    position: relative;
    min-width: 140px;
}

.select-trigger {
    background: #383950;
    border: 1px solid #4a4b61;
    color: #fff;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s ease;
}

.select-trigger:hover {
    border-color: #9dff00;
    background-color: #424360;
}

.select-arrow {
    color: #9dff00;
    font-size: 12px;
    margin-left: 8px;
    transition: transform 0.2s ease;
}

.select-arrow.open {
    transform: rotate(180deg);
}

.select-options {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #2f3042;
    border: 1px solid #4a4b61;
    border-radius: 4px;
    overflow: hidden;
    z-index: 100;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.select-option {
    padding: 10px 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #fff;
    font-size: 14px;
}

.select-option:hover {
    background: #424360;
    color: #9dff00;
}

.select-option.selected {
    background: #424360;
    color: #9dff00;
}

/* Анимации для выпадающего списка */
.dropdown-enter-active,
.dropdown-leave-active {
    transition: all 0.2s ease;
}

.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-4px);
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

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
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
