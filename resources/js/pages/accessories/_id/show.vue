<template>
    <div class="main">
        <Nav />
        <back />
        <div class="sub-main">
            <div class="container">
                <p class="typed">Accessory Details Form</p>
            </div>
            <div class="form">
                <div class="form-control">
                    <label for="name">Name</label>
                    <input
                        type="text"
                        name="name"
                        class="name"
                        v-model="name"
                        readonly
                    />
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
            .get(
                `/api/accessories/${this.$router.currentRoute._value.params.id}`
            )
            .then((response) => {
                const selected =
                    document.getElementsByClassName("selected-img")[0];
                selected.style.display = "block";
                this.name = response.data.data.name;
                this.image = `data:image/png;base64,${response.data.data.image}`;
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

.name {
    padding: 10px;
    border: none;
    width: 60%;
    background: rgb(251, 226, 226);
    border: 2px solid white;
    border-radius: 10px;
    color: rgb(254, 140, 140);
    font-size: 14px;
    opacity: 0.7;
    cursor: default;
}
.name:focus {
    outline: none;
}
.selected-img {
    margin-top: 30px;
    text-align: center;
    display: none;
}
.img {
    width: 250px;
    height: 250px;
    border-radius: 50%;
}
body {
    padding: 20px;
}
</style>
