import 'bootstrap-icons/font/bootstrap-icons.css';

const sidebar = document.getElementById(
    'admin-sidebar'
);

const adminMain = document.getElementById(
    'admin-main'
);

const collapseButton = document.getElementById(
    'sidebar-collapse'
);

const collapseIcon = document.getElementById(
    'sidebar-collapse-icon'
);

const collapseLabel = document.getElementById(
    'sidebar-collapse-label'
);

const openMobileButton = document.getElementById(
    'sidebar-open-mobile'
);

const closeMobileButton = document.getElementById(
    'sidebar-close-mobile'
);

const overlay = document.getElementById(
    'sidebar-overlay'
);

const sidebarLabels = document.querySelectorAll(
    '[data-sidebar-label]'
);

const desktopMediaQuery = window.matchMedia(
    '(min-width: 1024px)'
);

const SIDEBAR_STORAGE_KEY =
    'admin-sidebar-pinned';

const state = {
    pinned:
        localStorage.getItem(
            SIDEBAR_STORAGE_KEY
        ) === 'true',

    hovered: false,
    mobileOpen: false,
};

function isDesktop() {
    return desktopMediaQuery.matches;
}

function shouldExpandDesktopSidebar() {
    return state.pinned || state.hovered;
}

function showSidebarLabels() {
    sidebarLabels.forEach((label) => {
        label.classList.remove(
            'w-0',
            'opacity-0',
            'pointer-events-none'
        );

        label.classList.add(
            'opacity-100'
        );
    });
}

function hideSidebarLabels() {
    sidebarLabels.forEach((label) => {
        label.classList.remove(
            'opacity-100'
        );

        label.classList.add(
            'w-0',
            'opacity-0',
            'pointer-events-none'
        );
    });
}

function setMainExpanded(expanded) {
    if (!adminMain) {
        return;
    }

    if (expanded) {
        adminMain.classList.remove(
            'lg:ml-20'
        );

        adminMain.classList.add(
            'lg:ml-64'
        );

        return;
    }

    adminMain.classList.remove(
        'lg:ml-64'
    );

    adminMain.classList.add(
        'lg:ml-20'
    );
}

function setDesktopSidebarExpanded(
    expanded
) {
    if (!sidebar || !isDesktop()) {
        return;
    }

    sidebar.classList.remove(
        expanded
            ? 'lg:w-20'
            : 'lg:w-64'
    );

    sidebar.classList.add(
        expanded
            ? 'lg:w-64'
            : 'lg:w-20'
    );

    if (expanded) {
        showSidebarLabels();
    } else {
        hideSidebarLabels();
    }

    setMainExpanded(expanded);

    sidebar.dataset.sidebarPinned =
        state.pinned ? 'true' : 'false';
}

function updatePinButton() {
    if (
        !collapseButton ||
        !collapseIcon ||
        !collapseLabel
    ) {
        return;
    }

    collapseButton.setAttribute(
        'aria-expanded',
        state.pinned ? 'true' : 'false'
    );

    collapseIcon.className =
        state.pinned
            ? 'bi bi-pin-angle-fill text-lg'
            : 'bi bi-pin-angle text-lg';

    collapseLabel.textContent =
        state.pinned
            ? 'Lepas Sidebar'
            : 'Kunci Sidebar';

    collapseButton.title =
        state.pinned
            ? 'Biarkan sidebar menutup otomatis'
            : 'Buka sidebar secara permanen';
}

function refreshDesktopSidebar() {
    if (!isDesktop()) {
        return;
    }

    setDesktopSidebarExpanded(
        shouldExpandDesktopSidebar()
    );

    updatePinButton();
}

function toggleSidebarPin() {
    state.pinned = !state.pinned;

    localStorage.setItem(
        SIDEBAR_STORAGE_KEY,
        state.pinned ? 'true' : 'false'
    );

    refreshDesktopSidebar();
}

function handleSidebarMouseEnter() {
    if (!isDesktop()) {
        return;
    }

    state.hovered = true;

    refreshDesktopSidebar();
}

function handleSidebarMouseLeave() {
    if (!isDesktop()) {
        return;
    }

    state.hovered = false;

    refreshDesktopSidebar();
}

function openMobileSidebar() {
    if (!sidebar || isDesktop()) {
        return;
    }

    state.mobileOpen = true;

    sidebar.classList.remove(
        '-translate-x-full'
    );

    sidebar.classList.add(
        'translate-x-0'
    );

    overlay?.classList.remove(
        'hidden'
    );

    document.body.classList.add(
        'overflow-hidden'
    );

    showSidebarLabels();
}

function closeMobileSidebar() {
    if (!sidebar || isDesktop()) {
        return;
    }

    state.mobileOpen = false;

    sidebar.classList.remove(
        'translate-x-0'
    );

    sidebar.classList.add(
        '-translate-x-full'
    );

    overlay?.classList.add(
        'hidden'
    );

    document.body.classList.remove(
        'overflow-hidden'
    );
}

function handleBreakpointChange() {
    if (isDesktop()) {
        state.mobileOpen = false;

        sidebar?.classList.remove(
            '-translate-x-full',
            'translate-x-0'
        );

        sidebar?.classList.add(
            'lg:translate-x-0'
        );

        overlay?.classList.add(
            'hidden'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );

        state.hovered = false;

        refreshDesktopSidebar();

        return;
    }

    sidebar?.classList.remove(
        'lg:w-20',
        'lg:w-64'
    );

    sidebar?.classList.add(
        'w-72',
        '-translate-x-full'
    );

    setMainExpanded(false);
    showSidebarLabels();
    updatePinButton();
}

function closeMobileAfterNavigation(
    event
) {
    const menuLink = event.target.closest(
        'a'
    );

    if (!menuLink || isDesktop()) {
        return;
    }

    const href =
        menuLink.getAttribute('href');

    if (
        !href ||
        href === '#' ||
        href.startsWith('javascript:')
    ) {
        return;
    }

    closeMobileSidebar();
}

sidebar?.addEventListener(
    'mouseenter',
    handleSidebarMouseEnter
);

sidebar?.addEventListener(
    'mouseleave',
    handleSidebarMouseLeave
);

sidebar?.addEventListener(
    'click',
    closeMobileAfterNavigation
);

collapseButton?.addEventListener(
    'click',
    toggleSidebarPin
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

document.addEventListener(
    'keydown',
    (event) => {
        if (
            event.key === 'Escape' &&
            state.mobileOpen
        ) {
            closeMobileSidebar();
        }
    }
);

desktopMediaQuery.addEventListener(
    'change',
    handleBreakpointChange
);

handleBreakpointChange();