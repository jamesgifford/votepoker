<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-8">

                <div class="current-user">
                    {{ currentUser.name }}
                    <span v-if="results">{{ results.votes[currentUser.key] }}</span>
                </div>

                <div class="choices" v-if="format">
                    <ul>
                        <li v-for="choice in format.choices">
                            <button @click.prevent="vote">{{ choice }}</button>
                        </li>
                    </ul>
                </div>

                <div class="other-users">
                    <ul>
                        <li v-for="user in users" v-if="user.key != currentUser.key">
                            {{ user.name }}
                            <span v-if="results">{{ results.votes[user.key] }}</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="col-sm-3 col-sm-offset-1">

                <div class="round-controls" v-if="topic">
                    <h2>{{ topic.key }}</h2>
                    <button name="close-topic" @click.prevent="closeTopic">Close Topic</button>
                </div>
                <div v-else>
                    <button name="new-topic" @click.prevent="startTopic">New Topic</button>
                </div>

                <div class="results" v-if="results">
                    <ul>
                        <li v-for="vote in results.votes">
                            {{ vote }}
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'room',
            'currentUser'
        ],

        data: function () {
            return {
                'users': this.room.users,
                'topic': null,
                'format': null,
                'results': null
            }
        },

        mounted() {
            this.listen();
        },

        methods: {
            // Listen for broadcasted events
            listen: function () {
                var self = this;

                Echo.private('room.' + self.room.key)
                    .listen('UserJoinedRoom', function(e) {
                        self.users.push(e.user);
                    })
                    .listen('TopicCreated', function(e) {
                        self.topic = e.topic;
                        self.format = e.format;
                        self.results = null;
                    })
                    .listen('TopicClosed', function(e) {
                        self.topic = null;
                        self.format = null;
                        self.results = e.results;
                    });
            },

            startTopic: function (event) {
                axios.post('/topic', {
                    roomKey: this.room.key,
                    userKey: this.currentUser.key
                });
            },

            closeTopic: function (event) {
                axios.put('/topic/'+this.topic.key+'/close');
            },

            vote: function (event) {
                axios.put('/topic/'+this.topic.key+'/vote', {
                    userKey: this.currentUser.key,
                    value: event.target.textContent
                });
            }
        }
    }
</script>