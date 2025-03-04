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


import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

if (getValueFromId('is_pusher_active')) {
    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: getValueFromId("f_pusher_app_key"),
        cluster: getValueFromId("f_pusher_app_cluster"),
        forceTLS: true
    });
}

function getValueFromId(id) {
    let value = '';
    let input_box = document.getElementById(id);

    if (input_box) {
        value = input_box.value;
    }
    return value;
}