<template>
  <div class="relative p-4 rounded-sm h-68 w-68 tablet:w-84 tablet:h-84 tablet:mt-4 desktop:mt-0 bg-backgroundDark" id="board">
    <div v-for="id in Object.keys(grid)" :key="`${id}${grid[id].row}${grid[id].column}${grid[id].value}`" :ref="id"
      v-anime="{top: getTopValue(id), left: getLeftValue(id), duration: 250}"
      :class="`flex absolute items-center font-black text-white justify-center rounded-sm w-11 h-11 tablet:w-18 tablet:h-18 ${getBlockClass(previousGrid[id].value, grid[id].value)} ${getAnimations(id)}`">
        {{ getValue(id) }}
    </div>
  </div>
</template>
<script>
export default {
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
        }
      });
    },
    methods: {
      getTopValue(id) {
        if (this.grid[id].value == 0) {
          return [`${78 * this.grid[id].row}px`, `${78 * this.grid[id].row}px`]
        } else {
          return [`${78 * this.previousGrid[id].row}px`, `${78 * this.grid[id].row}px`]
        }
      },
      getLeftValue(id) {
        if (this.grid[id].value == 0) {
          return [`${78 * this.previousGrid[id].column}px`, `${78 * this.grid[id].column}px`]
        } else {
          return [`${78 * this.previousGrid[id].column}px`,`${78 * this.grid[id].column}px`]
        }
      },
      getValue(id) {
        if (this.previousGrid[id].value == 0 && this.grid[id].value == 1) {
          return '';
        } else if (this.grid[id].value > 0) {
          return this.grid[id].value;
        }
      },
      getAnimations(id) {
        if (this.previousGrid[id].value != this.grid[id].value) {
          return this.grid[id].value == 0 ? 'animation-dissapear' : 'animation-merge';
        }

        return '';
      },
      getBlockClass(prevValue, value) {
        if (prevValue == 0 && value == 1) {
          return "bg-empty";
        }
        
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
    props: ['grid', 'previousGrid'],
}
</script>

<style scoped>
#board {
  transition-delay: 5000ms;
}
</style>