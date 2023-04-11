<template>
    <div class="flex h-screen">
        <div class="m-auto bg-[#132D46] shadow-xl shadow-[#01C38D] rounded-xl">

            <!--            Navigation starts-->
            <div class="mt-5 flex justify-between ">

                <div class="ml-20 border-b border-transparent hover:border-[#01C38D] transition duration-300">
                    <NuxtLink @click.prevent="displayLoginForm=true" to="#" class="text-white text-xs tracking-widest font-light uppercase">Login</NuxtLink>
                </div>

                <div class="mr-20 border-b border-transparent hover:border-[#01C38D] transition duration-300">
                    <NuxtLink to="#" @click.prevent="displayLoginForm=false" class="text-white text-xs tracking-widest font-light uppercase">Register</NuxtLink>
                </div>

            </div>
            <!--            Navigation ends-->

            <div v-if="displayLoginForm">
                <LoginComponent />
            </div>

            <div v-else>
                <RegisterComponent/>
            </div>

        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";
const displayLoginForm = ref(true);
</script>
