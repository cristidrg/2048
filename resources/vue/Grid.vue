<template>
  <div>
    <div class="flex flex-wrap my-2 w-gametable" v-for="row in [0,1,2,3,4,5]" :key="row">
        <div class="flex items-center justify-center mx-1 h-gameblock w-gameblock bg-primary" v-for="col in [0,1,2,3,4,5]" :key="col">
            {{ grid[row][col] == 0 ? '' : grid[row][col] }}
        </div>
    </div>
  </div>
</template>
<script>

const defaultState = [0,1,2,3,4,5].map(entry => [{},{},{},{},{},{}])
export default {
    data() {
      return {
        grid: defaultState,
      }
    },
    methods: {
      mapBlocksToState(blocks) {
        return blocks.reduce((acc, {row, column, value}) => {
          acc[row][column] = value;
          return acc;
        }, defaultState);
      },
    },
    created() {
      axios.get('/api/game/1')
        .then(({ data }) => {
          this.grid = this.mapBlocksToState(data.data.blocks)
          this.$forceUpdate();
        })
        .catch(err => alert(err));

      window.Echo.channel('gameUpdated').listen('GameUpdated', ({ blocks }) => {
        this.grid = this.mapBlocksToState(blocks)
        this.$forceUpdate();
      });
    },
    props: [],
}
</script>