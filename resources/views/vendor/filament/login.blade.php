<x-filament::page class="flex flex-col items-center justify-center min-h-screen p-6">
    <x-filament::card class="w-full max-w-md">
        <x-filament::form wire:submit.prevent="authenticate">
            <x-filament::input
                type="email"
                name="email"
                placeholder="Email"
                wire:model.defer="email"
                required
                autofocus
                autocomplete="username"
            />
            <x-filament::input
                type="password"
                name="password"
                placeholder="Password"
                wire:model.defer="password"
                required
                autocomplete="current-password"
                class="mt-4"
            />
            <x-filament::button type="submit" class="w-full mt-6">
                Login
            </x-filament::button>
        </x-filament::form>

        <div class="mt-4 text-center">
            <!-- Removed register link as register page is deleted -->
            <!-- <a href="{{ route('filament.dashboard.pages.register') }}" class="text-primary-600 hover:underline">
                Register
            </a> -->
        </div>
    </x-filament::card>
</x-filament::page>
