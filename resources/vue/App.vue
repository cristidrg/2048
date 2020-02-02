<template>
  <div class="w-full h-full">
    <div class="flex flex-col items-center items-end h-full mx-auto w-70 tablet:w-84 tablet:justify-center tablet:flex-wrap desktop:items-start desktop:w-11/12 desktop:flex-row desktop:content-start">
      <GameOptions :obstacleCount="obstacleCount" />
      {{ gameState == 'playing' ? '' : gameState }}
      <Grid :grid="grid" />
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
    grid: [0,1,2,3,4,5].map(entry => [{},{},{},{},{},{}]),
    obstacleCount: 0,
    gameState: 'playing'
  }),

  methods: {
    mapBlocksToState(blocks) {
      return blocks.reduce((acc, {row, column, value}) => {
        acc[row][column] = value;
        return acc;
      }, this.grid);
    },
  },
  created() {
    axios.get('/api/game/1')
      .then(({ data }) => {
        this.grid = this.grid.splice(0, this.grid.length, this.mapBlocksToState(data.data.blocks));
        this.obstacleCount = data.data.obstacleCount
        this.gameState = data.data.gameState
      })
      .catch(err => alert(err));

    window.Echo.channel('gameUpdated').listen('GameUpdated', ({ blocks, obstacleCount, gameState }) => {
      this.grid = this.grid.splice(0, this.grid.length, this.mapBlocksToState(blocks));
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