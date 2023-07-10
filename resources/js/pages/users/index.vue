<template>
    <confirm :isDelete="deleteFlg" @no="cancelModal" @yes="confirmDelete" />
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">Users Lists</p>
            </div>
            <div class="sub-container">
                <button class="csv-btn">CSV upload</button>
                <button class="btn" @click="onCreate">New</button>
            </div>
            <Table2
                :users="users"
                @on-edit="onEdit"
                @on-show="onShow"
                @on-delete="onDelete"
            />
            <paginate
                v-if="isPaginate"
                :total="total_page"
                @change-page="changePage"
            />
        </div>
    </div>
</template>
<script>
import axios from "axios";
import moment from "moment";
import Confirm from "../../components/Confirm.vue";
import Nav from "../../components/Nav.vue";
import Back from "../../components/Back.vue";
import Table2 from "../../components/Table2.vue";
import Paginate from "../../components/Paginate.vue";

export default {
    name: "Dashboard",
    components: {
        Confirm,
        Nav,
        Back,
        Table2,
        Paginate,
    },
    data: () => {
        return {
            users: [],
            deleteFlg: false,
            isPaginate: false,
            total_page: 0,
            deleteId: -1,
        };
    },
    created() {
        this.changePage(1);
    },
    methods: {
        onCreate() {
            this.$router.push({ name: "UserCreate" });
        },
        onShow(id) {
            this.$router.push({ path: `/users/${id}/show` });
        },
        onEdit(id) {
            this.$router.push({ path: `/users/${id}/edit` });
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
            axios.delete(`/api/users/${this.deleteId}`).then((response) => {
                this.changePage(1);
                this.deleteFlg = false;
                for (let i = 0; i < this.total_page; i++) {
                    document
                        .getElementById(`page${i + 1}`)
                        .classList.remove("is-active");
                }
                document.getElementById(`page${1}`).classList.add("is-active");
            });
        },
        changePage(index) {
            const accessToken = this.$store.state.token;
            axios.defaults.headers = {
                Accept: "application/json",
                Authorization: `Bearer ${accessToken}`,
            };
            axios.get(`/api/users?search=${index}`).then((response) => {
                this.users = response.data.data.map((e) => {
                    if (e.entry_date) {
                        e.entry_date = moment(e.entry_date).format(
                            "YYYY-MM-DD"
                        );
                    }
                    return e;
                });
                if (response.data.is_next[1] >= 6) {
                    this.isPaginate = true;
                    const quotient = Math.floor(response.data.is_next[1] / 5);
                    const remainder = response.data.is_next[1] % 5;
                    this.total_page = remainder === 0 ? quotient : quotient + 1;
                } else {
                    this.isPaginate = false;
                }
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
    margin-left: 6%;
}
.sub-main {
    width: 75%;
    margin: 0 auto;
    padding-top: 30px;
}
.sub-container {
    text-align: right;
}
h1 {
    color: #615f5f;
    text-align: center;
}
.csv-btn,
.btn {
    background: rgb(157, 187, 165);
    background: linear-gradient(
        90deg,
        rgba(157, 187, 165, 1) 0%,
        #95a5a6 45%,
        rgba(221, 226, 219, 1) 100%
    );
    border-radius: 10px;
    border: 1px solid white;
    width: 100px;
    cursor: pointer;
    /* margin: 10px;     */
    height: 33px;
    color: white;
    font-size: 15px;
    font-weight: bold;
}
.csv-btn:hover,
.btn:hover {
    opacity: 0.5;
    color: black;
}
.csv-btn{
    width: 120px;
    margin-right: 10px;
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
