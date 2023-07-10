<template>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Employee Id</th>
                    <th>Team</th>
                    <th>Entry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.employee_id }}</td>
                    <td>{{ user.team }}</td>
                    <td>{{ user.entry_date }}</td>
                    <td>
                        <button @click="$emit('on-edit', user.id)">Edit</button>
                        <button @click="$emit('on-show', user.id)">Show</button>
                        <button @click="$emit('on-delete', user.id)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "Table2",
    props: ["users"],
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
