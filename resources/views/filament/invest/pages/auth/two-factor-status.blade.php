@if ($enabled)
    <div class="rounded-lg bg-success-50 px-4 py-3 border border-success-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <x-heroicon-m-check-circle class="h-5 w-5 text-success-400" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-success-800">Two-factor authentication is enabled</h3>
                <div class="mt-2 text-sm text-success-700">
                    <p>You have enabled two-factor authentication, adding an extra layer of security to your account.</p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="rounded-lg bg-gray-50 px-4 py-3 border border-gray-200">
        <div class="flex">
            <div class="flex-shrink-0">
                <x-heroicon-m-exclamation-triangle class="h-5 w-5 text-gray-400" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-800">Two-factor authentication is not enabled</h3>
                <div class="mt-2 text-sm text-gray-700">
                    <p>We recommend enabling two-factor authentication to secure your account.</p>
                </div>
            </div>
        </div>
    </div>
@endif