// Inizializzazione quando la pagina è caricata
document.addEventListener('DOMContentLoaded', function() {
    console.log('Deja-brew caricato!');
    
    // Event listeners per la sidebar
    const sidebarMenu = document.getElementById('sidebarMenu');
    
    sidebarMenu.addEventListener('show.bs.offcanvas', function() {
        console.log('Sidebar sta per aprirsi');
    });
    
    sidebarMenu.addEventListener('shown.bs.offcanvas', function() {
        console.log('Sidebar aperta');
    });
    
    sidebarMenu.addEventListener('hide.bs.offcanvas', function() {
        console.log('Sidebar sta per chiudersi');
    });
    
    sidebarMenu.addEventListener('hidden.bs.offcanvas', function() {
        console.log('Sidebar chiusa');
    });
});

function closeSidebar() {
    const sidebarMenu = bootstrap.Offcanvas.getInstance(document.getElementById('sidebarMenu'));
    if (sidebarMenu) {
        sidebarMenu.hide();
    }
}