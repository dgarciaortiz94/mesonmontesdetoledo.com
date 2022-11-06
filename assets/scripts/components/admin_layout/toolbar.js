import vars from '../../../vars';
import axios from 'axios';

const toolbarNotificationsIcon = document.getElementById("toolbar__notifications-icon");
if (document.getElementById("toolbar__notifications-warning")) var toolbarNotificationsWarning = document.getElementById("toolbar__notifications-warning");

toolbarNotificationsIcon.addEventListener("click", e => {
    if (toolbarNotificationsWarning != null) {
        document.getElementById("toolbar__notifications-warning").remove();

        axios.get(vars.root + '/admin-panel/user/check-notifications/mark-as-viewed')
            .then(function (response) {
            // manejar respuesta exitosa
            })
            .catch(function (error) {
            // manejar error
                console.log(error);
            })
            .then(function () {
                // siempre sera executado
            });   
    }
})