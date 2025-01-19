<template>
    <div class="app-layout">
        <!-- Боковое меню -->
        <aside class="sidebar">
            <div class="logo">TaskFlow</div>
            
            <nav class="main-nav">
                <div class="nav-section">
                    <a href="#" class="nav-item active">
                        <span class="nav-icon" style="color: #9dff00">✓</span>
                        Задачи
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Основной контент -->
        <main class="main-content">
            <tasks-list />
        </main>
    </div>
</template>

<script>
import TasksList from '../components/TasksList.vue'

export default {
    name: 'MainLayout',
    components: {
        TasksList
    }
}
</script>

<style scoped>
.app-layout {
    display: flex;
    min-height: 100vh;
    background-color: #2b2c37;
    color: #ffffff;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.sidebar {
    width: 300px;
    background-color: #23242b;
    padding: 20px;
    display: flex;
    flex-direction: column;
    border-right: 1px solid #383838;
}

.logo {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 30px;
    color: #ffffff;
}

.main-nav {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.nav-section {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.nav-item {
    display: flex;
    align-items: center;
    padding: 12px;
    color: #bbb;
    text-decoration: none;
    border-radius: 6px;
    gap: 12px;
    transition: all 0.2s;
    font-size: 16px;
}

.nav-item:hover {
    background-color: #323338;
    color: #fff;
}

.nav-item.active {
    background-color: #323338;
    color: #fff;
}

.nav-icon {
    font-size: 18px;
    width: 24px;
}

.main-content {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .sidebar {
        width: 250px;
    }
}
</style>
