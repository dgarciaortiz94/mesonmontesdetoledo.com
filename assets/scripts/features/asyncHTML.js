import vars from '../../vars';
import axios from 'axios';

const asyncTriggers = document.querySelectorAll("[async]");

for (const trigger of asyncTriggers) {
    trigger.addEventListener('click', e => {
        const path = e.currentTarget.dataset.asyncPath;
        const htmlWrapper = document.querySelector(e.currentTarget.dataset.asyncTarget);

        axios.get(path)
            .then(function (response) {
                htmlWrapper.innerHTML = response.data;
            })
            .catch(function (error) {
                console.log(error);
            })
            .then(function () {
                // siempre sera executado
            }); 
    })
}