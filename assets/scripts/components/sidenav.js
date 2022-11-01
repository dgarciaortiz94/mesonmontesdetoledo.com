const sidenavTrigger = document.getElementsByClassName('sidenav-trigger');
const sidenavWrapper = document.getElementsByClassName('sidenav-wrapper');

for (const element of sidenavWrapper) {
    if (element.dataset.position == "right") element.classList.add('right');
    else element.classList.add('left');
}

for (const element of sidenavTrigger) {
    element.addEventListener('click', e => {
        // closeAllSidenavs();

        const dataTarget = e.currentTarget.dataset.target;
        const sidenav = document.querySelectorAll(dataTarget);

        document.getElementsByTagName('body')[0].style.overflow = 'hidden';
        
        for (const element of sidenav) {
            element.style.display = 'block';

            setTimeout(() => {
                element.classList.add('opened');
            }, 250);
        }
    })
}

for (const element of sidenavWrapper) {
    element.addEventListener('click', e => {
        if ((! e.target.classList.contains('sidenav')) && (! e.target.closest('.sidenav'))) {
            closeAllSidenavs();
        }
    })
}


function closeAllSidenavs() {
    for (const element of sidenavWrapper) {
        element.classList.remove('opened');

        setTimeout(() => {
            element.style.display = 'none';
        }, 400);
    }

    setTimeout(() => {
        document.getElementsByTagName('body')[0].style.overflow = 'auto';
    }, 400);
}
