<template>
    <div class="main">
        <Nav />
        <div class="sub-main">
            <div class="container">
                <p>Overall Records Dashboard</p>
            </div>
            <div class="used" v-for="card in cards" :key="card">
                <h3>Records Analysis for {{ card.name }}</h3>
                <div class="overall">
                    <doughnut
                        class="doughnut"
                        :id="card.name"
                        :title="card.name"
                        :labels="floors"
                        :data="
                            chartDataCalc(
                                card.scores,
                                totalCalc(card.scores) - usedCalc(card.scores)
                            )
                        "
                    />
                    <div class="analysis">
                        <h3>Each Floor Overall Analysis</h3>
                        <div>
                            <table>
                                <tr>
                                    <th>Floor</th>
                                    <th>Total</th>
                                    <th>Used</th>
                                    <th>Remaining</th>
                                </tr>
                                <tr v-for="score in card.scores" :key="score">
                                    <td>{{ score.floor }}</td>
                                    <td>{{ score.total }}</td>
                                    <td>{{ score.used }}</td>
                                    <td>{{ score.total - score.used }}</td>
                                </tr>
                            </table>
                        </div>
                        <h3>All Floors Overall Analysis</h3>
                        <div>
                            <table>
                                <tr>
                                    <th>Total</th>
                                    <th>Used</th>
                                    <th>Remaining</th>
                                </tr>
                                <tr>
                                    <td>{{ totalCalc(card.scores) }}</td>
                                    <td>{{ usedCalc(card.scores) }}</td>
                                    <td>
                                        {{
                                            totalCalc(card.scores) -
                                            usedCalc(card.scores)
                                        }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Nav from "../components/Nav.vue";
import Doughnut from "../components/charts/Doughnut.vue";
import axios from "axios";

export default {
    name: "Dashboard",
    components: {
        Nav,
        Doughnut,
    },
    data: () => {
        return {
            scores: [],
            cards: [],
            floors: [],
        };
    },
    beforeCreate() {
        if (this.$store.state.user.role === 1) {
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
                        axios.post("api/fcm-token", {
                            _method: "PATCH",
                            token,
                        });
                    });
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
        }
    },
    created() {
        const accessToken = this.$store.state.token;
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${accessToken}`,
        };
        axios.get("/api/overall").then((response) => {
            this.scores = response.data.data;
            this.cards = this.scores
                .filter(
                    (obj, index) =>
                        this.scores.findIndex(
                            (item) => item.accessory === obj.accessory
                        ) === index
                )
                .map((e) => {
                    e.name = e.accessory;
                    e.scores = this.scores.filter((score) => {
                        return score.accessory === e.accessory;
                    });
                    return e;
                });
            this.floors = this.scores
                .filter(
                    (obj, index) =>
                        this.scores.findIndex(
                            (item) => item.floor === obj.floor
                        ) === index
                )
                .map((e) => {
                    return e.floor === 1
                        ? "1st Floor"
                        : e.floor === 2
                        ? "2nd Floor"
                        : "4th Floor";
                });
            this.floors.push("Remaining");
        });
    },
    computed: {
        totalCalc: () => (scores) => {
            if (scores) {
                return scores.reduce(
                    (accumulator, currentValue) =>
                        accumulator + currentValue.total,
                    0
                );
            }
            return 0;
        },
        usedCalc: () => (scores) => {
            if (scores) {
                return scores.reduce(
                    (accumulator, currentValue) =>
                        accumulator + currentValue.used,
                    0
                );
            }
            return 0;
        },
        chartDataCalc: () => (scores, remain) => {
            if (scores) {
                const overall = scores.map((e) => {
                    return e.used;
                });
                overall.push(remain);
                return overall;
            }
            return [0, 0, 0, 0];
        },
    },
};
</script>

<style scoped>
.main {
    min-height: calc(100vh - 1px);
    background: rgb(235, 245, 255);
    background: linear-gradient(
        90deg,
        rgba(235, 245, 255, 1) 0%,
        rgba(221, 231, 231, 1) 51%,
        rgba(253, 255, 244, 1) 100%
    );
    padding-bottom: 50px;
}
.sub-main {
    width: 76%;
    margin: 0 auto;
    margin-left: 17%;
    padding-top: 30px;
}
h3 {
    color: #615f5f;
}
.used {
    padding: 20px;
    background: #f2f7ff;
    border: 2px solid white;
    border-radius: 15px;
    box-shadow: -5px 8px #888888;
    margin-top: 30px;
}
.used:hover {
    background: #e3edfc;
}

.overall {
    padding-left: 7%;
    display: flex;
    flex-wrap: nowrap;
    border-radius: 10px;
}
.doughnut {
    margin: 60px 0;
    height: 300px;
    width: 300px;
}
.analysis {
    width: 60%;
    margin-left: 5%;
}
.analysis h3 {
    width: 100%;
    display: block;
    text-align: center;
}
h1 {
    text-align: center;
}
h2,
h3 {
    color: #888888;
}
table {
    width: 100%;
    text-align: center;
}
th,
td {
    width: 24%;
    color: #888888;
    text-align: center;
    padding-top: 10px;
    font-size: 18px;
    padding-bottom: 5px;
}
td {
    font-size: 16px;
}
.team-name {
    cursor: pointer;
}
.team-name:hover {
    color: blue;
}
.overall-two {
    padding-left: 0;
    padding-right: 7%;
}
.analysis-two {
    margin-left: 0;
    margin-right: 5%;
}
.container {
    width: 100%;
    margin: 0 auto;
    text-align: center;
}
.container p {
    display: inline-block;
    font-size: 24px;
    font-weight: bold;
    background: -webkit-linear-gradient(#9dbba5, #ff9c9c);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    overflow: hidden;
    white-space: nowrap;
    animation: typing 2.5s steps(700, end) forwards, blinking 0.2s infinite;
    animation-iteration-count: infinite;
}

@keyframes typing {
    from {
        width: 0;
    }
    to {
        width: 100%;
    }
}

@keyframes blinking {
    0% {
        border-right-color: transparent;
    }
    50% {
        border-right-color: black;
    }
    100% {
        border-right-color: transparent;
    }
}
</style>
