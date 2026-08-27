<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login — Pusat Pengetahuan</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js',
    ])

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        @keyframes loginFade {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes waveFloat {
            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(-28px);
            }
        }

        @keyframes waveFloatReverse {
            0%,
            100% {
                transform: translateX(0);
            }

            50% {
                transform: translateX(34px);
            }
        }

        .login-animate {
            animation: loginFade .45s ease-out both;
        }

        .do-spin {
            display: inline-block;
            animation: spin .7s linear infinite;
        }

        .wave-one {
            animation: waveFloat 13s ease-in-out infinite;
        }

        .wave-two {
            animation: waveFloatReverse 17s ease-in-out infinite;
        }

        .wave-three {
            animation: waveFloat 21s ease-in-out infinite;
        }

        @media (prefers-reduced-motion: reduce) {
            .login-animate,
            .wave-one,
            .wave-two,
            .wave-three {
                animation: none;
            }
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-950 font-sans text-slate-900">

    {{-- =========================================================
        NAVBAR
    ========================================================== --}}
    @include('components.navbar')


    {{-- =========================================================
        BACKGROUND
    ========================================================== --}}
    <div
        class="
            fixed inset-x-0 bottom-0 top-16
            z-0
            overflow-hidden
            bg-[#061a3b]
        "
    >
        {{-- Background Image --}}
        <img
            src="{{ asset('images/pemkab-badung_169.png') }}"
            alt="Pemerintah Kabupaten Badung"
            class="
                absolute inset-0
                h-full w-full
                object-cover
                object-center
            "
        >

        {{-- Main Dark Overlay --}}
        <div
            class="
                absolute inset-0
                bg-slate-950/55
            "
        ></div>

        {{-- Main Horizontal Gradient --}}
        <div
            class="
                absolute inset-0
                bg-gradient-to-r
                from-[#04152f]/95
                via-[#071f45]/82
                to-[#0b3c78]/50
            "
        ></div>

        {{-- Subtle bottom gradient --}}
        <div
            class="
                absolute inset-0
                bg-gradient-to-t
                from-[#020817]/80
                via-transparent
                to-[#071f45]/20
            "
        ></div>


        {{-- =====================================================
            WAVY SVG BACKGROUND
        ====================================================== --}}
        <div
            class="
                pointer-events-none
                absolute inset-0
                overflow-hidden
            "
            aria-hidden="true"
        >
            {{-- Wave 1 --}}
            <svg
                class="
                    wave-one
                    absolute
                    -bottom-12
                    -left-[10%]
                    h-[48%]
                    w-[120%]
                    opacity-80
                "
                viewBox="0 0 1600 500"
                preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <linearGradient
                        id="waveGradientOne"
                        x1="0%"
                        y1="0%"
                        x2="100%"
                        y2="0%"
                    >
                        <stop
                            offset="0%"
                            stop-color="#1d4ed8"
                            stop-opacity="0.10"
                        />

                        <stop
                            offset="35%"
                            stop-color="#2563eb"
                            stop-opacity="0.32"
                        />

                        <stop
                            offset="70%"
                            stop-color="#38bdf8"
                            stop-opacity="0.24"
                        />

                        <stop
                            offset="100%"
                            stop-color="#60a5fa"
                            stop-opacity="0.08"
                        />
                    </linearGradient>
                </defs>

                <path
                    d="
                        M0,260
                        C220,180 360,180 560,240
                        C760,300 900,350 1110,265
                        C1300,190 1440,195 1600,245
                        L1600,500
                        L0,500
                        Z
                    "
                    fill="url(#waveGradientOne)"
                />
            </svg>


            {{-- Wave 2 --}}
            <svg
                class="
                    wave-two
                    absolute
                    -bottom-4
                    -left-[8%]
                    h-[42%]
                    w-[116%]
                    opacity-90
                "
                viewBox="0 0 1600 500"
                preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <linearGradient
                        id="waveGradientTwo"
                        x1="0%"
                        y1="0%"
                        x2="100%"
                        y2="0%"
                    >
                        <stop
                            offset="0%"
                            stop-color="#0f172a"
                            stop-opacity="0.20"
                        />

                        <stop
                            offset="30%"
                            stop-color="#1e3a8a"
                            stop-opacity="0.46"
                        />

                        <stop
                            offset="65%"
                            stop-color="#1d4ed8"
                            stop-opacity="0.28"
                        />

                        <stop
                            offset="100%"
                            stop-color="#2563eb"
                            stop-opacity="0.10"
                        />
                    </linearGradient>
                </defs>

                <path
                    d="
                        M0,300
                        C180,350 330,340 520,260
                        C710,180 880,165 1050,245
                        C1230,330 1400,345 1600,255
                        L1600,500
                        L0,500
                        Z
                    "
                    fill="url(#waveGradientTwo)"
                />
            </svg>


            {{-- Wave 3 --}}
            <svg
                class="
                    wave-three
                    absolute
                    bottom-0
                    -left-[5%]
                    h-[30%]
                    w-[110%]
                    opacity-95
                "
                viewBox="0 0 1600 500"
                preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <linearGradient
                        id="waveGradientThree"
                        x1="0%"
                        y1="0%"
                        x2="100%"
                        y2="0%"
                    >
                        <stop
                            offset="0%"
                            stop-color="#020617"
                            stop-opacity="0.74"
                        />

                        <stop
                            offset="45%"
                            stop-color="#0f2557"
                            stop-opacity="0.70"
                        />

                        <stop
                            offset="80%"
                            stop-color="#1e40af"
                            stop-opacity="0.42"
                        />

                        <stop
                            offset="100%"
                            stop-color="#1d4ed8"
                            stop-opacity="0.18"
                        />
                    </linearGradient>
                </defs>

                <path
                    d="
                        M0,330
                        C240,250 390,290 590,340
                        C790,390 960,365 1130,285
                        C1320,195 1440,220 1600,285
                        L1600,500
                        L0,500
                        Z
                    "
                    fill="url(#waveGradientThree)"
                />
            </svg>


            {{-- Soft radial highlight --}}
            <div
                class="
                    absolute
                    right-[4%]
                    top-[12%]
                    h-[420px]
                    w-[420px]
                    rounded-full
                    bg-blue-400/10
                    blur-[100px]

                    lg:h-[560px]
                    lg:w-[560px]
                "
            ></div>
        </div>


        {{-- Mobile Overlay --}}
        <div
            class="
                absolute inset-0
                bg-[#061a3b]/20
                lg:hidden
            "
        ></div>
    </div>


    {{-- =========================================================
        PAGE CONTENT
    ========================================================== --}}
    <main
        class="
            relative z-10

            flex
            min-h-[calc(100vh-4rem)]
            items-center

            px-4
            pb-8
            pt-24

            sm:px-6

            lg:grid
            lg:grid-cols-[400px_minmax(0,1fr)]
            lg:gap-12
            lg:px-12
            lg:pb-10
            lg:pt-24

            xl:grid-cols-[420px_minmax(0,1fr)]
            xl:gap-16
            xl:px-20

            2xl:px-28
        "
    >

        {{-- =====================================================
            LOGIN CARD
        ====================================================== --}}
        <section
            class="
                login-animate

                mx-auto

                w-full
                max-w-[390px]

                rounded-2xl

                border border-white/30

                bg-white/95

                p-5

                shadow-[0_28px_80px_rgba(0,0,0,0.38)]

                backdrop-blur-xl

                sm:p-6

                lg:mx-0
            "
        >

            {{-- Brand --}}
            <div class="flex items-center gap-3">
                <div
                    class="
                        flex h-11 w-11
                        shrink-0
                        items-center justify-center

                        overflow-hidden
                        rounded-xl

                        border border-slate-200
                        bg-slate-50

                        p-1.5
                    "
                >
                    <img
                        src="{{ asset('images/Logo.png') }}"
                        alt="Logo Pusat Pengetahuan"
                        class="h-full w-full object-contain"
                    >
                </div>

                <div class="min-w-0">
                    <p
                        class="
                            truncate
                            text-sm font-bold
                            text-slate-950
                        "
                    >
                        Pusat Pengetahuan
                    </p>

                    <p
                        class="
                            mt-0.5
                            truncate
                            text-xs
                            text-slate-500
                        "
                    >
                        LMS Pemkab Badung
                    </p>
                </div>
            </div>


            <div class="my-5 border-t border-slate-200"></div>


            {{-- Heading --}}
            <div>
                <p
                    class="
                        text-[10px] font-bold
                        uppercase tracking-[0.17em]
                        text-blue-700

                        sm:text-[11px]
                    "
                >
                    Login Administrator
                </p>

                <h1
                    class="
                        mt-2

                        text-xl font-bold
                        tracking-tight

                        text-slate-950

                        sm:text-2xl
                    "
                >
                    Selamat datang kembali
                </h1>

                <p
                    class="
                        mt-2

                        text-xs leading-5

                        text-slate-500

                        sm:text-sm
                        sm:leading-6
                    "
                >
                    Masuk menggunakan akun Anda untuk mengakses
                    dan mengelola Pusat Pengetahuan.
                </p>
            </div>


            {{-- =================================================
                ERROR
            ================================================== --}}
            <div
                id="errorAlert"
                class="
                    mt-5
                    hidden

                    items-start
                    gap-3

                    rounded-xl

                    border border-red-200

                    bg-red-50

                    px-4 py-3

                    text-xs
                    text-red-700

                    sm:text-sm
                "
            >
                <i
                    class="
                        bi bi-exclamation-circle-fill

                        mt-0.5

                        shrink-0

                        text-red-500
                    "
                ></i>

                <span id="errorMessage"></span>
            </div>


            {{-- =================================================
                LOGIN FORM
            ================================================== --}}
            <form
                id="loginForm"
                class="mt-5 space-y-4"
                novalidate
            >

                {{-- Email --}}
                <div>
                    <label
                        for="email"
                        class="
                            mb-2 block

                            text-xs font-semibold

                            text-slate-700

                            sm:text-sm
                        "
                    >
                        Alamat Email
                    </label>

                    <div class="relative">
                        <span
                            class="
                                pointer-events-none

                                absolute inset-y-0 left-0

                                flex items-center

                                pl-3.5

                                text-slate-400
                            "
                        >
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            placeholder="admin@example.com"

                            class="
                                block w-full

                                rounded-xl

                                border border-slate-300

                                bg-white

                                py-3

                                pl-10 pr-4

                                text-sm
                                text-slate-900

                                outline-none

                                transition

                                placeholder:text-slate-400

                                hover:border-slate-400

                                focus:border-blue-600
                                focus:ring-4
                                focus:ring-blue-600/10
                            "
                        >
                    </div>
                </div>


                {{-- Password --}}
                <div>
                    <label
                        for="password"
                        class="
                            mb-2 block

                            text-xs font-semibold

                            text-slate-700

                            sm:text-sm
                        "
                    >
                        Kata Sandi
                    </label>

                    <div class="relative">
                        <span
                            class="
                                pointer-events-none

                                absolute inset-y-0 left-0

                                flex items-center

                                pl-3.5

                                text-slate-400
                            "
                        >
                            <i class="bi bi-lock"></i>
                        </span>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            placeholder="Masukkan kata sandi"

                            class="
                                block w-full

                                rounded-xl

                                border border-slate-300

                                bg-white

                                py-3

                                pl-10 pr-11

                                text-sm
                                text-slate-900

                                outline-none

                                transition

                                placeholder:text-slate-400

                                hover:border-slate-400

                                focus:border-blue-600
                                focus:ring-4
                                focus:ring-blue-600/10
                            "
                        >

                        <button
                            type="button"
                            id="togglePassword"

                            class="
                                absolute inset-y-0 right-0

                                flex items-center

                                pr-3.5

                                text-slate-400

                                transition

                                hover:text-blue-600
                            "

                            aria-label="Tampilkan atau sembunyikan kata sandi"
                        >
                            <i
                                id="toggleIcon"
                                class="bi bi-eye"
                            ></i>
                        </button>
                    </div>
                </div>


                {{-- Remember --}}
                <div
                    class="
                        flex
                        flex-col

                        gap-2

                        pt-1

                        min-[380px]:flex-row
                        min-[380px]:items-center
                        min-[380px]:justify-between
                    "
                >
                    <label
                        for="rememberMe"

                        class="
                            flex cursor-pointer

                            items-center gap-2

                            text-xs

                            text-slate-600

                            select-none
                        "
                    >
                        <input
                            id="rememberMe"
                            type="checkbox"

                            class="
                                h-4 w-4

                                rounded

                                border-slate-300

                                text-blue-600

                                focus:ring-blue-500/30
                            "
                        >

                        Ingat email saya
                    </label>

                    <span
                        class="
                            inline-flex
                            items-center gap-1.5

                            text-[10px]
                            font-medium

                            text-slate-400
                        "
                    >
                        <i class="bi bi-shield-lock"></i>

                        Akses terbatas
                    </span>
                </div>


                {{-- Submit --}}
                <button
                    type="submit"
                    id="submitBtn"

                    class="
                        group

                        flex w-full

                        items-center
                        justify-center

                        gap-2

                        rounded-xl

                        bg-blue-700

                        px-4 py-3

                        text-sm font-semibold
                        text-white

                        shadow-lg
                        shadow-blue-700/20

                        transition

                        hover:bg-blue-600

                        hover:shadow-xl
                        hover:shadow-blue-700/25

                        active:scale-[0.99]

                        disabled:cursor-not-allowed
                        disabled:opacity-60
                    "
                >
                    <span id="btnText">
                        Masuk ke Sistem
                    </span>

                    <i
                        id="btnArrow"

                        class="
                            bi bi-arrow-right

                            transition-transform

                            group-hover:translate-x-1
                        "
                    ></i>

                    <i
                        id="btnSpinner"

                        class="
                            bi bi-arrow-repeat
                            do-spin
                            hidden
                        "
                    ></i>
                </button>
            </form>


            {{-- Security Info --}}
            <div
                class="
                    mt-5

                    flex items-start
                    gap-2.5

                    rounded-xl

                    border border-slate-100

                    bg-slate-50

                    px-3.5 py-3
                "
            >
                <div
                    class="
                        flex h-8 w-8
                        shrink-0
                        items-center justify-center

                        rounded-lg

                        bg-blue-100

                        text-blue-700
                    "
                >
                    <i class="bi bi-shield-check"></i>
                </div>

                <div>
                    <p
                        class="
                            text-[11px] font-semibold
                            text-slate-700
                        "
                    >
                        Area khusus pengguna terdaftar
                    </p>

                    <p
                        class="
                            mt-0.5

                            text-[10px] leading-4

                            text-slate-500

                            sm:text-[11px]
                            sm:leading-5
                        "
                    >
                        Gunakan akun yang telah diberikan hak akses
                        untuk menggunakan sistem.
                    </p>
                </div>
            </div>


            {{-- Footer --}}
            <p
                class="
                    mt-4

                    text-center

                    text-[9px] leading-4

                    text-slate-400

                    sm:text-[10px]
                    sm:leading-5
                "
            >
                &copy; {{ date('Y') }}
                Dinas Komunikasi dan Informatika

                <br>

                Pemerintah Kabupaten Badung
            </p>
        </section>


        {{-- =====================================================
            RIGHT INFORMATION
            Hidden under lg breakpoint
        ====================================================== --}}
        <section
            class="
                relative

                hidden

                min-h-[600px]

                items-center

                lg:flex
            "
        >
            <div
                class="
                    relative

                    w-full
                    max-w-4xl
                "
            >

                {{-- Badge --}}
                <span
                    class="
                        inline-flex

                        items-center
                        gap-2

                        rounded-full

                        border border-white/20

                        bg-[#071f45]/35

                        px-4 py-2

                        text-xs font-semibold

                        text-blue-100

                        shadow-lg

                        backdrop-blur-md
                    "
                >
                    <i class="bi bi-mortarboard-fill"></i>

                    LMS Pemerintah Kabupaten Badung
                </span>


                {{-- Heading --}}
                <h2
                    class="
                        mt-6

                        max-w-3xl

                        text-4xl font-bold

                        leading-tight
                        tracking-tight

                        text-white

                        drop-shadow-md

                        xl:text-5xl
                    "
                >
                    Belajar, berbagi,

                    <br>

                    dan berkembang bersama.
                </h2>


                {{-- Description --}}
                <p
                    class="
                        mt-5

                        max-w-2xl

                        text-sm leading-7

                        text-blue-50/90

                        drop-shadow-sm

                        xl:text-base
                        xl:leading-8
                    "
                >
                    LMS Pemkab Badung merupakan platform pembelajaran
                    dan pengelolaan pengetahuan yang membantu pegawai
                    mengakses materi, panduan, dokumentasi, serta
                    informasi pembelajaran secara terstruktur dalam
                    satu sistem.
                </p>


                {{-- Features --}}
                <div
                    class="
                        mt-7

                        grid
                        max-w-2xl

                        grid-cols-3

                        gap-3
                    "
                >
                    {{-- Feature 1 --}}
                    <article
                        class="
                            rounded-xl

                            border border-white/15

                            bg-[#071f45]/45

                            p-4

                            shadow-xl

                            backdrop-blur-md
                        "
                    >
                        <div
                            class="
                                flex h-9 w-9

                                items-center
                                justify-center

                                rounded-lg

                                bg-blue-500/20

                                text-blue-100
                            "
                        >
                            <i class="bi bi-diagram-3"></i>
                        </div>

                        <p
                            class="
                                mt-3

                                text-sm font-semibold

                                text-white
                            "
                        >
                            Terstruktur
                        </p>

                        <p
                            class="
                                mt-1

                                text-xs leading-5

                                text-blue-100/80
                            "
                        >
                            Materi dikelola berdasarkan aplikasi dan versi.
                        </p>
                    </article>


                    {{-- Feature 2 --}}
                    <article
                        class="
                            rounded-xl

                            border border-white/15

                            bg-[#071f45]/45

                            p-4

                            shadow-xl

                            backdrop-blur-md
                        "
                    >
                        <div
                            class="
                                flex h-9 w-9

                                items-center
                                justify-center

                                rounded-lg

                                bg-blue-500/20

                                text-blue-100
                            "
                        >
                            <i class="bi bi-collection-play"></i>
                        </div>

                        <p
                            class="
                                mt-3

                                text-sm font-semibold

                                text-white
                            "
                        >
                            Multi Konten
                        </p>

                        <p
                            class="
                                mt-1

                                text-xs leading-5

                                text-blue-100/80
                            "
                        >
                            Mendukung teks, gambar, video, dan dokumen PDF.
                        </p>
                    </article>


                    {{-- Feature 3 --}}
                    <article
                        class="
                            rounded-xl

                            border border-white/15

                            bg-[#071f45]/45

                            p-4

                            shadow-xl

                            backdrop-blur-md
                        "
                    >
                        <div
                            class="
                                flex h-9 w-9

                                items-center
                                justify-center

                                rounded-lg

                                bg-blue-500/20

                                text-blue-100
                            "
                        >
                            <i class="bi bi-people"></i>
                        </div>

                        <p
                            class="
                                mt-3

                                text-sm font-semibold

                                text-white
                            "
                        >
                            Mudah Diakses
                        </p>

                        <p
                            class="
                                mt-1

                                text-xs leading-5

                                text-blue-100/80
                            "
                        >
                            Pengetahuan tersedia sebagai referensi bersama.
                        </p>
                    </article>
                </div>


                {{-- =================================================
                    ASN ASSETS
                ================================================== --}}
                <div
                    class="
                        pointer-events-none

                        relative

                        mt-2

                        h-[300px]

                        xl:h-[330px]
                    "
                >
                    <img
                        src="{{ asset('images/asn_male.png') }}"
                        alt="Ilustrasi ASN laki-laki"

                        class="
                            absolute

                            bottom-0
                            left-4

                            h-[270px]

                            object-contain

                            drop-shadow-2xl

                            xl:left-8
                            xl:h-[310px]
                        "
                    >

                    <img
                        src="{{ asset('images/asn_female.png') }}"
                        alt="Ilustrasi ASN perempuan"

                        class="
                            absolute

                            bottom-0
                            left-[205px]

                            h-[270px]

                            object-contain

                            drop-shadow-2xl

                            xl:left-[275px]
                            xl:h-[310px]
                        "
                    >
                </div>
            </div>
        </section>
    </main>


    {{-- =========================================================
        LOGIN JAVASCRIPT
    ========================================================== --}}
    <script>
        document
            .getElementById('loginForm')
            .addEventListener(
                'submit',
                async function (e) {
                    e.preventDefault();

                    const email =
                        document
                            .getElementById('email')
                            .value;

                    const password =
                        document
                            .getElementById('password')
                            .value;

                    const submitBtn =
                        document
                            .getElementById('submitBtn');

                    const btnText =
                        document
                            .getElementById('btnText');

                    const btnSpinner =
                        document
                            .getElementById('btnSpinner');

                    const btnArrow =
                        document
                            .getElementById('btnArrow');

                    const errorAlert =
                        document
                            .getElementById('errorAlert');

                    const errorMessage =
                        document
                            .getElementById('errorMessage');


                    submitBtn.disabled = true;

                    btnText.innerText =
                        'Memproses...';

                    btnArrow.classList.add(
                        'hidden'
                    );

                    btnSpinner.classList.remove(
                        'hidden'
                    );

                    errorAlert.classList.add(
                        'hidden'
                    );


                    try {
                        const response =
                            await fetch(
                                '/api/auth/login',
                                {
                                    method: 'POST',

                                    headers: {
                                        'Content-Type':
                                            'application/json',

                                        'Accept':
                                            'application/json',
                                    },

                                    body:
                                        JSON.stringify({
                                            email,
                                            password,
                                        }),
                                }
                            );


                        const data =
                            await response.json();


                        if (response.ok) {
                            localStorage.setItem(
                                'auth_token',
                                data.data.token
                            );


                            if (data.data.user) {
                                localStorage.setItem(
                                    'user',
                                    JSON.stringify(
                                        data.data.user
                                    )
                                );
                            }


                            if (
                                document
                                    .getElementById(
                                        'rememberMe'
                                    )
                                    .checked
                            ) {
                                localStorage.setItem(
                                    'remembered_email',
                                    email
                                );
                            } else {
                                localStorage.removeItem(
                                    'remembered_email'
                                );
                            }


                            btnText.innerText =
                                'Berhasil ✓';

                            btnSpinner.classList.add(
                                'hidden'
                            );

                            btnArrow.classList.add(
                                'hidden'
                            );

                            submitBtn.classList.remove(
                                'bg-blue-700',
                                'hover:bg-blue-600'
                            );

                            submitBtn.classList.add(
                                'bg-emerald-600',
                                'hover:bg-emerald-500'
                            );


                            setTimeout(() => {
                                const roles =
                                    data.data.user
                                        ?.roles || [];

                                if (
                                    roles.includes(
                                        'Pegawai'
                                    )
                                ) {
                                    window.location.href =
                                        '/';
                                } else {
                                    window.location.href =
                                        '/admin/materi';
                                }
                            }, 500);

                            return;
                        }


                        throw new Error(
                            data.message ||
                            data.errors?.email?.[0] ||
                            'Gagal login, periksa email dan kata sandi Anda.'
                        );
                    } catch (error) {
                        submitBtn.disabled = false;

                        btnText.innerText =
                            'Masuk ke Sistem';

                        btnArrow.classList.remove(
                            'hidden'
                        );

                        btnSpinner.classList.add(
                            'hidden'
                        );

                        errorMessage.innerText =
                            error.message;

                        errorAlert.classList.remove(
                            'hidden'
                        );
                    }
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Password Visibility
        |--------------------------------------------------------------------------
        */

        document
            .getElementById(
                'togglePassword'
            )
            .addEventListener(
                'click',
                function () {
                    const input =
                        document
                            .getElementById(
                                'password'
                            );

                    const icon =
                        document
                            .getElementById(
                                'toggleIcon'
                            );

                    const showPassword =
                        input.type ===
                        'password';


                    input.type =
                        showPassword
                            ? 'text'
                            : 'password';


                    icon.className =
                        showPassword
                            ? 'bi bi-eye-slash'
                            : 'bi bi-eye';
                }
            );


        /*
        |--------------------------------------------------------------------------
        | Remember Email
        |--------------------------------------------------------------------------
        */

        const savedEmail =
            localStorage.getItem(
                'remembered_email'
            );


        if (savedEmail) {
            document
                .getElementById('email')
                .value =
                    savedEmail;

            document
                .getElementById(
                    'rememberMe'
                )
                .checked =
                    true;
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Login
        |--------------------------------------------------------------------------
        */

        if (
            localStorage.getItem(
                'auth_token'
            )
        ) {
            const userStr =
                localStorage.getItem(
                    'user'
                );


            if (userStr) {
                try {
                    const user =
                        JSON.parse(
                            userStr
                        );

                    const roles =
                        user.roles ||
                        ['Admin'];


                    if (
                        roles.includes(
                            'Pegawai'
                        )
                    ) {
                        window.location.href =
                            '/';
                    } else {
                        window.location.href =
                            '/admin/materi';
                    }
                } catch (error) {
                    window.location.href =
                        '/admin/materi';
                }
            } else {
                window.location.href =
                    '/admin/materi';
            }
        }
    </script>
</body>
</html>