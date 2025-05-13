const loaderContainer = document.getElementById('loaderContainer');

    function showLoader() {
        loaderContainer.classList.add('show');
    }

    function hideLoader() {
        loaderContainer.classList.remove('show');
    }

    //    automatic loader
    document.addEventListener('DOMContentLoaded', function() {
        const loaderContainer = document.getElementById('loaderContainer');
        const contentWrapper = document.getElementById('contentWrapper');
        let loadingTimeout;

        function hideLoader() {
            loaderContainer.classList.add('hide');
            contentWrapper.classList.add('show');
        }

        function showError() {
            console.error('Page load took too long or encountered an error');
            // You can add custom error handling here
        }

        // Set a maximum loading time (10 seconds)
        loadingTimeout = setTimeout(showError, 10000);

        // Hide loader when everything is loaded
        window.onload = function() {
            clearTimeout(loadingTimeout);

            // Add a small delay to ensure smooth transition
            setTimeout(hideLoader, 500);
        };

        // Error handling
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            clearTimeout(loadingTimeout);
            showError();
            return false;
        };
    });

    // Toggle Sidebar
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const body = document.body;
    const mobileOverlay = document.getElementById('mobileOverlay');

    function toggleSidebar() {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('mobile-show');
            mobileOverlay.classList.toggle('show');
            body.classList.toggle('sidebar-open');
        } else {
            sidebar.classList.toggle('collapsed');
        }
    }
    hamburger.addEventListener('click', toggleSidebar);
    mobileOverlay.addEventListener('click', toggleSidebar);
    // Toggle User Menu
    const userMenu = document.getElementById('userMenu');
    const dropdownMenu = userMenu.querySelector('.dropdown-menu');

    userMenu.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', () => {
        dropdownMenu.classList.remove('show');
    });

    // Toggle Submenu
    const menuItems = document.querySelectorAll('.has-submenu');
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            const submenu = item.nextElementSibling;
            item.classList.toggle('active');
            submenu.classList.toggle('active');
        });
    });

    // Handle responsive behavior
    window.addEventListener('resize', () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove('collapsed');
            sidebar.classList.remove('mobile-show');
            mobileOverlay.classList.remove('show');
            body.classList.remove('sidebar-open');
        } else {
            sidebar.style.transform = '';
            mobileOverlay.classList.remove('show');
            body.classList.remove('sidebar-open');
        }
    });