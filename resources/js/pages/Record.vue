<template>
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">Used Accessories Record</p>
            </div>
            <div class="btn-container">
                <button id="printButton" class="btn" @click="print">
                    Print PDF
                </button>
            </div>

            <Table2 :records="records" id="printTable" />
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
import Nav from "../components/Nav.vue";
import Back from "../components/Back.vue";
import Table2 from "../components/Table2.vue";
import Paginate from "../components/Paginate.vue";

export default {
    name: "Report",
    components: {
        Nav,
        Back,
        Table2,
        Paginate,
    },
    data: () => {
        return {
            records: [],
            isPaginate: false,
            total_page: 0,
        };
    },
    created() {
        this.changePage(1);
    },
    methods: {
        changePage(index) {
            const accessToken = this.$store.state.token;
            axios.defaults.headers = {
                Accept: "application/json",
                Authorization: `Bearer ${accessToken}`,
            };
            axios.get(`api/records?search=${index}`).then((response) => {
                this.records = response.data.data;
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
        print() {
            window.jsPDF = window.jspdf.jsPDF;
            var docPDF = new jsPDF();
            // function print() {
            var elementHTML = document.querySelector("#printTable");
            docPDF.html(elementHTML, {
                callback: function (docPDF) {
                    docPDF.save("HTML Linuxhint web page.pdf");
                },
                x: 15,
                y: 15,
                width: 170,
                windowWidth: 650,
            });
            // }
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

.btn-container {
    text-align: right;
}
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
    margin-top: 10px;
    height: 33px;
    color: white;
    font-size: 15px;
    font-weight: bold;
}
.btn:hover {
    opacity: 0.5;
    color: black;
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
