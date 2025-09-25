<template>
  <div class="library-layout">
    <aside class="sidebar">
      <div class="sidebar-header">
        <span class="logo">📚</span>
        <span class="app-title">Library</span>
      </div>
      <nav class="sidebar-nav">
        <inertia-link href="/library" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '') === 'library' }">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </inertia-link>
        <inertia-link href="/library/books" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '').startsWith('library/books') }">
          <i class="fas fa-book"></i> Books
        </inertia-link>
        <inertia-link href="/library/categories" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '').startsWith('library/categories') }">
          <i class="fas fa-tags"></i> Categories
        </inertia-link>
        <inertia-link href="/library/authors" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '').startsWith('library/authors') }">
          <i class="fas fa-user-edit"></i> Authors
        </inertia-link>
        <inertia-link href="/library/publishers" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '').startsWith('library/publishers') }">
          <i class="fas fa-building"></i> Publishers
        </inertia-link>
        <inertia-link href="/library/members" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '').startsWith('library/members') }">
          <i class="fas fa-users"></i> Members
        </inertia-link>
        <inertia-link href="/library/borrows" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '').startsWith('library/borrows') }">
          <i class="fas fa-exchange-alt"></i> Borrows
        </inertia-link>
        <inertia-link href="/library/reports/borrowed" class="sidebar-link" :class="{ active: page.url.replace(/^\//, '').startsWith('library/reports') }">
          <i class="fas fa-chart-bar"></i> Reports
        </inertia-link>
      </nav>
    </aside>
    <div class="main-content">
      <header class="topbar">
        <div class="topbar-left">
          <span class="school-name">Dunco School</span>
        </div>
        <div class="flex-1 flex justify-center">
          <SmartSearchBar />
        </div>
        <div class="topbar-right">
          <span class="user-avatar">DS</span>
        </div>
      </header>
      <main class="content-area">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import SmartSearchBar from '@/Components/SmartSearchBar.vue';
const page = usePage();
console.log('Current Inertia page.url:', page.url);
</script>

<style scoped>
.library-layout {
  display: flex;
  min-height: 100vh;
  background: #f5f7fa;
}
.sidebar {
  width: 220px;
  background: #0d1333;
  color: #fff;
  display: flex;
  flex-direction: column;
  padding: 1.5rem 0;
  box-shadow: 2px 0 8px rgba(30, 64, 175, 0.08);
}
.sidebar-header {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-size: 1.3rem;
  font-weight: bold;
  padding: 0 1.5rem 2rem 1.5rem;
}
.logo {
  font-size: 2rem;
}
.app-title {
  letter-spacing: 1px;
  color: #42a5f5;
}
.sidebar-nav {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  padding: 0 1.5rem;
}
.sidebar-link {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
  color: #90caf9;
  text-decoration: none;
  font-weight: 500;
  transition: background 0.2s, color 0.2s;
}
.sidebar-link.active, .sidebar-link:hover {
  background: #1e88e5;
  color: #fff;
}
.main-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}
.topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fff;
  padding: 1rem 2rem;
  box-shadow: 0 2px 8px rgba(30, 64, 175, 0.04);
}
.school-name {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1a237e;
  letter-spacing: 1px;
}
.user-avatar {
  background: #1e88e5;
  color: #fff;
  border-radius: 50%;
  width: 2.2rem;
  height: 2.2rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
  box-shadow: 0 2px 8px rgba(30, 64, 175, 0.08);
}
.content-area {
  flex: 1;
  padding: 2.5rem 2rem;
  background: #f5f7fa;
}
@media (max-width: 900px) {
  .sidebar {
    width: 60px;
    padding: 1rem 0;
  }
  .sidebar-header .app-title {
    display: none;
  }
  .sidebar-link {
    justify-content: center;
    padding: 0.75rem 0.5rem;
    font-size: 1.1rem;
  }
  .main-content {
    padding-left: 60px;
  }
}
</style> 