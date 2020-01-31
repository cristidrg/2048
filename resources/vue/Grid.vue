<template>
  <div class="p-2 rounded-sm tablet:mt-4 desktop:mt-0 bg-backgroundDark">
    <div class="flex flex-col justify-between h-68 tablet:h-84">
      <div class="flex flex-wrap justify-between w-68 tablet:w-84" v-for="row in [0,1,2,3,4,5]" :key="row">
          <div :class="`flex items-center font-black text-white justify-center rounded-sm w-11 h-11 tablet:w-18 tablet:h-18 ${getBlockClass(grid[row][col])}`" v-for="col in [0,1,2,3,4,5]" :key="col">
              {{ grid[row][col] > 0 ? grid[row][col] : '' }}
          </div>
      </div>
    </div>
  </div>
</template>
<script>
import { Fragment } from 'vue-fragment'

const defaultState = [0,1,2,3,4,5].map(entry => [{},{},{},{},{},{}])
export default {
    data() {
      return {
        grid: defaultState,
      }
    },
    methods: {
      getBlockClass(value) {
        let backgroundColor = "bg-empty";
        if (value == 1) {
          backgroundColor = "bg-primary";
        }else if (value > 1) {
          backgroundColor = `bg-block${value}`;
        } else if (value == - 1) {
          backgroundColor = `bg-obstacle`;
        }

        return backgroundColor;
      },
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
    components: { Fragment }
}
</script>