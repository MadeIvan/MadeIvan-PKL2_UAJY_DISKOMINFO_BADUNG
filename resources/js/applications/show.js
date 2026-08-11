import 'bootstrap-icons/font/bootstrap-icons.css';

const sidebar = document.getElementById(
    'documentationSidebar'
);

const sidebarOverlay = document.getElementById(
    'sidebarOverlay'
);

const openSidebar = document.getElementById(
    'openSidebar'
);

const closeSidebar = document.getElementById(
    'closeSidebar'
);

const toggleDesktopSidebar = document.getElementById(
    'toggleDesktopSidebar'
);

const desktopSidebarIcon = document.getElementById(
    'desktopSidebarIcon'
);

const applicationVersion = document.getElementById(
    'applicationVersion'
);

const sidebarDetails = document.querySelectorAll(
    '[data-sidebar-detail]'
);

const treeToggleButtons = document.querySelectorAll(
    '[data-tree-toggle]'
);

let desktopSidebarCollapsed = false;

/*
|--------------------------------------------------------------------------
| Desktop Sidebar Collapse
|--------------------------------------------------------------------------
*/

function setDesktopSidebarCollapsed(collapsed) {
    if (!sidebar) {
        return;
    }

    desktopSidebarCollapsed = collapsed;

    sidebar.classList.toggle(
        'lg:w-16',
        collapsed
    );

    sidebar.classList.toggle(
        'lg:w-72',
        !collapsed
    );

    sidebarDetails.forEach((element) => {
        element.classList.toggle(
            'lg:hidden',
            collapsed
        );
    });

    if (desktopSidebarIcon) {
        desktopSidebarIcon.classList.toggle(
            'bi-layout-sidebar-inset',
            !collapsed
        );

        desktopSidebarIcon.classList.toggle(
            'bi-layout-sidebar-inset-reverse',
            collapsed
        );
    }

    if (toggleDesktopSidebar) {
        toggleDesktopSidebar.setAttribute(
            'aria-expanded',
            String(!collapsed)
        );

        toggleDesktopSidebar.setAttribute(
            'aria-label',
            collapsed
                ? 'Perluas sidebar'
                : 'Ciutkan sidebar'
        );

        toggleDesktopSidebar.setAttribute(
            'title',
            collapsed
                ? 'Perluas sidebar'
                : 'Ciutkan sidebar'
        );
    }
}

toggleDesktopSidebar?.addEventListener(
    'click',
    () => {
        setDesktopSidebarCollapsed(
            !desktopSidebarCollapsed
        );
    }
);

/*
|--------------------------------------------------------------------------
| Mobile Sidebar
|--------------------------------------------------------------------------
*/

function showMobileSidebar() {
    if (!sidebar) {
        return;
    }

    sidebar.classList.remove(
        '-translate-x-full'
    );

    sidebarOverlay?.classList.remove(
        'hidden'
    );

    document.body.classList.add(
        'overflow-hidden'
    );
}

function hideMobileSidebar() {
    if (!sidebar) {
        return;
    }

    sidebar.classList.add(
        '-translate-x-full'
    );

    sidebarOverlay?.classList.add(
        'hidden'
    );

    document.body.classList.remove(
        'overflow-hidden'
    );
}

openSidebar?.addEventListener(
    'click',
    showMobileSidebar
);

closeSidebar?.addEventListener(
    'click',
    hideMobileSidebar
);

sidebarOverlay?.addEventListener(
    'click',
    hideMobileSidebar
);

document.addEventListener(
    'keydown',
    (event) => {
        if (
            event.key === 'Escape' &&
            window.innerWidth < 1024
        ) {
            hideMobileSidebar();
        }
    }
);

/*
|--------------------------------------------------------------------------
| Version Switcher
|--------------------------------------------------------------------------
*/

applicationVersion?.addEventListener(
    'change',
    () => {
        const versionId =
            applicationVersion.value;

        const applicationSlug =
            applicationVersion.dataset
                .applicationSlug;

        if (
            !versionId ||
            !applicationSlug
        ) {
            return;
        }

        const url = new URL(
            `/applications/${encodeURIComponent(applicationSlug)}`,
            window.location.origin
        );

        url.searchParams.set(
            'version',
            versionId
        );

        window.location.href =
            url.toString();
    }
);

/*
|--------------------------------------------------------------------------
| Tutorial Tree
|--------------------------------------------------------------------------
*/

treeToggleButtons.forEach((button) => {
    button.addEventListener(
        'click',
        () => {
            const treeItem =
                button.closest(
                    '.tree-item'
                );

            const children =
                treeItem?.querySelector(
                    ':scope > [data-tree-children]'
                );

            const arrow =
                button.querySelector(
                    '[data-tree-arrow]'
                );

            if (!children) {
                return;
            }

            const willOpen =
                children.classList.contains(
                    'hidden'
                );

            children.classList.toggle(
                'hidden'
            );

            button.setAttribute(
                'aria-expanded',
                String(willOpen)
            );

            if (arrow) {
                arrow.classList.toggle(
                    'bi-chevron-down',
                    willOpen
                );

                arrow.classList.toggle(
                    'bi-chevron-right',
                    !willOpen
                );
            }
        }
    );
});

/*
|--------------------------------------------------------------------------
| Responsive Reset
|--------------------------------------------------------------------------
*/

window.addEventListener(
    'resize',
    () => {
        if (window.innerWidth >= 1024) {
            sidebarOverlay?.classList.add(
                'hidden'
            );

            document.body.classList.remove(
                'overflow-hidden'
            );

            sidebar?.classList.remove(
                '-translate-x-full'
            );

            return;
        }

        sidebar?.classList.add(
            '-translate-x-full'
        );

        sidebarOverlay?.classList.add(
            'hidden'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );
    }
);