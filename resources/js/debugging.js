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
    xDown = evt.touches[0].clientX;                                      
    yDown = evt.touches[0].clientY;                                      
};                                                

function handleTouchMove(evt) {
    if ( ! xDown || ! yDown ) {
        return;
    }
    var xUp = evt.touches[0].clientX;                                    
    var yUp = evt.touches[0].clientY;

    var xDiff = xDown - xUp;
    var yDiff = yDown - yUp;

    var grid = document.querySelector('#board');
    if (grid.contains(evt.target)) {
        if ( Math.abs( xDiff ) > Math.abs( yDiff ) ) {/*most significant*/
            if ( xDiff > 0 ) {
                console.log("LEEEEFT");
                triggerMovement('left');
            } else {
                triggerMovement('right');
            }                       
        } else {
            if ( yDiff > 0 ) {
                console.log("top");
                triggerMovement('top');
            } else { 
                triggerMovement('bottom');
            }                                                                 
        }
    }   

    /* reset values */
    xDown = null;
    yDown = null;                                             
};

document.addEventListener('touchstart', handleTouchStart, false);        
document.addEventListener('touchmove', handleTouchMove, false);