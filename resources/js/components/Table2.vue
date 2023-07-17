<template>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th v-for="head in headers" :key="head">{{ head }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="param in params" :key="param.id">
                    <td>{{ param.id }}</td>
                    <td>
                        {{
                            users
                                ? param.name
                                : lists
                                ? param.accessory_name
                                : param.user.name
                        }}
                    </td>
                    <td>
                        {{
                            users
                                ? param.email
                                : lists
                                ? param.floor
                                : param.accessory.name
                        }}
                    </td>
                    <td>
                        {{
                            users
                                ? param.employee_id
                                : lists
                                ? param.quantity
                                : param.count
                        }}
                    </td>
                    <td>
                        {{
                            users
                                ? param.team
                                : lists
                                ? param.remind_limit
                                : param.floor
                        }}
                    </td>
                    <td>
                        {{ users ? param.entry_date : param.created_at }}
                    </td>
                    <td v-if="!records">
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
    props: ["users", "lists", "records"],
    data: () => {
        return {
            params: null,
            headers: [],
        };
    },
    updated() {
        if (this.users) {
            this.headers = [
                "Id",
                "Name",
                "Email",
                "Employee Id",
                "Team",
                "Entry Date",
                "Actions",
            ];
            this.params = this.users;
        } else if (this.lists) {
            this.headers = [
                "Id",
                "Accessory",
                "Floor",
                "Quantity",
                "Remind Limit",
                "Added Date",
                "Actions",
            ];
            this.params = this.lists;
        } else {
            this.headers = [
                "Id",
                "User",
                "Accessory",
                "Count",
                "Floor",
                "Used Date",
            ];
            this.params = this.records;
        }
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
