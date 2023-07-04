<template>
    <div class="main">
        <div class="sub-main">
            <div class="container">
                <p class="typed">Accessory Edit Form</p>
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
                    <label for="image">Image</label>
                    <input
                        type="file"
                        class="custom-file-input"
                        @change="uploadImg"
                    />
                    <form action="">
                        <div class="selected-img">
                            <img
                                :src="this.image"
                                alt="img"
                                class="img"
                                id="avatar"
                            />
                            <div class="clear" @click="clearImg">clear</div>
                        </div>
                    </form>
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
export default {
    name: "AccessoryEdit",
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
            })
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
        async update() {
            const accessToken = this.$store.state.token;
            axios.defaults.headers = {
                Accept: "application/json",
                Authorization: `Bearer ${accessToken}`,
                "Content-Type": "application/x-www-form-urlencoded",
            };
            const formData = new FormData();
            formData.append("name", this.name);
            formData.append("image", this.image);
            formData.append('_method', 'PATCH');
            axios
                .post(
                    `/api/accessories/${this.$router.currentRoute._value.params.id}`,
                    formData
                )
                .then((response) => {
                    this.$router.push({ name: "Accessories" });
                })
                .catch((error) => {
                    document.getElementsByClassName(
                        "error-msg"
                    )[0].style.display = "block";
                    this.errorMsg = error.response.data.message;
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
}
.name:focus {
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
.custom-file-input {
    color: transparent;
}
.custom-file-input::-webkit-file-upload-button {
    visibility: hidden;
}
.custom-file-input::before {
    content: "Select some files";
    color: black;
    display: inline-block;
    background: -webkit-linear-gradient(top, #f9f9f9, #e3e3e3);
    border: 1px solid #999;
    border-radius: 8px;
    padding: 8px 8px;
    outline: none;
    white-space: nowrap;
    -webkit-user-select: none;
    user-select: none;
    cursor: pointer;
    text-shadow: 1px 1px #fff;
    font-weight: 700;
    font-size: 10pt;
    color: rgb(254, 140, 140);
    background: rgb(251, 226, 226);
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
.clear {
    display: inline-block;
    color: red;
    font-weight: bold;
    font-size: 13px;
    cursor: pointer;
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
