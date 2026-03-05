<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <x-filament::button
            type="submit"
            style="margin-top: 2rem;"
        >
            {{ __('botly::botly.form.submit') }}
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
