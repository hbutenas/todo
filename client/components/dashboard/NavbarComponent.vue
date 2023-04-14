<template>
    <aside>
        <div class="flex flex-col">
            <button @click.prevent="$emit('navigationEmit', 'todos')" class="text-left m-1.5 text-[#01C38D] uppercase text-xs tracking-widest hover:text-gray-400 duration-300">
                Todos
            </button>
            <button @click.prevent="$emit('navigationEmit', 'statistics')" class="text-left m-1.5 text-[#01C38D] uppercase text-xs tracking-widest hover:text-gray-400 duration-300">
                Statistics
            </button>
            <button @click.prevent="handleLogoutSubmit" class="text-left m-1.5 text-[#01C38D] uppercase text-xs tracking-widest hover:text-gray-400 duration-300">
                Sign out
            </button>
        </div>
    </aside>
</template>

<script setup>

const handleLogoutSubmit = async () => {
    // get user token
    const token = localStorage.getItem('token');

    // if token does not exist
    if (!token) {
        navigateTo('/');
    }

    // send request to server
    await fetch('http://localhost:5000/api/v1/auth/logout', {
        method: 'POST',
        credentials: 'include',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`,
        },

    });

    // redirect user
    navigateTo('/');
}
</script>
