<template>
    <confirm :isDelete="deleteFlg" @no="cancelModal" @yes="confirmDelete" />
    <div class="main">
        <div class="sub-main">
            <div class="container">
                <p class="typed">Accessory Lists</p>
            </div>
            <div class="sub-container">
                <button class="btn" @click="onCreate">New</button>
            </div>
            <div class="lists">
                <cards
                    :accessories="this.accessories"
                    @on-show="onShow"
                    @on-edit="onEdit"
                    @on-delete="onDelete"
                />
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import Cards from "../../components/Cards.vue";
import Confirm from "../../components/Confirm.vue";

export default {
    name: "Dashboard",
    components: {
        Cards,
        Confirm,
    },
    data: () => {
        return {
            accessories: [],
            deleteFlg: false,
            deleteId: -1,
        };
    },
    created() {
        const accessToken = this.$store.state.token;
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${accessToken}`,
        };
        axios.get("api/accessories").then((response) => {
            this.accessories = response.data.data.map((e) => {
                e.color = Math.floor(Math.random() * 16777215).toString(16);
                return e;
            });
        });
    },
    methods: {
        onCreate() {
            this.$router.push({ name: "AccessoryCreate" });
        },
        onShow(id) {
            this.$router.push({ path: `/accessories/${id}/show` });
        },
        onEdit(id) {
            this.$router.push({ path: `/accessories/${id}/edit` });
        },
        onDelete(id) {
            this.deleteFlg = true;
            this.deleteId = id;
        },
        cancelModal() {
            this.deleteFlg = false;
            this.deleteId = -1;
        },
        confirmDelete() {
            axios
                .delete(`/api/accessories/${this.deleteId}`)
                .then((response) => {
                    axios.get("api/accessories").then((response) => {
                        this.deleteFlg = false;
                        this.accessories = response.data.data.map((e) => {
                            e.color = Math.floor(
                                Math.random() * 16777215
                            ).toString(16);
                            return e;
                        });
                    });
                });
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
}
.sub-main {
    width: 75%;
    margin: 0 auto;
    padding-top: 70px;
}
.sub-container {
    text-align: right;
    margin-right: 1%;
}
h1 {
    color: #615f5f;
    text-align: center;
}
.btn {
    background: rgb(157, 187, 165);
    background: linear-gradient(
        90deg,
        rgba(157, 187, 165, 1) 0%,
        rgba(214, 251, 206, 1) 45%,
        rgba(221, 226, 219, 1) 100%
    );
    border-radius: 10px;
    border: 1px solid white;
    width: 100px;
    cursor: pointer;
    margin: 10px;
    height: 33px;
    color: white;
    font-size: 15px;
    font-weight: bold;
}
.btn:hover {
    opacity: 0.5;
    color: #9dbba5;
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
</style>
