<template>
    <report-model
        :isDelete="reportFlg"
        :accessory="accessory"
        @no="cancelModal"
        @yes="confirmReport"
    />
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">Report Used Accessory</p>
            </div>
            <div class="accessories">
                <div
                    class="card"
                    v-for="accessory in accessories"
                    :key="accessory.id"
                >
                    <div class="card-header">{{ accessory.name }}</div>
                    <div class="card-body">
                        <img
                            :src="`data:image/png;base64,${accessory.image}`"
                            alt="img"
                        />

                        <p class="text">Total: {{ accessory.total }}</p>
                        <p class="text">Used: {{ accessory.used }}</p>
                        <p class="text">
                            Remaining: {{ accessory.total - accessory.used }}
                        </p>
                        <p class="text">Team: {{ accessory.team.name }}</p>
                        <p class="text">Floor: {{ accessory.team.floor }}</p>
                    </div>
                    <div class="card-footer">
                        <button
                            :class="`btn ${btnClass(accessory.total)}`"
                            :disabled="isDisable(accessory.total)"
                            @click="report(accessory)"
                        >
                            Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Nav from "../components/Nav.vue";
import Back from "../components/Back.vue";
import ReportModel from "../components/ReportModel.vue";
export default {
    name: "Report",
    components: {
        Nav,
        Back,
        ReportModel,
    },
    data: () => {
        return {
            accessories: [],
            accessory: null,
            reportFlg: false,
        };
    },
    beforeCreate() {
        const accessToken = this.$store.state.token;
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${accessToken}`,
        };
        axios.get("api/accessory_infos").then((response) => {
            this.accessories = response.data.data;
        });
    },
    methods: {
        report(accessory) {
            this.reportFlg = true;
            this.accessory = accessory;
        },
        cancelModal() {
            this.reportFlg = false;
            this.accessory = null;
        },
        confirmReport(count) {
            const formData = new FormData();
            formData.append("accessory_id", this.accessory.id);
            formData.append("count", count);
            axios.post("/api/records", formData).then((record) => {
                this.reportFlg = false;
                axios.get("api/accessory_infos").then((response) => {
                    this.accessories = response.data.data;
                });
                if (record.data.data.msg) {
                    const msgData = new FormData();
                    msgData.append("msg", record.data.data.msg);
                    axios.post("/api/skype-msg", msgData)
                }
            });
        },
    },
    computed: {
        isDisable: () => (total) => {
            return total === 0;
        },
        btnClass: () => (total) => {
            return total === 0 ? "disabled" : "";
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
    text-align: center;
    margin-left: 6%;
}
.sub-main {
    width: 75%;
    margin: 0 auto;
    padding-top: 70px;
}
.container {
    display: inline-block;
    font-size: 25px;
    font-weight: bold;
    background: -webkit-linear-gradient(#9dbba5, #ff9c9c);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    overflow: hidden;
    white-space: nowrap;
    width: 0;
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
.accessories {
    margin-top: 50px;
}
.card {
    display: inline-block;
    width: 30%;
    margin: 10px;
    border: 2px solid white;
    border-radius: 15px;
    background: #dae1eb;
}
.card-header {
    padding: 20px;
    font-size: 18px;
    border-bottom: 2px solid white;
    color: #888888;
}
.card-body {
    padding: 20px 0;
}
.card-body img {
    width: 150px;
    height: 150px;
}
.card-footer {
    padding: 20px;
}
.btn {
    background: rgb(88, 178, 255);
    background: linear-gradient(
        45deg,
        rgba(88, 178, 255, 1) 0%,
        rgba(226, 225, 242, 1) 52%,
        rgba(255, 156, 156, 1) 100%
    );
    border-radius: 10px;
    border: 1px solid white;
    width: 100px;
    cursor: pointer;
    height: 30px;
    color: white;
}
.text {
    margin-top: 10px;
    color: #888888;
}
.disabled {
    cursor: not-allowed;
}
</style>
