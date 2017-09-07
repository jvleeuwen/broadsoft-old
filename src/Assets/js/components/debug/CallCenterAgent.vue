<template>
    <div>
        <pre v-for="msg in messages">{{msg}}</pre> 
    </div>
</template>



<script>
export default {
    data() {
        return {
            messages: [],
        }
    },
    filters: {
        pretty: function(value) {
            if(value)
                return JSON.stringify(value, undefined, 2).trim();
            else
                return value;
        }
    },
    methods:{
        listen(){
            console.log('pusher client ready to receive messages!');
            this.channel.listen('CallCenterAgentEvent', (e) => {
                console.log(e);
            });
        }
    },
    mounted(){
        Echo.channel('CallCenterAgent')
                .listen('.CallCenterAgentEvent', (e) => {
                    this.messages.unshift(e.message);
                    this.messages = this.messages.slice(0,25);
                });
    }
}
</script>