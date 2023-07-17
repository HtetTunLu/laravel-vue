<template>
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">Accessory List Edit Form</p>
            </div>
            <div class="form">
                <div class="error-msg">
                    <span> Error! {{ errorMsg }} </span>
                </div>
                <div class="form-control">
                    <label for="accessory">Accessory</label>
                    <select class="select" v-model="selectedAcc">
                        <option
                            v-for="option in accessories"
                            :key="option.id"
                            :value="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="accessory">Floor</label>
                    <select name="cars" class="select floor" v-model="floor">
                        <option disabled selected value>select floor</option>
                        <option value="1">First</option>
                        <option value="2">Second</option>
                        <option value="4">Fourth</option>
                    </select>
                </div>
                <div class="form-control">
                    <label for="quantity">Qunatity</label>
                    <input
                        type="number"
                        name="quantity"
                        class="name"
                        v-model="quantity"
                    />
                </div>
                <div class="form-control">
                    <label for="remind" class="remind">Remind Limit</label>
                    <input
                        type="number"
                        name="remind"
                        class="name"
                        v-model="remind"
                    />
                </div>
                <div class="form-control-btn">
                    <button type="submit" class="btn" @click="save">
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
    name: "AccessoryCreate",
    components: {
        Nav,
        Back,
    },
    data: () => {
        return {
            selectedAcc: 0,
            floor: "",
            accessories: [],
            teams: [],
            quantity: null,
            remind: null,
            errorMsg: "",
        };
    },
    beforeCreate() {
        axios.defaults.headers = {
            Accept: "application/json",
            Authorization: `Bearer ${this.$store.state.token}`,
        };
        axios
            .get(
                `/api/accessory_lists/${this.$router.currentRoute._value.params.id}`
            )
            .then((response) => {
                this.selectedAcc = response.data.data.accessory.id;
                this.floor = response.data.data.floor;
                this.quantity = response.data.data.quantity;
                this.remind = response.data.data.remind_limit;
            });
    },
    created() {
        axios.get("/api/get_accessories").then((response) => {
            this.accessories = response.data.data;
            const defaultSelect = [
                {
                    id: 0,
                    name: "Select Accessory",
                },
            ];
            this.accessories = [...defaultSelect, ...this.accessories];
        });
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
        save() {
            const formData = new FormData();
            formData.append("accessory_id", this.selectedAcc);
            formData.append("floor", this.floor);
            formData.append("quantity", this.quantity);
            formData.append("remind_limit", this.remind);
            formData.append("_method", "PATCH");
            axios
                .post(
                    `/api/accessory_lists/${this.$router.currentRoute._value.params.id}`,
                    formData
                )
                .then((response) => {
                    this.$router.push({ name: "Lists" });
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
.error-msg {
    color: red;
    font-size: 15px;
    display: none;
}
/* Works for Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Works for Firefox */
input[type="number"] {
    -moz-appearance: textfield;
    appearance: textfield;
}
.floor {
    margin-left: 6%;
}
.form-control .remind {
    margin-right: 8%;
}
</style>
