<template>
  <div class="flex flex-col flex-grow w-full h-64 mb-8 desktop:pl-12 desktop:h-84 desktop:w-full">
    <div class="flex flex-col items-start flex-grow w-full px-2 pb-3 mt-2 overflow-y-auto desktop:px-6 bg-backgroundDark">
      <div class="inline-block max-w-full py-1 pl-2 pr-4 mt-2 text-sm font-bold text-white break-words rounded-lg desktop:mt-3 desktop:pr-8 desktop:pl-4 desktop:py-2 bg-primary" v-for="(message, index) in messages" :key="index">
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
        autocomplete="off"
        class="w-full px-3 py-2 text-sm leading-tight text-gray-700 border rounded-b appearance-none focus:border-blue-500 focus:outline-none"
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
        
        axios.post('/api/game/1/message', {
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