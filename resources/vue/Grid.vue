<template>
  <div class="relative p-4 rounded-sm h-68 w-68 tablet:w-84 tablet:h-84 tablet:mt-4 desktop:mt-0 bg-backgroundDark" id="board">
    <div v-for="(iterator, idx) in new Array(36)" :key="`${Math.floor(idx / 6)}${idx % 6}0${offSet}`"
       v-anime="{top: getTopValue(0, Math.floor(idx / 6)), left: getLeftValue(0, idx % 6, 0), duration: 250}"
      class="absolute flex items-center justify-center font-black text-white rounded-sm w-11 h-11 tablet:w-18 tablet:h-18 bg-empty" />
    
    <div v-for="{id, row, column, value} in Object.values(grid).filter(({value}) => value != 0)" :key="`${id}${row}${column}${value}${offSet}`" :ref="id"
      v-anime="{top: getTopValue(value, row, id), left: getLeftValue(value, column, id), duration: 250}"
      :class="`flex absolute items-center font-black text-white justify-center rounded-sm w-11 h-11 tablet:w-18 tablet:h-18 ${getBlockClass(previousGrid[id].value, value)}`">
        {{ getValue(id) }}
    </div>
  </div>
</template>
<script>
export default {
    data: () => ({
      offSet: 9,
      gridSize: 78,
    }),
    updated() {
      Object.values(this.grid).forEach(({id, value}) => {
        if (this.previousGrid[id].value == 0 && value == 1) {
          this.$anime({
            targets: this.$refs[id][0],
            backgroundColor: "#00d9c9", 
            scale: [1, 0.8, 1.2, 1],
            duration: 85,
            delay: 275,
            complete: (anim) => {
              anim.completed ? this.$refs[id][0].innerText = value : '';
            }
          });
        } else if (value > 1 && this.previousGrid[id].value == value / 2) {
          this.$anime({
            targets: this.$refs[id][0],
            scale: [1, 1,2, 1],
            duration: 250,
          });
        }
      });
    },
    created() {
      var mq = window.matchMedia( "(max-width: 767px)" );
      if (mq.matches) {
        this.offSet = 2.5;
        this.gridSize = 45;
      }


      window.addEventListener('resize', (e) => {
        var mq = window.matchMedia( "(max-width: 767px)" );

        if (mq.matches) {
          this.gridSize = 45;
          this.offSet = 2.5;
        } else {
          this.gridSize = 78;
          this.offSet = 9;
        }
      });
    },

    methods: {
      getTopValue(value, row, id) {
        if (value == 0) {
          return [`${this.gridSize * row + this.offSet}px`, `${this.gridSize * row + this.offSet}px`]
        } else {
          return [`${this.gridSize * this.previousGrid[id].row + this.offSet}px`, `${this.gridSize * row + this.offSet}px`]
        }
      },
      getLeftValue(value, column, id) {
        if (value == 0) {
          return [`${this.gridSize * column + this.offSet}px`, `${this.gridSize * column + this.offSet}px`]
        } else {
          return [`${this.gridSize * this.previousGrid[id].column + this.offSet}px`,`${this.gridSize * column + this.offSet}px`]
        }
      },
      getValue(id) {
        const { value } = this.grid[id];
        
        if (this.previousGrid[id].value == 0 && value == 1 || value == -1) {
          return '';
        }
        return value;
      },
      getBlockClass(prevValue, value) {
        if (prevValue == 0 && value == 1) {
          return "display-none";
        } else if (value == 1) {
          return "bg-primary";
        } else if (value > 1) {
          return `bg-block${value}`;
        } else if (value == - 1) {
          return `bg-obstacle`;
        }

        return "bg-empty";
      },
    },
    props: ['grid', 'previousGrid'],
}
</script>