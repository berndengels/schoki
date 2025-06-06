try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {
    console.error(e)
}
import moment from 'moment';
window.moment = moment;
window.moment.locale('de');
