// left: 37, up: 38, right: 39, down: 40,
// spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
var keys = {37: 1, 38: 1, 39: 1, 40: 1};

function preventDefault(e) {
  e.preventDefault();
}

function preventDefaultForScrollKeys(e) {
  if (keys[e.keyCode]) {
    preventDefault(e);
    return false;
  }
}

// modern Chrome requires { passive: false } when adding event
var supportsPassive = false;
try {
  document.body.addEventListener("test", null, Object.defineProperty({}, 'passive', {
    get: function () { supportsPassive = true; } 
  }));
} catch(e) {}

var wheelOpt = supportsPassive ? { passive: false } : false;
var wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';

// call this to Disable
function disableScroll() {
  document.body.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
  document.body.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
  document.body.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
  document.body.addEventListener('keydown', preventDefaultForScrollKeys, false);
}

// call this to Enable
function enableScroll() {
  document.body.removeEventListener('DOMMouseScroll', preventDefault, false);
  document.body.removeEventListener(wheelEvent, preventDefault, wheelOpt); 
  document.body.removeEventListener('touchmove', preventDefault, wheelOpt);
  document.body.removeEventListener('keydown', preventDefaultForScrollKeys, false);
}