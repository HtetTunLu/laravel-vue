<template>
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">User Edit Form</p>
            </div>
            <div class="form">
                <div class="form-control">
                    <label for="name">Name</label>
                    <input
                        type="text"
                        name="name"
                        class="name"
                        readonly
                        v-model="name"
                    />
                </div>
                <div class="form-control">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        class="name"
                        readonly
                        v-model="email"
                    />
                </div>
                <div class="form-control">
                    <label for="email" class="employee">Employee Id</label>
                    <input
                        type="text"
                        name="id"
                        class="name"
                        readonly
                        v-model="employeeId"
                    />
                </div>
                <div class="form-control">
                    <label for="email" class="entry">Entry Date</label>
                    <input
                        type="date"
                        name="entry"
                        class="name entry-input"
                        readonly
                        v-model="entry"
                    />
                </div>
                <div class="form-control">
                    <label for="team">Team</label>
                    <select class="select" v-model="selected" disabled>
                        <option
                            v-for="option in teams"
                            :key="option.id"
                            :value="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="image">Image</label>
                    <form action="">
                        <div class="selected-img">
                            <img
                                :src="this.image"
                                alt="img"
                                class="img"
                                id="avatar"
                            />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from "moment";
import Nav from "../../../components/Nav.vue";
import Back from "../../../components/Back.vue";

export default {
    name: "AccessoryCreate",
    components: {
        Nav,
        Back,
    },
    data: () => {
        return {
            selected: 0,
            teams: [],
            name: "",
            email: "",
            entry: "",
            employeeId: "",
            image: "",
            errorMsg: "",
        };
    },
    beforeCreate() {
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${this.$store.state.token}`,
        };
        axios
            .get(`/api/users/${this.$router.currentRoute._value.params.id}`)
            .then((response) => {
                this.name = response.data.data.name;
                this.email = response.data.data.email;
                this.entry = response.data.data.entry_date
                    ? moment(response.data.data.entry_date).format("YYYY-MM-DD")
                    : "";
                this.employeeId = response.data.data.employee_id
                    ? response.data.data.employee_id
                    : "";
                this.selected = response.data.data.team
                    ? response.data.data.team
                    : 0;
                if (response.data.data.avatar) {
                    const selected =
                        document.getElementsByClassName("selected-img")[0];
                    selected.style.display = "block";
                    this.image = `data:image/png;base64,${response.data.data.avatar}`;
                }
            });
    },
    created() {
        axios.get("/api/get_teams").then((response) => {
            this.teams = response.data.data;
            const defaultSelect = [
                {
                    id: 0,
                    name: "Select Team",
                },
            ];
            this.teams = [...defaultSelect, ...this.teams];
        });
    },
    methods: {
        uploadImg(event) {
            document.getElementsByClassName("error-msg")[0].style.display =
                "none";
            this.image = event.target.files[0];
            const selected = document.getElementsByClassName("selected-img")[0];
            selected.style.display = "block";
            const reader = new FileReader();
            reader.onload = function (e) {
                selected.children[0].src = e.target.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        },
        update() {
            const formData = new FormData();
            formData.append("name", this.name);
            formData.append("email", this.email);
            formData.append("employee_id", this.employeeId);
            formData.append("entry_date", this.entry);
            formData.append("team_id", this.selected);
            formData.append("avatar", this.image);
            formData.append("_method", "PATCH");
            axios
                .post(
                    `/api/users/${this.$router.currentRoute._value.params.id}`,
                    formData
                )
                .then((response) => {
                    this.$router.push({ name: "Users" });
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
        clearImg() {
            this.image = "";
            document.getElementsByClassName("selected-img")[0].style.display =
                "none";
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
.form-control .entry {
    margin-right: 8%;
}
.form-control .employee {
    margin-right: 6%;
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
.entry-input {
    border: none;
    box-sizing: border-box;
    outline: 0;
    padding: 0.75rem;
    position: relative;
    width: 20%;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}
.name:focus,
.select:focus {
    outline: none;
}
.img {
    width: 250px;
    height: 250px;
    border-radius: 50%;
}
.selected-img {
    margin-top: 30px;
    text-align: center;
    display: none;
}
</style>
