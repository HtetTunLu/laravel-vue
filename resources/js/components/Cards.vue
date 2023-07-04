<template>
    <div class="ag-format-container">
        <div class="ag-courses_box">
            <div
                class="ag-courses_item"
                v-for="accessory in accessories"
                :key="accessory.id"
            >
                <div href="#" class="ag-courses-item_link">
                    <div
                        class="ag-courses-item_bg"
                        :style="`background-color: #${accessory.color}`"
                    ></div>

                    <div class="ag-courses-item_title">
                        {{ accessory.name }}
                        <img
                            class="image"
                            :src="`data:image/png;base64,${accessory.image}`"
                            alt=""
                        />
                    </div>

                    <div class="ag-courses-item_date-box">
                        <span @click="$emit('on-show', accessory.id)">Show</span>
                        <span class="or">|</span
                        ><span @click="$emit('on-edit', accessory.id)">Edit</span>
                        <span class="or">|</span>
                        <span @click="$emit('on-delete', accessory.id)">Delete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Cards",
    props: ["accessories"],
};
</script>

<style scoped>
.ag-format-container {
    width: 1142px;
    margin: 0 auto;
}

body {
    background-color: #000;
}
.ag-courses_box {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;

    padding: 50px 0;
}
.ag-courses_item {
    -ms-flex-preferred-size: calc(33.33333% - 30px);
    flex-basis: calc(33.33333% - 30px);

    margin: 0 15px 30px;

    overflow: hidden;

    border-radius: 28px;
}
.ag-courses-item_link {
    display: block;
    padding: 30px 20px;
    background: rgb(157, 187, 165);
    background: linear-gradient(
        45deg,
        rgba(157, 187, 165, 1) 0%,
        rgba(214, 251, 206, 1) 45%,
        rgba(221, 226, 219, 1) 100%
    );

    overflow: hidden;

    position: relative;
}
.ag-courses-item_link:hover,
.ag-courses-item_link:hover .ag-courses-item_date {
    text-decoration: none;
    color: #fff;
}
.ag-courses-item_link:hover .ag-courses-item_bg {
    -webkit-transform: scale(10);
    -ms-transform: scale(10);
    transform: scale(10);
}
.ag-courses-item_title {
    overflow: hidden;
    text-align: left;
    font-weight: bold;
    font-size: 22px;
    color: #fff;

    z-index: 2;
    position: relative;
}
.ag-courses-item_date-box {
    text-align: right;
    margin-top: 20px;
    font-size: 16px;
    color: red;
    z-index: 2;
    position: relative;
    cursor: pointer;
}
.ag-courses-item_date {
    font-weight: bold;
    color: #f9b234;

    -webkit-transition: color 0.5s ease;
    -o-transition: color 0.5s ease;
    transition: color 0.5s ease;
}
.ag-courses-item_bg {
    height: 128px;
    width: 128px;
    background-color: #f9b234;

    z-index: 1;
    position: absolute;
    top: -75px;
    right: -75px;

    border-radius: 50%;

    -webkit-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

.image {
    display: block;
    width: 150px;
    height: 150px;
    margin: 0 auto;
    border-radius: 50%;
}

.or {
    color: white;
    margin: 8px;
}

@media only screen and (max-width: 979px) {
    .ag-courses_item {
        -ms-flex-preferred-size: calc(50% - 30px);
        flex-basis: calc(50% - 30px);
    }
    .ag-courses-item_title {
        font-size: 24px;
    }
}

@media only screen and (max-width: 767px) {
    .ag-format-container {
        width: 96%;
    }
}
@media only screen and (max-width: 639px) {
    .ag-courses_item {
        -ms-flex-preferred-size: 100%;
        flex-basis: 100%;
    }
    .ag-courses-item_title {
        min-height: 72px;
        line-height: 1;

        font-size: 24px;
    }
    .ag-courses-item_link {
        padding: 22px 40px;
    }
    .ag-courses-item_date-box {
        font-size: 16px;
    }
}
</style>
