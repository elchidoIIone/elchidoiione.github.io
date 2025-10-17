document.addEventListener('DOMContentLoaded', () => {
    
    const navButtons = document.querySelectorAll('#selectSection a');

    navButtons.forEach(link => {
        link.addEventListener('click', (event) => {
            // Se revisa el ancho de la ventana al hacer clic.
            if (window.innerWidth < 600) {
                
                event.preventDefault();

                const originalPage = link.getAttribute('href');

                
                const phonePage = originalPage.replace('.html', 'Phone.html');

            
                window.location.href = phonePage;
            }
        });
    });
});
/*(function(){
    const phoneBreakpoint = 600;

    const currentPath = window.location.pathname;

    if (window.innerWidth < phoneBreakpoint && currentPath.endsWith('.html') && !currentPath.endsWith('Phone.html')){
        const mobileUrl = currentPath.replace('.html', 'Phone.html');
        window.location.href = mobileUrl;
    }
})();*/