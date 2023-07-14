<template>
    <div class="main">
        <Nav />
        <div class="sub-main">
            <div class="container">
                <p>Overall Records Dashboard</p>
            </div>
            <div class="used">
                <h3>Records Analysis for Accessory One</h3>
                <div class="overall">
                    <doughnut class="doughnut" :id="1" />
                    <div class="analysis">
                        <h3>Overall Analysis</h3>
                        <div>
                            <table>
                                <tr>
                                    <th>Team</th>
                                    <th>Total</th>
                                    <th>Used</th>
                                    <th>Remaining</th>
                                </tr>
                                <tr v-for="team in teams" :key="team.id">
                                    <td class="team-name">{{ team.name }}</td>
                                    <td>10</td>
                                    <td>5</td>
                                    <td>5</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="used">
                <h3>Records Analysis for Accessory One</h3>
                <div class="overall overall-two">
                    <div class="analysis analysis-two">
                        <h3>Overall Analysis</h3>
                        <div>
                            <table>
                                <tr>
                                    <th>Team</th>
                                    <th>Total</th>
                                    <th>Used</th>
                                    <th>Remaining</th>
                                </tr>
                                <tr>
                                    <td class="team-name">Team name</td>
                                    <td>10</td>
                                    <td>5</td>
                                    <td>5</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <doughnut class="doughnut" :id="2" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Nav from "../components/Nav.vue";
import Doughnut from "../components/charts/Doughnut.vue";

export default {
    name: "Dashboard",
    components: {
        Nav,
        Doughnut,
    },
    data: () => {
        return {
            teams: [],
        };
    },
    created() {
        const accessToken = this.$store.state.token;
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${accessToken}`,
        };
        axios.get("/api/get_teams").then((response) => {
            this.teams = response.data.data;
        });
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
    margin-top: 50px;
    padding-left: 7%;
    padding-bottom: 50px;
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
