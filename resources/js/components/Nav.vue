<template>
    <div class="sidenav">
        <div class="user-info">
            <img :src="avatar" alt="" class="avatar" />
            <div class="info-box">
                <p class="user-name">{{ userName }}</p>
                <button class="action edit" @click="editProfile">edit</button>
                <button class="action" @click="logout">logout</button>
            </div>
        </div>
        <a @click="goTo('Dashboard')">Dashboard</a>
        <a @click="goTo('Accessories')">Accessories</a>
        <a @click="goTo('Lists')">Lists</a>
        <a @click="goTo('Users')">Users</a>
        <a @click="goTo('Teams')">Teams</a>
    </div>
</template>

<script>
import image from "../src/images/default_user.png";

export default {
    name: "Nav",
    data: () => {
        return {
            avatar: image,
            userName: "",
        };
    },
    methods: {
        goTo(pageName) {
            this.$router.push({ name: pageName });
        },
        logout() {
            this.$store.dispatch("logout").then(() => {
                this.$router.push({ name: "Login" });
            });
        },
        editProfile() {
            this.$router.push({
                path: `/users/${this.$store.state.user.id}/edit`,
            });
        },
    },
    created() {
        this.userName = this.$store.state.user.name;
        this.avatar = this.$store.state.user.avatar
            ? `data:image/png;base64,${this.$store.state.user.avatar}`
            : image;
    },
};
</script>

<style scoped>
.sidenav {
    height: 100%;
    width: 11%;
    position: fixed;
    z-index: 1;
    top: 0;
    left: 0;
    background-color: #111;
    overflow-x: hidden;
    padding-top: 20px;
    text-align: left;
}

.sidenav a {
    padding: 6px 0px 16px 22px;
    text-decoration: none;
    font-size: 20px;
    color: #818181;
    display: block;
    cursor: pointer;
    font-weight: bold;
}

.sidenav a:hover {
    color: #f1f1f1;
}
.user-info {
    margin: 20px 0;
    text-align: center;
}
.user-name {
    color: white;
    margin: 10px 0;
}
.avatar {
    text-align: center;
    width: 70px;
    height: 70px;
    border-radius: 50%;
}
.info-box {
    margin-bottom: 50px;
}

.action {
    min-width: 30%;
    border: 1px solid white;
    background: rgba(157, 187, 165, 1);
    border-radius: 5%;
    cursor: pointer;
    text-align: center;
    padding: 5px;
}
.action:hover {
    opacity: 0.5;
    color: white;
}
.edit {
    margin-right: 5%;
}
@media screen and (max-height: 450px) {
    .sidenav {
        padding-top: 15px;
    }
    .sidenav a {
        font-size: 18px;
    }
}
</style>
