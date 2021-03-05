import '@fortawesome/fontawesome-free/js/all';
import notify from "./notify";

window._notify = notify;

window._ = require('lodash');

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
