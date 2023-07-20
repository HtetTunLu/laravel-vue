<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
export default {
    beforeCreate() {
        const firebaseConfig = {
            apiKey: "AIzaSyC9NUkq_OymZ_MX69WCRF1C_ilJozd2N8U",
            authDomain: "laravel-ojt.firebaseapp.com",
            projectId: "laravel-ojt",
            storageBucket: "laravel-ojt.appspot.com",
            messagingSenderId: "273723227121",
            appId: "1:273723227121:web:a834ed4f51336f97582250",
            measurementId: "G-WR4S5E4Q53",
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        // const analytics = getAnalytics(app);
        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken();
                })
                .then(function (token) {
                    axios
                        .post("api/fcm-token", {
                            _method: "PATCH",
                            token,
                        })
                })
        }

        initFirebaseMessagingRegistration();

        messaging.onMessage(function (payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    },
};
// Import the functions you need from the SDKs you need
// import {
//     initializeApp
// } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-app.js";
// import {
//     getAnalytics
// } from "https://www.gstatic.com/firebasejs/10.0.0/firebase-analytics.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
</script>
