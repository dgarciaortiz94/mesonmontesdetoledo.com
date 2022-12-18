import vars from '../../../vars';
import axios from 'axios';

const notificationTrigger = document.getElementById("toolbar__notifications-icon");
if (document.getElementById("toolbar__notifications-warning")) var toolbarNotificationsWarning = document.getElementById("toolbar__notifications-warning");

notificationTrigger.addEventListener("click", e => {
    if (toolbarNotificationsWarning != null) {
        document.getElementById("toolbar__notifications-warning").remove();

        axios.get(vars.root + '/admin-panel/notification-component/set-as-watched')
            .then(response => {
                if (response.data.success) console.log('leido');
            })
            .catch(error => {
                console.log(error);
            })
            .then(() => {
                // siempre sera executado
            });
    }
})