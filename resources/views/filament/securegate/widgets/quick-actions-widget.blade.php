<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-filament::button href="/securegate/admins/create" tag="a" icon="heroicon-m-user-plus" color="primary"
                class="w-full justify-center">
                Add New Admin
            </x-filament::button>

            <x-filament::button href="/securegate/marketing-materials/create" tag="a" icon="heroicon-m-megaphone"
                color="success" class="w-full justify-center">
                Add Marketing Material
            </x-filament::button>

            <x-filament::button href="/securegate/investors/create" tag="a" icon="heroicon-m-banknotes" color="warning"
                class="w-full justify-center">
                Add Investor
            </x-filament::button>

            <x-filament::button href="/securegate/ai-image-generator" tag="a" icon="heroicon-m-photo" color="info"
                class="w-full justify-center">
                Generate AI Image
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>