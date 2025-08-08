<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Administrateur')</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    @livewireStyles
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .transition-transform {
            transition: transform 0.3s ease-in-out;
        }

        .transition-opacity {
            transition: opacity 0.3s ease-in-out;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">
    <div id="mobileBackdrop"
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden opacity-0 pointer-events-none transition-opacity">
    </div>

    @include('backOffice.components.sidebar')

    <div class="lg:pl-64 transition-all duration-300">
        @include('backOffice.components.navbar')
        <main class="p-5">
            @yield('content')
        </main>
    </div>
    <script>
        class AdminLayout {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.mobileBackdrop = document.getElementById('mobileBackdrop');
                this.burgerButton = document.getElementById('burgerButton');
                this.closeButton = document.getElementById('closeButton');
                this.profileButton = document.getElementById('profileButton');
                this.profileDropdown = document.getElementById('profileDropdown');
                this.profileChevron = this.profileButton?.querySelector('.fa-chevron-up');
                this.notificationsButton = document.getElementById('notificationsButton');
                this.notificationsDropdown = document.getElementById('notificationsDropdown');

                this.init();
            }
            init() {
                this.setupEventListeners();
                this.updateLayout();
                setTimeout(() => {
                    this.updateLayout();
                }, 50);
            }
            setupEventListeners() {
                this.burgerButton?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleSidebar();
                });
                this.closeButton?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.closeSidebar();
                });
                this.mobileBackdrop?.addEventListener('click', () => {
                    this.closeSidebar();
                });
                this.profileButton?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleProfileDropdown();
                });
                this.notificationsButton?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.toggleNotificationsDropdown();
                });
                document.addEventListener('click', () => {
                    this.closeAllDropdowns();
                });
                this.profileDropdown?.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
                this.notificationsDropdown?.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
                window.addEventListener('resize', () => {
                    this.updateLayout();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.closeSidebar();
                        this.closeAllDropdowns();
                    }
                });
            }
            updateLayout() {
                if (!this.sidebar) return;
                if (window.innerWidth >= 1024) {
                    this.sidebar.classList.remove('-translate-x-full');
                    this.sidebar.classList.add('translate-x-0');
                    this.hideMobileBackdrop();
                } else {
                    if (!this.sidebar.classList.contains('translate-x-0')) {
                        this.sidebar.classList.add('-translate-x-full');
                        this.sidebar.classList.remove('translate-x-0');
                    }
                }
            }
            toggleSidebar() {
                if (window.innerWidth < 1024) {
                    const isHidden = this.sidebar.classList.contains('-translate-x-full');

                    if (isHidden) {
                        this.openSidebar();
                    } else {
                        this.closeSidebar();
                    }
                }
            }
            openSidebar() {
                this.sidebar?.classList.remove('-translate-x-full');
                this.sidebar?.classList.add('translate-x-0');
                this.showMobileBackdrop();
            }
            closeSidebar() {
                if (window.innerWidth < 1024) {
                    this.sidebar?.classList.add('-translate-x-full');
                    this.sidebar?.classList.remove('translate-x-0');
                    this.hideMobileBackdrop();
                }
            }
            showMobileBackdrop() {
                if (this.mobileBackdrop) {
                    this.mobileBackdrop.classList.remove('opacity-0', 'pointer-events-none');
                    this.mobileBackdrop.classList.add('opacity-100');
                }
            }
            hideMobileBackdrop() {
                if (this.mobileBackdrop) {
                    this.mobileBackdrop.classList.add('opacity-0', 'pointer-events-none');
                    this.mobileBackdrop.classList.remove('opacity-100');
                }
            }
            toggleProfileDropdown() {
                const isHidden = this.profileDropdown?.classList.contains('hidden');
                this.closeAllDropdowns();
                if (isHidden) {
                    this.profileDropdown?.classList.remove('hidden');
                    this.profileChevron?.classList.add('rotate-180');
                } else {
                    this.profileDropdown?.classList.add('hidden');
                    this.profileChevron?.classList.remove('rotate-180');
                }
            }
            toggleNotificationsDropdown() {
                const isHidden = this.notificationsDropdown?.classList.contains('hidden');
                this.closeAllDropdowns();
                if (isHidden) {
                    this.notificationsDropdown?.classList.remove('hidden');
                }
            }
            closeAllDropdowns() {
                this.profileDropdown?.classList.add('hidden');
                this.profileChevron?.classList.remove('rotate-180');
                this.notificationsDropdown?.classList.add('hidden');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            new AdminLayout();
        });
    </script>

    @livewireScripts
</body>

</html>
