import vars from '../../vars';

const asyncTriggers = document.querySelectorAll("[async]");

for (const trigger of asyncTriggers) {
    trigger.addEventListener('click', e => {
        const path = e.currentTarget.dataset.asyncPath;
        const htmlWrapper = $(e.currentTarget.dataset.asyncTarget);

        $.ajax({
            url : path,
            type : 'GET',
            dataType : 'html',
            success : function(html) {
                htmlWrapper.html(html);
            },
            error : function(xhr, status) {
                console.log(xhr.responseText);
            }
        });

    })
}