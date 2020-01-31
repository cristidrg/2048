window.csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content')

const triggerMovement = type => axios.post('/api/game/1/message', {
    csrf: window.csrf,
    content: type
  })
  .catch(err => alert(err));

window.addEventListener('keyup', e => {
    if (document.activeElement && document.activeElement.getAttribute("name") != "input") {
        if (event.keyCode == 37) {
            triggerMovement('left');
        } else if (event.keyCode == 38) {
            triggerMovement('top');
        } else if (event.keyCode == 39) {
            triggerMovement('right');
        } else if (event.keyCode == 40) {
            triggerMovement('bottom');
        }
    }
});