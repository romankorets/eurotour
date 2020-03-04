<template>
    <div>
        <vue-telegram-login mode="callback"
                            telegram-login="myeurotour_bot"
                            @callback="onTelegramAuth"></vue-telegram-login>
    </div>
</template>


<script>
    import {vueTelegramLogin} from 'vue-telegram-login'
    export default {
        components: {vueTelegramLogin},
        data: function () {
            return {}
        },

        methods: {
            onTelegramAuth(user) {
                axios.get('/login', {
                    'email': user.first_name + ' ' + user.last_name,
                    'user_id': user.id,
                    'name': user.first_name,
                    'username': user.username,
                    'password': user.id,
                }).then(response => {
                    console.log(response.status);
                });
                alert('Logged in as ' + user.first_name + ' ' + user.last_name + ' (' + user.id + (user.username ? ', @' + user.username : '') + ')');
            },
        }
    }
</script>
