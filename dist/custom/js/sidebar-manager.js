function closeSidebar() {
    const sidebarMenu = bootstrap.Offcanvas.getInstance(document.getElementById('sidebarMenu'));
    if (sidebarMenu) {
        sidebarMenu.hide();
    }
}