<template>
    <div id="myModal" class="modal" v-if="isDelete">
        <div class="modal-content">
            <!-- <span class="close">&times;</span> -->
            <p>Are u sure want to delete ?</p>

            <button class="btn yes" @click="$emit('yes')">Yes</button>
            <button class="btn" @click="no">No</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Confirm",
    props: ["isDelete"],
    methods: {
        no() {
            this.$emit("no");
            document.getElementById("myModal").style.display = "none";
        },
    },
};
</script>

<style scoped>
/* The Modal (background) */
.modal {
    /* display: none; Hidden by default */
    position: fixed; /* Stay in place */
    padding-top: 16%; /* Location of the box */
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    z-index: 100;
    background-color: rgba(0, 0, 0, 0.7);
}

/* Modal Content */
.modal-content {
    text-align: center;
    background: rgb(157, 187, 165);
    background: linear-gradient(
        280deg,
        rgba(157, 187, 165, 1) 0%,
        rgba(214, 251, 206, 1) 45%,
        rgba(221, 226, 219, 1) 100%
    );
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 20%;
    border-radius: 20px;
}
.modal-content p {
    font-size: 20px;
    margin: 20px 0;
    color: brown;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 20px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
.btn {
    background: rgb(250, 86, 233);
    background: linear-gradient(
        90deg,
        rgba(250, 86, 233, 1) 0%,
        rgba(184, 84, 150, 1) 45%,
        rgba(215, 84, 179, 1) 100%
    );
    border-radius: 8px;
    border: 1px solid white;
    width: 80px;
    cursor: pointer;
    margin-top: 40px;
    margin-left: 10px;
    margin-bottom: 20px;
    color: white;
    font-size: 13px;
    font-weight: bold;
    padding: 7px;
}
.yes {
    margin-left: 0;
}
.btn:hover {
    opacity: 0.5;
    color: red;
}
</style>
