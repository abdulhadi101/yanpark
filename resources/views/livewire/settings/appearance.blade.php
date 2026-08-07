<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Appearance settings') }}</h2>

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <div class="py-6">
            <p class="text-sm text-zinc-500">{{ __('This application uses light mode only.') }}</p>
        </div>
    </x-settings.layout>
</section>
