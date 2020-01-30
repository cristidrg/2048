<template>
  <div class="w-full">
    <form method="post" action="/api/message/" @submit.prevent="restartGame">
      <button type="submit">Reset Game</button>
    </form>
    <div class="flex items-end justify-between w-8/12 mx-auto">
      <Grid />
      <Chat className="my-1" :csrf="csrf" />
    </div>
  </div>
</template>

<script>
import Chat from './Chat.vue';
import Grid from './Grid.vue';

export default {
  data: () => ({
    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  }),
  methods: {
    restartGame(e) {
      axios.post('/api/game/1/commands', {
        command: 'restart',
        csrf: this.csrf
      }).then(res => res);
    } 
  },
  components: {
    Chat,
    Grid
  }
}
</script>