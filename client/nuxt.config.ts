// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    modules: [
        '@nuxtjs/tailwindcss',
    ],
    components: [
        '~/components',
        '~/components/auth',
    ],
    app: {
        head: {
            script: [
                {
                    hid: 'flowbite',
                    src: 'https://unpkg.com/flowbite@1.5.3/dist/flowbite.js',
                    defer: true
                },
                {
                    src: 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAZn3EraQKcexvPaH3Nt0TpnQ_xuV4Dhlg&libraries=places'
                }
            ]
        },
    },
});
