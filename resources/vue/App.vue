<template>
  <div class="w-full h-full">
    <div class="flex flex-col items-center items-end h-full mx-auto w-70 tablet:w-84 tablet:justify-center tablet:flex-wrap desktop:items-start desktop:w-11/12 desktop:flex-row desktop:content-start">
      <GameOptions :obstacleCount="obstacleCount" />
      {{ gameState == 'playing' ? '' : gameState }}
      <Grid :grid="grid" :previousGrid="previousGrid" />
      <Chat :csrf="csrf" />
    </div>
  </div>
</template>

<script>
import Chat from './Chat.vue';
import Grid from './Grid.vue';
import GameOptions from './GameOptions';

export default {
  data: () => ({
    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    grid: {},
    previousGrid: {},
    obstacleCount: 0,
    gameState: 'playing'
  }),

  methods: {
    mapBlocksToState(blocks) {
      return blocks.reduce(
        (acc, {id, row, column, value}) => {
          acc[id] = { id, row, column, value };
          return acc;
      }, {});
    },
  },
  created() {
    axios.get('/api/game/1')
      .then(({ data }) => {
        this.grid = this.mapBlocksToState(data.data.blocks);
        this.previousGrid = this.grid;
        this.obstacleCount = data.data.obstacleCount
        this.gameState = data.data.gameState
      })
      .catch(err => alert(err));

    window.Echo.channel('gameUpdated').listen('GameUpdated', ({ blocks, obstacleCount, gameState }) => {
      this.previousGrid = this.grid;
      this.grid = this.mapBlocksToState(blocks);
      this.obstacleCount = obstacleCount;
      this.gameState = gameState;
    });
  },
  components: {
    Chat,
    Grid,
    GameOptions
  }
}
</script>