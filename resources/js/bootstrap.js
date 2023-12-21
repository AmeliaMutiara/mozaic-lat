import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
// import $ from 'jquery';
// window.$ = $;
import moment from 'moment';
window.moment = moment;
import Swal from 'sweetalert2'
window.Swal = Swal;
import toastr from 'toastr'
window.toastr = toastr;
// import select2 from 'select2'
// window.select2 = select2;
import select2 from 'select2';
select2();
import { createPopper } from '@popperjs/core';
createPopper();
const button = document.querySelector('#sys-show-quote');
const tooltip = document.querySelector('#quote-content');
const tooltipBody = document.querySelector('#quote-body');

// Pass the button, the tooltip, and some options, and Popper will do the
// magic positioning for you:
const popperInstance = createPopper(button, tooltip, {
  placement: 'left',
  modifiers: [
    {
      name: 'offset',
      options: {
        offset: [-5, 8],
      },
    },
  ],
});
function show() {
tooltip.setAttribute('data-show', '');
axios({
    method: 'get',
    url: 'http://127.0.0.1:8090/quote',
    responseType: 'html'
  })
    .then(function (response) {
        tooltip.innerHTML = response.data;
        popperInstance.update();
    });
 // Enable the event listeners
 popperInstance.setOptions((options) => ({
    ...options,
    modifiers: [
      ...options.modifiers,
      { name: 'eventListeners', enabled: true },
    ],
  }));

  // Update its position
popperInstance.update();
}
function hide() {
tooltip.removeAttribute('data-show');
  // Disable the event listeners
  popperInstance.setOptions((options) => ({
    ...options,
    modifiers: [
      ...options.modifiers,
      { name: 'eventListeners', enabled: false },
    ],
  }));
}

const showEvents = ['click'];
const hideEvents = ['blur','focusout'];
showEvents.forEach((event) => {
  button.addEventListener(event, show);
});

hideEvents.forEach((event) => {
  button.addEventListener(event, hide);
});