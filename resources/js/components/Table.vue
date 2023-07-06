<template>
    <div class="table-container">
        <ul class="responsive-table">
            <li class="table-header">
                <div class="col col-1">Team Id</div>
                <div class="col col-2">Team Name</div>
                <div class="col col-3">Floor</div>
                <div class="col col-3">Action</div>
            </li>
            <li class="table-row" v-for="team in teams" :key="team.id">
                <div class="col col-1" data-label="Team Id">{{ team.id }}</div>
                <div class="col col-2" data-label="Team Name">
                    {{ team.name }}
                </div>
                <div class="col col-3" data-label="Floor">
                    {{ team.floor }}
                </div>
                <div class="col col-4" data-label="Action">
                    <button class="edit-btn" @click="$emit('on-edit', team.id)">
                        Edit
                    </button>
                    <button class="show-btn" @click="$emit('on-show', team.id)">
                        Show
                    </button>
                    <button
                        class="delete-btn"
                        @click="$emit('on-delete', team.id)"
                    >
                        Delete
                    </button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "Table",
    props: ["teams"],
};
</script>

<style scoped>
body {
    font-family: "lato", sans-serif;
}
.table-container {
    margin-left: auto;
    margin-right: auto;
    padding-left: 10px;
    padding-right: 10px;
}

.responsive-table li {
    border-radius: 3px;
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}
.responsive-table .table-header {
    background-color: #95a5a6;
    font-size: 14px;
    letter-spacing: 0.03em;
    color: white;
}
.responsive-table .table-row {
    background-color: #ffffff;
    box-shadow: 0px 0px 9px 0px rgba(0, 0, 0, 0.1);
}
.responsive-table .col-1 {
    flex-basis: 10%;
}
.responsive-table .col-2 {
    flex-basis: 40%;
}
.responsive-table .col-3 {
    flex-basis: 25%;
}
.responsive-table .col-4 {
    flex-basis: 25%;
}
button {
    min-width: 25%;
    color: white;
    background: #95a5a6;
    border: 1px solid white;
    padding: 7px 10px;
    border-radius: 10px;
    cursor: pointer;
}
button:hover {
    opacity: 0.6;
}
.responsive-table .table-row .col-4 .edit-btn,
.responsive-table .table-row .col-4 .show-btn {
    margin-right: 15px;
}
.responsive-table .table-row .col-4 .show-btn {
    background-color: rgba(157, 187, 165, 1);
}
.responsive-table .table-row .col-4 .delete-btn {
    background-color: #fac9c5;
}
@media all and (max-width: 767px) {
    .responsive-table .table-header {
        display: none;
    }
    .responsive-table li {
        display: block;
    }
    .responsive-table .col {
        flex-basis: 100%;
    }
    .responsive-table .responsive-table .col {
        display: flex;
        padding: 10px 0;
    }
    .responsive-table .responsive-table .col::before {
        color: #6c7a89;
        padding-right: 10px;
        content: attr(data-label);
        flex-basis: 50%;
        text-align: right;
    }
}
</style>
