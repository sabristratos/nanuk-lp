<x-layouts.base :title="$title ?? null">
    <div class="bg-zinc-50 dark:bg-zinc-950 min-h-screen">
        <flux:sidebar sticky stashable class="bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700 flex flex-col h-full">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            @if(\App\Facades\Settings::get('show_logo_in_header', true))
                <flux:brand href="#" name="{{ \App\Facades\Settings::get('site_name', config('app.name', 'Laravel')) }}" class="px-2" />
            @endif

            <flux:navlist variant="outline">
                @can('view-dashboard')
                <flux:navlist.item icon="home" href="{{ route('admin.dashboard') }}" :current="request()->routeIs('admin.dashboard')">{{ __('Dashboard') }}</flux:navlist.item>
                @endcan
                <flux:navlist.group heading="{{ __('Manage') }}" class="mt-4">
                    <flux:navlist.group icon="user" expandable heading="{{ __('Content') }}" class="grid">
                        @can('view-attachments')
                        <flux:navlist.item href="{{ route('admin.attachments') }}" :current="request()->routeIs('admin.attachments')">{{ __('Attachments') }}</flux:navlist.item>
                        @endcan
                        @can('view-taxonomies')
                        <flux:navlist.item href="{{ route('admin.taxonomies.index') }}" :current="request()->routeIs('admin.taxonomies.*')">{{ __('Taxonomies') }}</flux:navlist.item>
                        @endcan
                        @can('view-legal-pages')
                        <flux:navlist.item href="{{ route('admin.legal.index') }}" :current="request()->routeIs('admin.legal.*')">{{ __('Legal') }}</flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                    @can('view-experiments')
                    <flux:navlist.group icon="beaker" expandable heading="{{ __('A/B Testing') }}" class="grid">
                        <flux:navlist.item href="{{ route('admin.experiments.index') }}" :current="request()->routeIs('admin.experiments.*')">{{ __('Experiments') }}</flux:navlist.item>
                        <flux:navlist.item href="{{ route('admin.submissions.index') }}" :current="request()->routeIs('admin.submissions.index')">{{ __('Submissions') }}</flux:navlist.item>
                    </flux:navlist.group>
                    @endcan
                    <flux:navlist.group expandable heading="{{ __('User Management') }}" class="grid">
                        @can('view-users')
                        <flux:navlist.item href="{{ route('admin.users.index') }}" :current="request()->routeIs('admin.users.*')">{{ __('All Users') }}</flux:navlist.item>
                        @endcan
                        @can('view-roles')
                        <flux:navlist.item href="{{ route('admin.roles.index') }}" :current="request()->routeIs('admin.roles.*')">{{ __('Roles & Permissions') }}</flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                    <flux:navlist.group expandable heading="{{ __('System') }}" class="grid">
                        @can('view-activity-logs')
                        <flux:navlist.item href="{{ route('admin.activity-logs') }}" :current="request()->routeIs('admin.activity-logs')">{{ __('Activity Logs') }}</flux:navlist.item>
                        @endcan
                        @can('view-notifications')
                        <flux:navlist.item href="{{ route('admin.notifications') }}" :current="request()->routeIs('admin.notifications')">{{ __('Notifications') }}</flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            {{-- New container for icon buttons --}}
            <div class="mt-4 mb-2 px-2 flex justify-between items-center space-x-2 max-lg:hidden">
                {{-- Notifications Icon Button --}}
                <div x-data="{ unreadCount: @js(auth()->user()?->unreadNotifications()->count() ?? 0) }" x-on:unread-notifications-count-updated.window="unreadCount = $event.detail.count" class="relative inline-block">
                    <flux:button
                        variant="filled"
                        icon="bell"
                        x-on:click.prevent="$flux.modal('notification-flyout').show()"
                        aria-label="{{ __('View notifications') }}"
                        :tooltip="__('Notifications')"
                        tooltip:position="top"
                        square
                    >
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-1 -right-1 h-4 w-4 min-w-[1rem] px-1 flex items-center justify-center text-xs font-semibold text-white bg-red-500 rounded-full" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                        </template>
                    </flux:button>
                </div>

                {{-- Language Switcher Dropdown --}}
                <flux:dropdown position="top" align="center">
                    <flux:button
                        variant="filled"
                        icon="language"
                        aria-label="{{ __('Change language') }}"
                        :tooltip="__('Language')"
                        tooltip:position="top"
                        square
                    />
                    <flux:menu>
                        @foreach($availableLocalesForSwitcher as $localeCode => $localeName)
                            <flux:menu.item href="{{ route('language.switch', ['locale' => $localeCode]) }}">
                                {{ $localeName }}
                                @if(app()->getLocale() === $localeCode)
                                    <x-slot:icon>
                                        <flux:icon icon="check" variant="mini" class="text-green-500" />
                                    </x-slot:icon>
                                @endif
                            </flux:menu.item>
                        @endforeach
                    </flux:menu>
                </flux:dropdown>

                {{-- Settings Icon Button --}}
                @can('view-settings')
                <flux:button
                    variant="filled"
                    href="{{ route('admin.settings') }}"
                    icon="cog-6-tooth"
                    aria-label="{{ __('Settings') }}"
                    :tooltip="__('Settings')"
                    tooltip:position="top"
                    square
                />
                @endcan

                {{-- Help Icon Button --}}
                <flux:button
                    variant="filled"
                    href="#"
                    icon="information-circle"
                    aria-label="{{ __('Help') }}"
                    :tooltip="__('Help')"
                    tooltip:position="top"
                    square
                />
            </div>

            <flux:dropdown position="top" align="left" class="max-lg:hidden">
                <flux:profile name="{{ Auth::user()->name ?? __('Admin User') }}" />

                <flux:menu>
                    <flux:menu.item href="{{ route('admin.profile') }}" icon="user">{{ __('Profile') }}</flux:menu.item>
                    @can('view-settings')
                    <flux:menu.item href="{{ route('admin.settings') }}" icon="cog-6-tooth">{{ __('Settings') }}</flux:menu.item>
                    @endcan
                    @if(App\Facades\Settings::get('enable_dark_mode', true))
                    <flux:menu.item
                        x-data
                        x-on:click="$flux.dark = !$flux.dark">
                        {{ __('Dark Mode') }}
                        <x-slot:icon>
                            <template x-if="$flux.dark">
                                {{-- Ensure 'variant' matches what flux:icon expects and corresponds to flux:menu.item's styling --}}
                                <flux:icon class="text-zinc-400 mr-2" icon="sun" variant="mini" />
                            </template>
                            <template x-if="!$flux.dark">
                                <flux:icon class="text-zinc-400 mr-2" icon="moon" variant="mini" />
                            </template>
                        </x-slot:icon>
                    </flux:menu.item>
                    @endif
                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">{{ __('Logout') }}</flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            {{-- Bell Icon for SM screens --}}
            <div x-data="{ unreadCount: @js(auth()->user()?->unreadNotifications()->count() ?? 0) }" x-on:unread-notifications-count-updated.window="unreadCount = $event.detail.count" class="relative">
                <flux:button
                    variant="ghost"
                    icon="bell"
                    x-on:click.prevent="$flux.modal('notification-flyout').show()"
                    aria-label="{{ __('View notifications') }}"
                    :tooltip="__('Notifications')"
                    tooltip:position="bottom"
                >
                    <template x-if="unreadCount > 0">
                        <span class="absolute -top-1 -right-1 h-4 w-4 min-w-[1rem] px-1 flex items-center justify-center text-xs font-semibold text-white bg-red-500 rounded-full" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                    </template>
                </flux:button>
            </div>

            <flux:dropdown align="right">
                <flux:profile class="-mr-4" />

                <flux:menu>
                    <flux:menu.item href="{{ route('admin.profile') }}" icon="user">{{ __('Profile') }}</flux:menu.item>
                    @can('view-settings')
                    <flux:menu.item href="#" icon="cog-6-tooth">{{ __('Settings') }}</flux:menu.item>
                    @endcan

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">{{ __('Logout') }}</flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        <flux:main container class="max-w-7xl">
            {{ $slot }}
        </flux:main>
    </div>

    @livewire('admin.notifications.notification-flyout')
</x-layouts.base> 