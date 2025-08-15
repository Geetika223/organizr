(() => {
    // Get elements (same IDs on all pages)
    const toggleBtn = document.getElementById("toggle-btn");
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content") || document.querySelector(".main-wrapper");

    if (!sidebar || !toggleBtn) return; // Skip if page doesn't have sidebar

    // Toggle sidebar
    toggleBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        sidebar.classList.toggle("show");
        sidebar.classList.toggle("hide");

        if (mainContent) {
            mainContent.classList.toggle("shifted");
        }
    });

    // Close sidebar when clicking outside
    document.addEventListener("click", (e) => {
        const clickedOutsideSidebar = !sidebar.contains(e.target);
        const clickedOutsideToggle = !toggleBtn.contains(e.target);

        if (clickedOutsideSidebar && clickedOutsideToggle) {
            sidebar.classList.remove("show");
            sidebar.classList.add("hide");
            if (mainContent) {
                mainContent.classList.remove("shifted");
            }
        }
    });
})();
