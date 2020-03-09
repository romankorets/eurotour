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
            return {
            }
        },

        methods: {
            async onTelegramAuth(user) {
                await axios.post('telegram/login', {
                    'telegram_id': user.id,
                    'first_name': user.first_name,
                    'last_name': user.last_name,
                    'username': user.username,
                }).then(response => {
                    if (response.status === 200) {
                        window.location.href = '/home';
                    }
                    console.log(response);
                });

            },
        }
    }
</script>
