<template>
    <!--            Display error starts-->
    <p v-if="displayError" class="text-red-500 text-xs text-center m-6 tracking-wider">
        Invalid email or password
    </p>
    <!--    Display error ends-->

    <AuthFormComponent @authEmit="handleSubmit">
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
            <!--                    Loading spinner starts-->
            <svg v-if="showLoading" aria-hidden="true" class="w-8 h-8 mr-2 text-[#01C38D] animate-spin fill-[#132D46]" viewBox="0 0 100 101" fill="none"
                 xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor"/>
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill"/>
            </svg>
            <!--                    Loading spinner ends-->

            <button v-if="!showLoading" class="bg-[#01C38D] uppercase tracking-widest font-semibold rounded w-28 h-8 text-white">
                Log in
            </button>
        </div>
        <!--                Login button ends-->

    </AuthFormComponent>
</template>

<script setup>
import {ref} from "vue";

const email = ref('');
const password = ref('');
const showLoading = ref(false);
const displayError = ref(false);

const handleSubmit = async () => {
    // on submit display loading spinner
    showLoading.value = true;

    // trim values for empty spacing
    const trimmedEmail = email.value.trim();
    const trimmedPassword = password.value.trim();

    // check if empty values, if yes display errors
    if (!trimmedEmail || !trimmedPassword) {
        showLoading.value = false;
        displayError.value = true;
        password.value = '';
        return;
    }

    // make post request to api for log in
    const request = await fetch('http://localhost:5000/api/v1/auth/login', {
        method: 'POST',
        credentials: 'include',
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            'email': trimmedEmail,
            'password': trimmedPassword
        })
    });

    const response = await request.json();

    // destruct response
    const {data: {token}} = response;

    // invalid credentials
    if (!token) {
        showLoading.value = false;
        displayError.value = true;
        password.value = '';
        return;
    }

    // save token to local storage
    localStorage.setItem('token', token);

    navigateTo('/dashboard');
}
</script>


