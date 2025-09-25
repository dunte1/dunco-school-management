<nav class="bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700 px-4 py-3 flex items-center justify-between">
    <!-- Logo/Title -->
    <div class="flex items-center">
        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Dunco School</span>
    </div>
    <!-- Actions (Notifications, etc.) -->
    <div class="flex items-center space-x-4">
        <!-- Notification Icon -->
        <button class="relative text-gray-600 dark:text-gray-300 hover:text-indigo-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 7.165 6 9.388 6 12v2.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full"></span>
        </button>
        <!-- User Dropdown -->
        <div class="relative">
            <button class="flex items-center focus:outline-none">
                <img class="w-8 h-8 rounded-full border-2 border-indigo-500" src="https://ui-avatars.com/api/?name=User&background=4f46e5&color=fff" alt="User avatar">
                <span class="ml-2 text-gray-700 dark:text-gray-200 font-medium">John Doe</span>
                <svg class="w-4 h-4 ml-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M19 9l-7 7-7-7"/></svg>
            </button>
            <!-- Dropdown menu (hidden by default, show with JS if needed) -->
            <!--
            <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-2 z-20">
                <a href="/profile" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                <a href="/settings" class="block px-4 py-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                <a href="/logout" class="block px-4 py-2 text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</a>
            </div>
            -->
        </div>
    </div>
</nav> 