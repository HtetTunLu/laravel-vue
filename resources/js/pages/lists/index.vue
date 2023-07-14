<template>
    <confirm :isDelete="deleteFlg" @no="cancelModal" @yes="confirmDelete" />
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">Accessories History Lists</p>
            </div>
            <div class="sub-container">
                <button class="csv-btn" @click="csvDownload">Download</button>
                <input
                    type="file"
                    class="custom-file-input"
                    @change="uploadCsv"
                />
                <button class="btn" @click="onCreate">New</button>
            </div>
            <Table2
                :lists="lists"
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
            lists: [],
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
            this.$router.push({ name: "ListCreate" });
        },
        onShow(id) {
            this.$router.push({ path: `/lists/${id}/show` });
        },
        onEdit(id) {
            this.$router.push({ path: `/lists/${id}/edit` });
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
                .delete(`/api/accessory_lists/${this.deleteId}`)
                .then((response) => {
                    this.changePage(1);
                    this.deleteFlg = false;
                    for (let i = 0; i < this.total_page; i++) {
                        document
                            .getElementById(`page${i + 1}`)
                            .classList.remove("is-active");
                    }
                    if (this.isPaginate) {
                        document
                            .getElementById(`page${1}`)
                            .classList.add("is-active");
                    }
                });
        },
        changePage(index) {
            const accessToken = this.$store.state.token;
            axios.defaults.headers = {
                Accept: "application/json",
                Authorization: `Bearer ${accessToken}`,
            };
            axios
                .get(`/api/accessory_lists?search=${index}`)
                .then((response) => {
                    this.lists = response.data.data.map((e) => {
                        e.accessory_name = e.accessory.name;
                        e.team_name = e.team.name;
                        return e;
                    });
                    if (response.data.is_next[1] >= 6) {
                        this.isPaginate = true;
                        const quotient = Math.floor(
                            response.data.is_next[1] / 5
                        );
                        const remainder = response.data.is_next[1] % 5;
                        this.total_page =
                            remainder === 0 ? quotient : quotient + 1;
                    } else {
                        this.isPaginate = false;
                    }
                });
        },
        csvDownload() {
            axios.get("/api/csv_download").then((response) => {
                const data1 = response.data.data;
                const csvData = objectToCsv(data1);
                download(csvData);
            });
            const objectToCsv = function (data1) {
                const csvRows = [];
                const headers = Object.keys(data1[0]);
                csvRows.push(headers.join(","));
                for (const row of data1) {
                    const values = headers.map((header) => {
                        const escaped = ("" + row[header]).replace(/"/g, '\\"');
                        return `"${escaped}"`;
                    });
                    csvRows.push(values.join(","));
                }
                return csvRows.join("\n");
            };
            const download = function (data1) {
                const blob = new Blob([data1], { type: "text/csv" });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.setAttribute("hidden", "");
                a.setAttribute("href", url);
                a.setAttribute("download", "download.csv");
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            };
        },
        uploadCsv(e) {
            const regex = new RegExp("[^.]+$");
            const extension = e.target.files[0].name.match(regex);
            if (extension[0] === "csv" || extension[0] === "xslx") {
                const formData = new FormData();
                formData.append("file", e.target.files[0]);
                axios.post("/api/csv_upload", formData).then((response) => {
                    this.changePage(1);
                });
            }
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
.csv-btn {
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
.custom-file-input {
    width: 10.5%;
    color: transparent;
}
.custom-file-input::-webkit-file-upload-button {
    visibility: hidden;
}
.custom-file-input::before {
    content: "upload";
    display: inline-block;
    background: -webkit-linear-gradient(top, #f9f9f9, #e3e3e3);
    border: 1px solid #999;
    border-radius: 8px;
    padding: 8px 28px;
    outline: none;
    white-space: nowrap;
    -webkit-user-select: none;
    user-select: none;
    cursor: pointer;
    font-weight: 700;
    font-size: 10pt;
    color: white;
    background: linear-gradient(
        90deg,
        rgba(157, 187, 165, 1) 0%,
        #95a5a6 45%,
        rgba(221, 226, 219, 1) 100%
    );
    border: none;
}
.custom-file-input:hover::before {
    border-color: black;
}
.custom-file-input:active {
    outline: 0;
}
.custom-file-input:active::before {
    background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
}
</style>
