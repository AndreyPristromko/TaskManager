<template>
    <div class="create-task">
        <button @click="showForm = true" class="create-btn">+ Новая задача</button>

        <div v-if="showForm" class="create-task-form">
            <input 
                type="text" 
                v-model="newTask.title" 
                class="edit-input"
                placeholder="Название задачи"
            >
            <textarea 
                v-model="newTask.description" 
                class="edit-input"
                placeholder="Описание задачи"
            ></textarea>
            <input 
                type="date" 
                v-model="newTask.deadline"
                class="edit-input date-input"
            >
            <div class="edit-buttons">
                <button @click="createTask" class="save-btn">Создать</button>
                <button @click="cancelCreate" class="cancel-btn">Отмена</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CreateTask',
    data() {
        return {
            showForm: false,
            newTask: {
                title: '',
                description: '',
                deadline: '',
                status: 'todo'
            }
        }
    },
    methods: {
        async createTask() {
            try {
                const response = await axios.post('/tasks', this.newTask);
                this.$emit('task-created', response.data);
                this.showForm = false;
                this.resetForm();
            } catch (error) {
                console.error('Error creating task:', error);
            }
        },

        cancelCreate() {
            this.showForm = false;
            this.resetForm();
        },

        resetForm() {
            this.newTask = {
                title: '',
                description: '',
                deadline: '',
                status: 'todo'
            };
        }
    }
}
</script>

<style scoped>
.create-task {
    margin-top: 20px;
}

.create-btn {
    background: #9dff00;
    color: #000;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.create-btn:hover {
    background: #8ee600;
}

.create-task-form {
    background: #2f3042;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
    border: 1px solid #383950;
}

.edit-input {
    background: #383950;
    border: 1px solid #4a4b61;
    color: #fff;
    padding: 8px 12px;
    border-radius: 4px;
    font-size: 14px;
    width: 100%;
    margin-bottom: 10px;
}

.edit-input:hover {
    border-color: #9dff00;
}

.edit-input:focus {
    border-color: #9dff00;
    outline: none;
    box-shadow: 0 0 0 1px #9dff00;
}

.date-input::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}

.edit-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 10px;
}

.save-btn, .cancel-btn {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
}

.save-btn {
    background: #9dff00;
    color: #000;
    border: none;
}

.save-btn:hover {
    background: #8ee600;
}

.cancel-btn {
    background: transparent;
    color: #fff;
    border: 1px solid #4a4b61;
}

.cancel-btn:hover {
    border-color: #9dff00;
    color: #9dff00;
}
</style> 