<template>
    <div class="custom-select" v-click-outside="() => closeSelect(task)">
        <div 
            class="input select-trigger" 
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
</template>

<script>
import { clickOutside } from '../directives/clickOutside'
import axios from 'axios'

export default {
    name: 'StatusSelect',
    directives: {
        clickOutside
    },
    props: {
        task: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            statusOptions: [
                { value: 'todo', text: 'К выполнению' },
                { value: 'in_progress', text: 'В процессе' },
                { value: 'done', text: 'Выполнено' }
            ]
        }
    },
    methods: {
        toggleSelect(task) {
            this.$emit('toggle-select', task)
        },
        closeSelect(task) {
            this.$emit('close-select', task)
        },
        async selectOption(task, status) {
            const oldStatus = task.status
            task.status = status
            task.isSelectOpen = false
            
            try {
                await axios.patch(`/tasks/${task.id}/status`, { status })
                this.$emit('status-updated', { task, status })
            } catch (error) {
                console.error('Error updating task:', error)
                task.status = oldStatus
                this.$emit('status-update-failed', { task, oldStatus })
            }
        },
        getStatusText(status) {
            const option = this.statusOptions.find(o => o.value === status)
            return option ? option.text : 'Неизвестный статус'
        }
    }
}
</script>

<style scoped>
.custom-select {
    position: relative;
    width: 150px;
}

.select-trigger {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.select-trigger.active {
    border-color: #9dff00;
    box-shadow: 0 0 0 1px #9dff00;
}

.select-arrow {
    transition: transform 0.2s;
    font-size: 12px;
    color: #9dff00;
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
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.select-option:hover {
    background: #383950;
}

.select-option.selected {
    background: #9dff00;
    color: #000;
}
</style> 