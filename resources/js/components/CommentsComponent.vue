<template>
    <div>
        <div id="comment-input" class="row justify-content-end">
            <textarea type="text" v-model="comment" placeholder="Введіть комментар"/>
        </div>
        <div class="row justify-content-end">
            <button id="submitComment" v-on:click="sendComment" class="btn btn-primary">Відправити</button>
        </div>
        <div class="row justify-content-center">
            <h3>Комментарі</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div v-for="comment in localComments"
                     class="row justify-content-start comment">
                    <div class="col-md-12">
                        <div v-if="checkIfCurrentUserAdmin()" class="row justify-content-end">
                            <span title="Вимкнути комментар" class="close"
                                  v-on:click="disableComment(comment.id)"/>
                        </div>
                        <div class="row name">{{comment["user"]['name']}}</div>
                        <div class="row date">{{comment["created_at"]}}</div>
                        <div class="row body">
                            <p>{{comment.body}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'comments-component',
        props: ['comments', 'id'],
        data() {
            return {
                localComments: this.comments,
                currentUserRoles: null,
                userId: null,
                comment: '',
            }
        },

        mounted: async function () {
            await this.setUserId();
            window.Echo.channel(`place.${this.id}.comments`)
                .listen('.comment.new', (e) => {
                    this.localComments.push(e.comment);
                    console.log('Event comment');
                    console.log(e);
                });
        },

        methods: {
            async checkIfCurrentUserAdmin() {
                await this.setCurrentUserRoles();
                for (let i = 0; i < this.currentUserRoles.length; i++) {
                    if (this.currentUserRoles[i]['slug'] === 'admin') {
                        return true;
                    }
                }
                return false;
            },

            async disableComment(id) {
                await axios.put('api/comment/' + id, {
                    'enabled': false
                }).then(response => {
                    console.log(response)
                });
                for (let j = 0; j < this.localComments.length; j++){
                    if(this.localComments[j].id === id){
                        this.localComments.splice(j, 1);
                        console.log('Коментарі');
                        console.log(this.localComments);
                        break;
                    }
                }
            },

            async sendComment() {
                let comment = null;
                await axios.post('api/place/'+ this.$route.query.place +'/comment', {
                    'body': this.comment
                }).then(response => {
                    comment = response.data;
                });
                this.localComments.push(comment);
                this.comment = '';
            },

            async setUserId(){
                await axios.get('api/user').then(response => {
                    this.userId = response.data;
                    console.log('UserId');
                    console.log(this.userId);
                })
            },

            async setCurrentUserRoles() {
                if(this.currentUserRoles === null){
                    await axios.get('api/user/roles').then(response => {
                        this.currentUserRoles = response.data;
                        console.log('Ролі користувача');
                        console.log(this.currentUserRoles);
                    })
                }
            },
        }
    }
</script>
