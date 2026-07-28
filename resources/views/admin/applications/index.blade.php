<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Application Management</title>

    @vite([
        'resources/css/app.css',
        'resources/js/admin/applications/index.js'
    ])
</head>

<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="mx-auto max-w-7xl px-4 py-8">
        <header class="mb-6">
            <h1 class="text-3xl font-bold">
                Application Management
            </h1>

            <p class="mt-1 text-sm text-slate-600">
                Manage applications and their version history.
            </p>
        </header>

        <div
            id="notification"
            class="mb-5 hidden rounded-lg border px-4 py-3 text-sm"
        ></div>

        <section class="mb-6 rounded-xl bg-white p-6 shadow-sm">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <h2
                        id="application-form-title"
                        class="text-xl font-semibold"
                    >
                        Add Application
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Enter the main application information.
                    </p>
                </div>

                <button
                    id="cancel-application-edit"
                    type="button"
                    class="hidden items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-100"
                >
                    <i class="bi bi-x-lg"></i>
                    Cancel Edit
                </button>
            </div>

            <form id="application-form">
                <input
                    id="application-id"
                    type="hidden"
                >

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label
                            for="application-name"
                            class="mb-1 block text-sm font-medium"
                        >
                            Application Name
                        </label>

                        <input
                            id="application-name"
                            type="text"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Knowledge Management System"
                        >
                    </div>

                    <div>
                        <label
                            for="application-slug"
                            class="mb-1 block text-sm font-medium"
                        >
                            Slug
                        </label>

                        <input
                            id="application-slug"
                            type="text"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="knowledge-management-system"
                        >
                    </div>

                    <div>
                        <label
                            for="application-category"
                            class="mb-1 block text-sm font-medium"
                        >
                            Category
                        </label>

                        <input
                            id="application-category"
                            type="text"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Internal System"
                        >
                    </div>

                    <div>
                        <label
                            for="application-status"
                            class="mb-1 block text-sm font-medium"
                        >
                            Status
                        </label>

                        <select
                            id="application-status"
                            required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label
                            for="application-description"
                            class="mb-1 block text-sm font-medium"
                        >
                            Description
                        </label>

                        <textarea
                            id="application-description"
                            rows="4"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                            placeholder="Describe the application..."
                        ></textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="inline-flex cursor-pointer items-center gap-2">
                            <input
                                id="application-is-public"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-300"
                            >

                            <span class="text-sm font-medium">
                                Make this application public
                            </span>
                        </label>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        id="application-submit-button"
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <i class="bi bi-plus-lg"></i>
                        <span>Save Application</span>
                    </button>
                </div>
            </form>
        </section>

        <section class="mb-6 rounded-xl bg-white p-5 shadow-sm">
            <label
                for="application-search"
                class="mb-2 block text-sm font-medium"
            >
                Search Applications
            </label>

            <div class="relative">
                <i class="bi bi-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                <input
                    id="application-search"
                    type="search"
                    class="w-full rounded-lg border border-slate-300 py-3 pl-11 pr-4 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    placeholder="Search by name, category, slug, status, or version..."
                >
            </div>
        </section>

        <section class="overflow-hidden rounded-xl bg-white shadow-sm">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">
                        Application List
                    </h2>

                    <p
                        id="application-count"
                        class="mt-1 text-sm text-slate-500"
                    >
                        Loading applications...
                    </p>
                </div>

                <button
                    id="refresh-applications"
                    type="button"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-100"
                >
                    <i class="bi bi-arrow-clockwise"></i>
                    Refresh
                </button>
            </div>

            <div
                id="application-loading"
                class="p-8 text-center text-slate-500"
            >
                <i class="bi bi-arrow-repeat mr-2 inline-block animate-spin"></i>
                Loading application data...
            </div>

            <div
                id="application-empty"
                class="hidden p-8 text-center text-slate-500"
            >
                <i class="bi bi-inbox mb-3 block text-3xl"></i>
                No applications found.
            </div>

            <div
                id="application-list"
                class="hidden divide-y"
            ></div>
        </section>
    </main>

    <div
        id="version-modal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
        aria-hidden="true"
    >
        <div class="max-h-[90vh] w-full max-w-4xl overflow-y-auto rounded-xl bg-white shadow-xl">
            <div class="sticky top-0 z-10 flex items-start justify-between border-b bg-white px-6 py-4">
                <div>
                    <h2 class="text-xl font-semibold">
                        Manage Versions
                    </h2>

                    <p
                        id="version-application-name"
                        class="mt-1 text-sm text-slate-500"
                    ></p>
                </div>

                <button
                    id="close-version-modal"
                    type="button"
                    title="Close"
                    aria-label="Close"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-300 hover:bg-slate-100"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="grid gap-6 p-6 lg:grid-cols-[0.9fr_1.1fr]">
                <section class="rounded-xl border border-slate-200 p-5">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <h3
                                id="version-form-title"
                                class="text-lg font-semibold"
                            >
                                Add Version
                            </h3>

                            <p class="mt-1 text-sm text-slate-500">
                                Create a new application version.
                            </p>
                        </div>

                        <button
                            id="cancel-version-edit"
                            type="button"
                            class="hidden items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700"
                        >
                            <i class="bi bi-x-lg"></i>
                            Cancel
                        </button>
                    </div>

                    <form id="version-form">
                        <input
                            id="version-application-id"
                            type="hidden"
                        >

                        <input
                            id="version-id"
                            type="hidden"
                        >

                        <div class="space-y-4">
                            <div>
                                <label
                                    for="version-number"
                                    class="mb-1 block text-sm font-medium"
                                >
                                    Version Number
                                </label>

                                <input
                                    id="version-number"
                                    type="text"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                    placeholder="1.0.0"
                                >
                            </div>

                            <div>
                                <label
                                    for="version-release-date"
                                    class="mb-1 block text-sm font-medium"
                                >
                                    Release Date
                                </label>

                                <input
                                    id="version-release-date"
                                    type="date"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >
                            </div>

                            <div>
                                <label
                                    for="version-status"
                                    class="mb-1 block text-sm font-medium"
                                >
                                    Version Status
                                </label>

                                <select
                                    id="version-status"
                                    required
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="beta">Beta</option>
                                    <option value="stable">Stable</option>
                                    <option value="deprecated">Deprecated</option>
                                </select>
                            </div>

                            <div>
                                <label
                                    for="version-release-notes"
                                    class="mb-1 block text-sm font-medium"
                                >
                                    Release Notes
                                </label>

                                <textarea
                                    id="version-release-notes"
                                    rows="4"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                    placeholder="Describe the changes in this version..."
                                ></textarea>
                            </div>

                            <label class="inline-flex cursor-pointer items-center gap-2">
                                <input
                                    id="version-is-current"
                                    type="checkbox"
                                    class="h-4 w-4 rounded border-slate-300"
                                >

                                <span class="text-sm font-medium">
                                    Set as current version
                                </span>
                            </label>
                        </div>

                        <button
                            id="version-submit-button"
                            type="submit"
                            class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <i class="bi bi-plus-lg"></i>
                            <span>Save Version</span>
                        </button>
                    </form>
                </section>

                <section>
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">
                            Version History
                        </h3>

                        <p
                            id="version-count"
                            class="mt-1 text-sm text-slate-500"
                        ></p>
                    </div>

                    <div
                        id="version-empty"
                        class="hidden rounded-xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500"
                    >
                        <i class="bi bi-clock-history mb-3 block text-3xl"></i>
                        This application does not have any versions yet.
                    </div>

                    <div
                        id="version-list"
                        class="space-y-3"
                    ></div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>