<template>
    <div class="flex h-screen">
        <div class="m-auto bg-[#132D46] shadow-xl shadow-[#01C38D] rounded-xl">
            <!--            Form starts-->
            <form class="w-72 m-4" @submit.prevent="handleSubmit">

                <!--                Email starts-->
                <div class="mt-5">
                    <label for="email" class="text-white text-xs tracking-widest font-light">Email</label>

                    <div>
                        <input class="w-72 h-7 mt-1 rounded" type="email" v-model="email" required>
                    </div>
                </div>
                <!--                Email ends-->

                <!--                Password starts-->
                <div class="mt-5">
                    <label for="email" class="text-white text-xs tracking-widest font-light">Password</label>

                    <div>
                        <input class="w-72 h-7 mt-1 rounded" type="password" v-model="password" required>
                    </div>
                </div>
                <!--                Password ends-->

                <!--                Login button starts-->
                <div class="my-8 flex justify-center">
                    <button class="bg-[#01C38D] uppercase tracking-widest font-semibold rounded w-28 h-8 text-white">
                        Log in
                    </button>
                </div>
                <!--                Login button ends-->

            </form>
            <!--            Form ends-->

        </div>
    </div>
</template>

<script setup>
import {ref} from "vue";

const email = ref('');
const password = ref('');

const handleSubmit = async () => {
    // await this.$nuxtAuth.loginWith('sanctum', {
    //     data: {
    //         email: email.value,
    //         password: password.value
    //     }
    // });
}
</script>
