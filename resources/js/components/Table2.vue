<template>
    <div class="table-container">
        <table>
            <thead>
                <tr v-if="users">
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Employee Id</th>
                    <th>Team</th>
                    <th>Entry Date</th>
                    <th>Actions</th>
                </tr>
                <tr v-else>
                    <th>Id</th>
                    <th>Accessory</th>
                    <th>Team</th>
                    <th>Quantity</th>
                    <th>Remind Limit</th>
                    <th>Added Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="param in params" :key="param.id">
                    <td>{{ param.id }}</td>
                    <td>{{ users ? param.name : param.accessory_name }}</td>
                    <td>{{ users ? param.email : param.team_name }}</td>
                    <td>{{ users ? param.employee_id : param.quantity }}</td>
                    <td>{{ users ? param.team : param.remind_limit }}</td>
                    <td>{{ users ? param.entry_date : param.created_at }}</td>
                    <td>
                        <button @click="$emit('on-edit', param.id)">
                            Edit
                        </button>
                        <button
                            @click="$emit('on-show', param.id)"
                            v-if="users"
                        >
                            Show
                        </button>
                        <button @click="$emit('on-delete', param.id)">
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "Table2",
    props: ["users", "lists"],
    data: () => {
        return {
            params: null,
        };
    },
    updated() {
        this.params = this.users ? this.users : this.lists;
    },
};
</script>

<style scoped>
.table-container {
    margin-left: 4%;
    margin-top: 3%;
    width: 100%;
}

table {
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

th,
td {
    text-align: left;
    padding: 15px 10px;
    background-color: rgba(255, 255, 255, 0.2);
    color: gray;
}

th {
    text-align: left;
}

thead th {
    background-color: #95a5a6;
    color: white;
}

tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.3);
}
tbody td {
    position: relative;
}
button {
    color: white;
    background: #95a5a6;
    border: 1px solid white;
    padding: 7px 5px;
    border-radius: 10px;
    cursor: pointer;
    width: 32%;
    margin: 1px;
}
button:hover {
    opacity: 0.6;
}
</style>
