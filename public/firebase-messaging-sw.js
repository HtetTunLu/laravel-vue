importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");

firebase.initializeApp({
    apiKey: "AIzaSyC9NUkq_OymZ_MX69WCRF1C_ilJozd2N8U",
    authDomain: "laravel-ojt.firebaseapp.com",
    projectId: "laravel-ojt",
    storageBucket: "laravel-ojt.appspot.com",
    messagingSenderId: "273723227121",
    appId: "1:273723227121:web:a834ed4f51336f97582250",
    measurementId: "G-WR4S5E4Q53",
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(title, options);
});
