import 'bootstrap-icons/font/bootstrap-icons.css';

const sidebar =
    document.getElementById(
        'admin-sidebar'
    );

const adminMain =
    document.getElementById(
        'admin-main'
    );

const pinButton =
    document.getElementById(
        'sidebar-collapse'
    );

const pinIcon =
    document.getElementById(
        'sidebar-collapse-icon'
    );

const pinLabel =
    document.getElementById(
        'sidebar-collapse-label'
    );

const openMobileButton =
    document.getElementById(
        'sidebar-open-mobile'
    );

const closeMobileButton =
    document.getElementById(
        'sidebar-close-mobile'
    );

const overlay =
    document.getElementById(
        'sidebar-overlay'
    );

const sidebarLabels =
    document.querySelectorAll(
        '[data-sidebar-label]'
    );

const SIDEBAR_PIN_KEY =
    'admin-sidebar-pinned';

let sidebarPinned =
    localStorage.getItem(
        SIDEBAR_PIN_KEY
    ) === 'true';

let sidebarHovered = false;

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function isDesktop() {
    return window.innerWidth >= 1024;
}

function setLabelsVisible(visible) {
    sidebarLabels.forEach((label) => {
        label.classList.toggle(
            'hidden',
            !visible
        );

        label.classList.toggle(
            'opacity-0',
            !visible
        );

        label.classList.toggle(
            'opacity-100',
            visible
        );
    });
}

function updatePinButton() {
    if (!pinButton) {
        return;
    }

    pinButton.setAttribute(
        'aria-pressed',
        String(sidebarPinned)
    );

    pinButton.setAttribute(
        'title',
        sidebarPinned
            ? 'Lepas kunci sidebar'
            : 'Kunci sidebar'
    );

    pinButton.classList.toggle('bg-white/10', sidebarPinned);
    pinButton.classList.toggle('text-white', sidebarPinned);
    pinButton.classList.toggle('text-blue-100', !sidebarPinned);
}

/*
|--------------------------------------------------------------------------
| Desktop Sidebar State
|--------------------------------------------------------------------------
*/

function setDesktopExpanded(expanded) {
    if (
        !sidebar ||
        !adminMain ||
        !isDesktop()
    ) {
        return;
    }

    sidebar.classList.toggle(
        'lg:w-72',
        expanded
    );

    sidebar.classList.toggle(
        'lg:w-20',
        !expanded
    );

    adminMain.classList.toggle(
        'lg:ml-72',
        expanded
    );

    adminMain.classList.toggle(
        'lg:ml-20',
        !expanded
    );

    setLabelsVisible(
        expanded
    );

    sidebar.dataset.sidebarExpanded =
        String(expanded);
}

function refreshDesktopSidebar() {
    if (!isDesktop()) {
        return;
    }

    const shouldExpand =
        sidebarPinned ||
        sidebarHovered;

    setDesktopExpanded(
        shouldExpand
    );
}

/*
|--------------------------------------------------------------------------
| Hover Behavior
|--------------------------------------------------------------------------
*/

sidebar?.addEventListener(
    'mouseenter',
    () => {
        if (!isDesktop()) {
            return;
        }

        sidebarHovered = true;

        refreshDesktopSidebar();
    }
);

sidebar?.addEventListener(
    'mouseleave',
    () => {
        if (!isDesktop()) {
            return;
        }

        sidebarHovered = false;

        refreshDesktopSidebar();
    }
);

/*
|--------------------------------------------------------------------------
| Pin Behavior
|--------------------------------------------------------------------------
*/

pinButton?.addEventListener(
    'click',
    () => {
        if (!isDesktop()) {
            return;
        }

        sidebarPinned =
            !sidebarPinned;

        localStorage.setItem(
            SIDEBAR_PIN_KEY,
            String(sidebarPinned)
        );

        sidebar.dataset.sidebarPinned =
            String(sidebarPinned);

        updatePinButton();
        refreshDesktopSidebar();
    }
);

/*
|--------------------------------------------------------------------------
| Mobile Sidebar
|--------------------------------------------------------------------------
*/

function openMobileSidebar() {
    if (
        !sidebar ||
        isDesktop()
    ) {
        return;
    }

    sidebar.classList.remove(
        '-translate-x-full'
    );

    overlay?.classList.remove(
        'hidden'
    );

    document.body.classList.add(
        'overflow-hidden'
    );
}

function closeMobileSidebar() {
    if (
        !sidebar ||
        isDesktop()
    ) {
        return;
    }

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
            !isDesktop()
        ) {
            closeMobileSidebar();
        }
    }
);

/*
|--------------------------------------------------------------------------
| Responsive
|--------------------------------------------------------------------------
*/

function handleResponsiveState() {
    if (!sidebar) {
        return;
    }

    if (isDesktop()) {
        sidebar.classList.remove(
            '-translate-x-full'
        );

        overlay?.classList.add(
            'hidden'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );

        refreshDesktopSidebar();

        return;
    }

    sidebar.classList.add(
        '-translate-x-full'
    );

    overlay?.classList.add(
        'hidden'
    );

    document.body.classList.remove(
        'overflow-hidden'
    );

    setLabelsVisible(
        true
    );
}

window.addEventListener(
    'resize',
    handleResponsiveState
);

/*
|--------------------------------------------------------------------------
| Initial State
|--------------------------------------------------------------------------
*/

sidebar?.setAttribute(
    'data-sidebar-pinned',
    String(sidebarPinned)
);

updatePinButton();
handleResponsiveState();