<template>
    <div class="edit-form">
        <input 
            type="text" 
            v-model="editedTask.title" 
            class="input"
            placeholder="Название задачи"
        >
        <textarea 
            v-model="editedTask.description" 
            class="input"
            placeholder="Описание задачи"
        ></textarea>
        <input 
            type="date" 
            v-model="editedTask.deadline"
            class="input"
        >
        <div class="edit-buttons">
            <button @click="saveEdit" class="btn btn-primary">Сохранить</button>
            <button @click="cancelEdit" class="btn btn-secondary">Отмена</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    name: 'TaskEdit',
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            editedTask: {
                title: this.task.title,
                description: this.task.description,
                deadline: this.task.deadline
            }
        }
    },
    methods: {
        async saveEdit() {
            try {
                await axios.patch(`/tasks/${this.task.id}`, {
                    title: this.editedTask.title,
                    description: this.editedTask.description,
                    deadline: this.editedTask.deadline
                })

                this.$emit('task-updated', {
                    ...this.task,
                    title: this.editedTask.title,
                    description: this.editedTask.description,
                    deadline: this.editedTask.deadline
                })
            } catch (error) {
                console.error('Error updating task:', error)
            }
        },
        cancelEdit() {
            this.$emit('edit-cancelled')
        }
    }
}
</script>

<style scoped>
.edit-form {
    margin-top: 10px;
}

.input {
    width: 100%;
    margin-bottom: 8px;
}

.edit-buttons {
    display: flex;
    gap: 8px;
}
</style> 