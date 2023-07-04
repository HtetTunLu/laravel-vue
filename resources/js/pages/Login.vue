<template>
    <div class="main">
        <div class="first-main">
            <div class="first"></div>
        </div>
        <div class="second-main">
            <div class="second"></div>
        </div>
        <div class="form">
            <div v-if="isLoading" style="margin-top: 5%">
                <atom-spinner
                    :animation-duration="1000"
                    :size="100"
                    :color="'#615f5f'"
                />
            </div>
            <div v-else>
                <div class="form-content">
                    <div class="container">
                        <p class="typed">Login Here .... !</p>
                    </div>
                    <div class="form-control">
                        <p class="err-msg">{{ errorMsg }}</p>
                    </div>
                    <div class="form-control">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            name="email"
                            v-model="email"
                            @input="typing"
                        />
                    </div>
                    <div class="form-control">
                        <label for="pasowrd">Password</label>
                        <input
                            type="password"
                            name="password"
                            v-model="password"
                            @input="typing"
                        />
                    </div>
                    <button type="submit" class="btn" @click="onLogin">
                        Login
                    </button>
                    <div><a href="">Forgot password ?</a></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import router from "../router";
import { AtomSpinner } from "epic-spinners";

export default {
    components: {
        AtomSpinner,
    },
    data: () => {
        return {
            email: "",
            password: "",
            errorMsg: "",
            isLoading: false,
        };
    },
    methods: {
        typing() {
            this.errorMsg = "";
        },
        onLogin() {
            const formData = new FormData();
            formData.append("email", this.email);
            formData.append("password", this.password);
            axios
                .post("api/auth/login", formData)
                .then((response) => {
                    this.isLoading = true;
                    this.$store.dispatch("saveToken", response);
                    function roll(finishCallback) {
                        let start = Date.now(); // remember start time
                        let timer = setInterval(function () {
                            // how much time passed from the start?
                            let timePassed = Date.now() - start;

                            if (timePassed >= 2000) {
                                clearInterval(timer);
                                finishCallback(); // finish the animation after 2 seconds
                                return;
                            }
                            // draw the animation at the moment timePassed
                            draw(timePassed);
                        }, 20);
                        function draw(timePassed) {
                            document.getElementsByClassName(
                                "second"
                            )[0].style.left =
                                document.getElementsByClassName("second")[0]
                                    .offsetLeft +
                                timePassed / 80 +
                                "px";
                            document.getElementsByClassName(
                                "first"
                            )[0].style.left =
                                document.getElementsByClassName("first")[0]
                                    .offsetLeft -
                                timePassed / 80 +
                                "px";
                        }
                    }
                    roll(function () {
                        router.push({ name: "Dashboard" });
                    });
                })
                .catch((error) => {
                    this.errorMsg = error.response.data.message;
                });
        },
    },
};
</script>

<style scoped>
.main {
    position: relative;
    margin: 0;
    padding: 0;
    overflow: hidden;
    height: calc(100vh - 1px);
    background: rgb(235, 245, 255);
    background: linear-gradient(
        90deg,
        rgba(235, 245, 255, 1) 0%,
        rgba(221, 231, 231, 1) 51%,
        rgba(253, 255, 244, 1) 100%
    );
    z-index: 0;
}
.first-main {
    position: relative;
    z-index: -1;
}
.first-main .first {
    height: 100vh;
    width: 50%;
    position: absolute;
    background: rgb(170, 128, 128);
    background: linear-gradient(
        90deg,
        rgba(170, 128, 128, 1) 0%,
        rgba(229, 243, 255, 1) 100%
    );
}
.second-main {
    position: relative;
    z-index: -1;
}
.second-main .second {
    height: 100vh;
    width: 50%;
    position: absolute;
    left: 50%;
    background: rgb(229, 243, 255);
    background: linear-gradient(
        90deg,
        rgba(229, 243, 255, 1) 0%,
        rgba(88, 178, 255, 1) 100%
    );
}
.form {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 14%;
    vertical-align: middle;
}
.container {
    display: inline-block;
    font-size: 22px;
    font-weight: bold;
    background: -webkit-linear-gradient(#58b2ff, #ff9c9c);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    overflow: hidden;
    white-space: nowrap;
    width: 0;
    animation: typing 2.5s steps(30, end) forwards, blinking 0.2s infinite;
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

.form-content {
    text-align: center;
}
.form-control {
    margin: 30px;
}
.form-control label {
    font-size: 20px;
    font-weight: bold;
    color: #58b2ff;
    padding-right: 20px;
}

.form-control input {
    padding: 5px;
    float: right;
    border: none;
    background: #f9fcf7;
    border: 2px solid white;
    border-radius: 10px;
    color: #fac9c5;
    font-size: 17px;
}

.form-control input:focus {
    outline: none;
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
.err-msg {
    color: red;
}
</style>
