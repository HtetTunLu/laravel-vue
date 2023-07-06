<template>
    <div class="pagination p9">
        <ul>
            <a :id="`page${index}`" v-for="index in total" :key="index"
                ><li @click="changePage(index)">{{ index }}</li></a
            >
        </ul>
    </div>
</template>

<script>
export default {
    name: "Paginate",
    props: ["total"],
    mounted() {
        document.getElementById("page1").classList.add("is-active");
    },
    methods: {
        changePage(index) {
            this.$emit("change-page", index)
            for (let i = 0; i < this.$props.total; i++) {
                document
                    .getElementById(`page${i + 1}`)
                    .classList.remove("is-active");
            }
            document.getElementById(`page${index}`).classList.add("is-active");
        },
    },
};
</script>

<style scoped>
a {
    text-decoration: none;
}

p,
li,
a {
    font-size: 14px;
    cursor: pointer;
}

/* GRID */

.twelve {
    width: 100%;
}
.eleven {
    width: 91.53%;
}
.ten {
    width: 83.06%;
}
.nine {
    width: 74.6%;
}
.eight {
    width: 66.13%;
}
.seven {
    width: 57.66%;
}
.six {
    width: 49.2%;
}
.five {
    width: 40.73%;
}
.four {
    width: 32.26%;
}
.three {
    width: 23.8%;
}
.two {
    width: 15.33%;
}
.one {
    width: 6.866%;
}

/* COLUMNS */

.col {
    display: block;
    float: left;
    margin: 1% 0 1% 1.6%;
}

.col:first-of-type {
    margin-left: 0;
}

.container {
    width: 100%;
    max-width: 940px;
    margin: 0 auto;
    position: relative;
    text-align: center;
}

/* CLEARFIX */

.cf:before,
.cf:after {
    content: " ";
    display: table;
}

.cf:after {
    clear: both;
}

.cf {
    *zoom: 1;
}

/* GENERAL STYLES */

.pagination {
    padding: 30px 0;
}

.pagination ul {
    margin: 0;
    padding: 0;
    list-style-type: none;
}

.pagination a {
    display: inline-block;
    padding: 10px 18px;
    color: #222;
}
/* NINE */

.p9 a {
    width: 30px;
    height: 30px;
    line-height: 30px;
    padding: 0;
    text-align: center;
    margin: auto 5px;
}

.p9 a.is-active {
    border: 3px solid #95a5a6;
    border-radius: 100%;
}
</style>
