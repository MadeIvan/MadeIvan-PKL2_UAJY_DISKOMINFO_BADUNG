import 'bootstrap-icons/font/bootstrap-icons.css';

const sidebar = document.getElementById('admin-sidebar');
const adminMain = document.getElementById('admin-main');
const collapseButton = document.getElementById(
    'sidebar-collapse'
);
const collapseIcon = document.getElementById(
    'sidebar-collapse-icon'
);
const openMobileButton = document.getElementById(
    'sidebar-open-mobile'
);
const closeMobileButton = document.getElementById(
    'sidebar-close-mobile'
);
const overlay = document.getElementById('sidebar-overlay');

const sidebarLabels = document.querySelectorAll(
    '[data-sidebar-label]'
);

const SIDEBAR_STORAGE_KEY = 'admin-sidebar-collapsed';

function setSidebarCollapsed(collapsed) {
    if (!sidebar || !adminMain) {
        return;
    }

    if (collapsed) {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');

        adminMain.classList.remove('lg:ml-64');
        adminMain.classList.add('lg:ml-20');

        sidebarLabels.forEach((label) => {
            label.classList.add('hidden');
        });

        collapseIcon?.classList.remove('bi-chevron-left');
        collapseIcon?.classList.add('bi-chevron-right');

        localStorage.setItem(
            SIDEBAR_STORAGE_KEY,
            'true'
        );

        return;
    }

    sidebar.classList.remove('w-20');
    sidebar.classList.add('w-64');

    adminMain.classList.remove('lg:ml-20');
    adminMain.classList.add('lg:ml-64');

    sidebarLabels.forEach((label) => {
        label.classList.remove('hidden');
    });

    collapseIcon?.classList.remove('bi-chevron-right');
    collapseIcon?.classList.add('bi-chevron-left');

    localStorage.setItem(
        SIDEBAR_STORAGE_KEY,
        'false'
    );
}

function toggleSidebarCollapse() {
    const isCollapsed = sidebar?.classList.contains('w-20');

    setSidebarCollapsed(!isCollapsed);
}

function openMobileSidebar() {
    sidebar?.classList.remove('-translate-x-full');
    overlay?.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeMobileSidebar() {
    sidebar?.classList.add('-translate-x-full');
    overlay?.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

collapseButton?.addEventListener(
    'click',
    toggleSidebarCollapse
);

openMobileButton?.addEventListener(
    'click',
    openMobileSidebar
);

closeMobileButton?.addEventListener(
    'click',
    closeMobileSidebar
);

overlay?.addEventListener(
    'click',
    closeMobileSidebar
);

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeMobileSidebar();
    }
});

const savedState =
    localStorage.getItem(SIDEBAR_STORAGE_KEY) === 'true';

setSidebarCollapsed(savedState);