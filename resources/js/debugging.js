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

var xDown = null;                                                        
var yDown = null;                                                        

function handleTouchStart(evt) {                                         
    xDown = evt.originalEvent.touches[0].clientX;                                      
    yDown = evt.originalEvent.touches[0].clientY;                                      
};                                                

// https://stackoverflow.com/questions/2264072/detect-a-finger-swipe-through-javascript-on-the-iphone-and-android
function handleTouchMove(evt) {
    if ( ! xDown || ! yDown ) {
        return;
    }

    var xUp = evt.originalEvent.touches[0].clientX;                                    
    var yUp = evt.originalEvent.touches[0].clientY;

    var xDiff = xDown - xUp;
    var yDiff = yDown - yUp;

    if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {/*most significant*/
        if ( xDiff > 0 ) {
            triggerMovement('left');
        } else {
            triggerMovement('right');
        }                       
    } else {
        if ( yDiff > 0 ) {
            triggerMovement('up');
        } else { 
            triggerMovement('down');
        }                                                                 
    }
    /* reset values */
    xDown = null;
    yDown = null;                                             
};

document.addEventListener('touchstart', handleTouchStart, false);        
document.addEventListener('touchmove', handleTouchMove, false);