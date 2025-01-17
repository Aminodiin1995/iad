<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('/favicon.ico') }}">
    <link rel="mask-icon" href="{{ asset('/favicon.ico') }}" color="#ff2d20">
    <link rel="stylesheet" href="https://unpkg.com/easymde/dist/easymde.min.css">
    <script src="https://unpkg.com/easymde/dist/easymde.min.js"></script>

    {{--  Currency  --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/robsontenorio/mary@0.44.2/libs/currency/currency.js"></script>

    {{-- ChartJS --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    {{-- Flatpickr  --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Cropper.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />

    {{-- Sortable.js --}}
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.1/Sortable.min.js"></script>

    {{-- PhotoSwipe --}}
    <script src="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/umd/photoswipe.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/umd/photoswipe-lightbox.umd.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/photoswipe@5.4.3/dist/photoswipe.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@2.9.6/build/vanilla-calendar.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/vanilla-calendar-pro@2.9.6/build/vanilla-calendar.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .print-hide {
                display: none;
            }
        }
    </style>
</head>
<body class="min-h-screen font-sans antialiased bg-base-200/50 dark:bg-base-200">
<x-nav sticky class="lg:hidden">
    <x-slot:brand>
        <x-flow-brand />
    </x-slot:brand>
    <x-slot:actions>
        <label for="main-drawer" class="mr-3 lg:hidden">
            <x-icon name="o-bars-2" class="cursor-pointer" />
        </label>
    </x-slot:actions>
</x-nav>

<x-main>
    <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">
        <x-flow-brand class="p-5 pt-3" />

        <x-menu activate-by-route>

            {{-- User --}}
            @if($user = auth()->user())
                <x-menu-separator />

                <x-list-item :item="$user" value="first_name" sub-value="username" no-separator no-hover class="-mx-2 !-my-2 rounded">
                    <x-slot:actions>
                        <x-dropdown>
                            <x-slot:trigger>
                                <x-button icon="o-cog-6-tooth" class="btn-circle btn-ghost btn-xs" />
                            </x-slot:trigger>
                            <x-menu-item icon="o-power" label="Logout" link="/logout" no-wire-navigate />
                            <x-menu-item icon="o-lock-closed" label="password" link="/change-password" no-wire-navigate />
                            <x-menu-item icon="o-swatch" label="Toggle theme" @click.stop="$dispatch('mary-toggle-theme')" />
                        </x-dropdown>
                    </x-slot:actions>
                </x-list-item>
            @endif

            <x-menu-separator />

            <x-menu-item title="Home" icon="o-chart-pie" link="/home" />
            <x-menu-item title="Etudiant" icon="c-user-group" link="/students" />
            <x-menu-item title="Reporting" icon="c-presentation-chart-line" link="/reports" />
            <x-menu-item title="Factures" icon="c-archive-box-arrow-down" link="/invoices" />
            <x-menu-item title="Recu" icon="c-banknotes" link="/payments" />
            <x-menu-separator />

            <x-menu-sub title="Warehouse" icon="o-wrench-screwdriver">
                <x-menu-item title="users" icon="o-user" link="/users" />
                <x-menu-item title="Filiers" icon="s-cube-transparent" link="/filieres" />
                <x-menu-item title="Niveaux" icon="s-table-cells" link="/niveaux" />
                <x-menu-item title="Section" icon="o-home-modern" link="/sections" />
            </x-menu-sub>

            <x-menu-item title="Search" @click.stop="$dispatch('mary-search-open')" icon="o-magnifying-glass" badge="Cmd + G" />
        </x-menu>
    </x-slot:sidebar>

    {{-- The `$slot` goes here --}}
    <x-slot:content>
        {{ $slot }}

        <div class="flex mt-5">
            {{--            <x-button label="Source code" icon="o-code-bracket" link="/support-us" class="btn-ghost" />--}}
           {{--  <x-button label="Built with maryUI" icon="o-heart" link="https://mary-ui.com" class="btn-ghost !text-pink-500" external /> --}}
        </div>
    </x-slot:content>
</x-main>

{{-- Toast --}}
<x-toast />

{{-- Spotlight --}}
<x-spotlight search-text="tasks, project ..." />

{{-- Theme Toggle--}}
<x-theme-toggle class="hidden" />
</body>

</html>
