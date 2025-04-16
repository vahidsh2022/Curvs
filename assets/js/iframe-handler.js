document.addEventListener("DOMContentLoaded", function () {

    if (window.self !== window.top) {
        const header = document.querySelector(".main-header"); // اگر کلاس است

        if (header) {
            header.remove();
        }


        const sidebar = document.querySelector('.main-sidebar');
        if(sidebar) {
            sidebar.remove();
        }

        const contentHeader = document.querySelector('.content-header')
        if(contentHeader) {
            contentHeader.remove();
        }
        const contentWrapper = document.querySelector('.content-wrapper');
        if(contentWrapper) {
            contentWrapper.classList.remove('content-wrapper');
        }

        const footer = document.querySelector('.main-footer');
        if(footer) {
            footer.remove();
        }

        // document.querySelectorAll("iframe.auto-height").forEach(iframe => {
        //     iframe.onload = function () {
        //         setTimeout(() => {
        //             iframe.style.height = 1000 + "px";
        //         }, 100);
        //     };
        // });

    }
});