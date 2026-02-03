<div class="space-y-4">
    <p class="text-sm text-gray-600">
        Store these recovery codes in a secure password manager. They can be used to recover access to your account if
        your two-factor authentication device is lost.
    </p>

    <div class="bg-gray-100 rounded-lg p-4 font-mono text-sm">
        <ul class="grid grid-cols-2 gap-2">
            @foreach ($codes as $code)
                <li class="select-all">{{ $code }}</li>
            @endforeach
        </ul>
    </div>
</div>