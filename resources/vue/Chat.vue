<template>
  <div :class="className">
    <div>
      <div v-for="(message,index) in messages" :key="index">
          {{message}}
      </div>
    </div>
    <form method="post" action="/api/message/" @submit.prevent="sendMessage">
      <input
        required
        name="content"
        v-model="newMessage"
        :disabled="isPostingMessage"
        placeholder="Enter a message"
        class="w-full px-3 py-2 leading-tight text-gray-700 border rounded appearance-none focus:border-blue-500 focus:outline-none"
      >
    </form>
  </div>
</template>
<script>
export default {
    data() {
      return {
        newMessage: '',
        isPostingMessage: false,
        messages: [],
      }
    },
    props: ['csrf', 'className'],
    created() {
      window.Echo.channel('messages').listen('BroadcastMessageCreation', ({ message }) => {
        this.messages.push(message.content)
      });
    },
    methods: {
      sendMessage(e) {
        this.isPostingMessage = true;
        
        axios.post('/api/message/', {
          csrf: this.csrf,
          content: this.newMessage 
        })
        .then(success => {
          this.isPostingMessage = false;
          this.newMessage = '';
        })
        .catch(err => alert(err));
      
        return false;
      }
    }
}
</script>