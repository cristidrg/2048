<template>
  <div class="w-full h-full">
    <div class="flex flex-col items-center items-end h-full mx-auto w-70 tablet:w-84 tablet:justify-center tablet:flex-wrap desktop:items-start desktop:w-11/12 desktop:flex-row desktop:content-start">
      <div class="flex items-center w-full mt-8 desktop:justify-center desktop:flex desktop:flex-wrap desktop:h-16 desktop:mb-16">
        <h1 class="font-black text-center text-black desktop:text-3xl desktop:w-full">2048 V7</h1>
        <form class="ml-auto desktop:ml-0 " method="post" action="/api/message/" @submit.prevent="restartGame">
          <button class="px-4 py-1 mt-2 mb-2 ml-auto mr-2 text-xs font-bold text-black uppercase bg-white rounded-lg" type="submit">New game</button>
        </form>
        <CogWheel />
      </div>
      
      <Grid />
      <Chat :csrf="csrf" />
    </div>
  </div>
</template>

<script>
import Chat from './Chat.vue';
import Grid from './Grid.vue';
import CogWheel from './Cogwheel.vue';

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
    Grid,
    CogWheel
  }
}
</script>