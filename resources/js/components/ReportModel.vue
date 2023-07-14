<template>
    <div id="myModal" class="modal" v-if="isDelete">
        <div class="modal-content">
            <p>{{ accessory.name }}</p>
            <div>
                <label for="count" style="padding-right: 10px">Count</label>
                <input type="number" name="count" v-model="count"/>
            </div>

            <button class="btn no" @click="no">Cancel</button>
            <button class="btn" @click="$emit('yes', count)">Report</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Confirm",
    props: ["isDelete", "accessory"],
    data: () => {
        return {
            count: null
        }
    },
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
    background: linear-gradient(90deg, #888888 0%, white 45%, gray 100%);
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
    background: rgb(157, 187, 165);
    background: linear-gradient(
        280deg,
        rgba(157, 187, 165, 1) 0%,
        gray 45%,
        rgba(221, 226, 219, 1) 100%
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
.no {
    margin-left: 0;
}
.btn:hover {
    opacity: 0.5;
    color: red;
}
input {
    padding: 6px;
    border: 1px solid #888888;
    border-radius: 10px;
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
</style>
