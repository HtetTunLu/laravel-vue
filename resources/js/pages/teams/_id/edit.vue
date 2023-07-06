<template>
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">Team Edit Form</p>
            </div>
            <div class="form">
                <div class="error-msg">
                    <span> Error! {{ errorMsg }} </span>
                </div>
                <div class="form-control">
                    <label for="name">Name</label>
                    <input
                        type="text"
                        name="name"
                        class="name"
                        v-model="name"
                    />
                </div>
                <div class="form-control">
                    <label for="name">Floor</label>
                    <select name="cars" class="select" v-model="floor">
                        <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="4">Fourth</option>
                    </select>
                </div>
                <div class="form-control-btn">
                    <button type="submit" class="btn" @click="update">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Nav from "../../../components/Nav.vue";
import Back from "../../../components/Back.vue";

export default {
    name: "AccessoryEdit",
    components: {
        Nav,
        Back,
    },
    data: () => {
        return {
            name: "",
            floor: "",
            errorMsg: "",
        };
    },
    beforeCreate() {
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${this.$store.state.token}`,
        };
        axios
            .get(`/api/teams/${this.$router.currentRoute._value.params.id}`)
            .then((response) => {
                this.name = response.data.data.name;
                this.floor = response.data.data.floor;
            });
    },
    methods: {
        async update() {
            const accessToken = this.$store.state.token;
            axios.defaults.headers = {
                Accept: "application/json",
                Authorization: `Bearer ${accessToken}`,
                "Content-Type": "application/x-www-form-urlencoded",
            };
            const formData = new FormData();
            formData.append("name", this.name);
            formData.append("floor", this.floor);
            formData.append("_method", "PATCH");
            axios
                .post(
                    `/api/teams/${this.$router.currentRoute._value.params.id}`,
                    formData
                )
                .then((response) => {
                    this.$router.push({ name: "Teams" });
                })
                .catch((error) => {
                    document.getElementsByClassName(
                        "error-msg"
                    )[0].style.display = "block";
                    this.errorMsg = Object.values(
                        error.response.data.data
                    )[0][0];
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
    margin-left: 6%;
}
.sub-main {
    width: 50%;
    margin: 0 auto;
}
.container {
    display: inline-block;
    font-size: 23px;
    font-weight: bold;
    background: -webkit-linear-gradient(#58b2ff, #ff9c9c);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    overflow: hidden;
    white-space: nowrap;
    width: 0;
    margin-top: 50px;
    margin-left: 36%;
    animation: typing 2.5s steps(60, end) forwards, blinking 0.2s infinite;
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
.form {
    width: 80%;
    margin: 0 auto;
    margin-top: 30px;
    background: white;
    border-radius: 10px;
    padding: 30px;
    border: 1px dotted #4287f5;
}
.form-control {
    margin: 20px;
}

.form-control label {
    font-size: 17px;
    margin-right: 15%;
    margin-left: 5%;
    font-weight: bold;
    color: #58b2ff;
}

.name,
.select {
    padding: 10px;
    border: none;
    width: 60%;
    background: rgb(251, 226, 226);
    border: 2px solid white;
    border-radius: 10px;
    color: rgb(254, 140, 140);
    font-size: 14px;
    opacity: 0.7;
}
.select {
    width: 32%;
}
.name:focus,
.select:focus {
    outline: none;
}
.form-control-btn {
    text-align: center;
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
    margin: 10px;
    height: 30px;
    color: white;
}
.selected-img {
    margin-top: 30px;
    text-align: center;
    display: none;
}
.error-msg {
    color: red;
    font-size: 15px;
    display: none;
}
body {
    padding: 20px;
}
</style>
