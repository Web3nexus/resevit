<div class="space-y-4">
    <div class="text-center">
        <p class="text-sm text-gray-600 mb-4">
            Scan the following QR code with your authenticator application (e.g. Google Authenticator).
        </p>

        <div class="inline-block p-4 bg-white border border-gray-200 rounded-lg">
            {!! (new \PragmaRX\Google2FAQRCode\Google2FA())->getQRCodeInline(
    config('app.name'),
    auth()->user()->email,
    $secret
) !!}
        </div>

        <p class="mt-4 text-sm text-gray-500">
            Or enter this secret key manually: <br>
            <code
                class="font-mono font-bold bg-gray-100 px-2 py-1 rounded text-gray-900 select-all">{{ $secret }}</code>
        </p>
    </div>
</div>