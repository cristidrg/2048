<template>
  <div class="p-2 rounded-sm tablet:mt-4 desktop:mt-0 bg-backgroundDark" id="board">
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

export default {
    methods: {
      getBlockClass(value) {
        let backgroundColor = "bg-empty";
        if (value == 1) {
          backgroundColor = "bg-primary";
        } else if (value > 1) {
          backgroundColor = `bg-block${value}`;
        } else if (value == - 1) {
          backgroundColor = `bg-obstacle`;
        }

        return backgroundColor;
      },
    },
    props: ['grid'],
}
</script>