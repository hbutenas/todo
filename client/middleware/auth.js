import {defineNuxtRouteMiddleware, navigateTo, useRoute} from "nuxt/app";

export default defineNuxtRouteMiddleware(async (to, form) => {
    // make sure that it's a web request
    if (process.client) {
        // get token
        const token = localStorage.getItem('token');

        // token does not exists
        if (!token) {
            return navigateTo('/');
        }

        // validate that token is still valid
        const request = await fetch('http://localhost:5000/api/v1/auth/profile', {
            method: 'GET',
            credentials: 'include',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const response = await request.json();

        if (response.email) {
            return true;
        } else {
            return navigateTo('/');
        }
    }
});
