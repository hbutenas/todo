<template>
    <div class="container flex mx-auto mt-10">
        <!--        Navbar component starts-->
        <div class="flex-none w-32">
            <NavbarComponent @navigationEmit="handleNavigationEmit"/>
        </div>
        <!--        Navbar component ends-->

        <!--        Navbar option starts-->
        <div class="flex-1">
            <div v-if="displayTodos">
                <TodoComponent/>
            </div>
            <div v-if="displayStatistics">
                <StatisticComponent/>
            </div>
        </div>
        <!--        Navbar option ends-->
    </div>
</template>
<script setup>
import {ref} from "vue";

const displayTodos = ref(false);
const displayStatistics = ref(false);

definePageMeta({
    middleware: 'auth'
});

defineEmits([
    'navigationEmit',
]);

const handleNavigationEmit = (value) => {
    if (value === 'todos') {
        displayTodos.value = true;
        displayStatistics.value = false;
    } else if (value === 'statistics') {
        displayStatistics.value = true;
        displayTodos.value = false;
    }
}
</script>
