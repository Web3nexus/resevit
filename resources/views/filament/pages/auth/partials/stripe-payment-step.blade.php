<div class="space-y-4" x-data="{
        stripe: null,
        elements: null,
        cardElement: null,
        paymentMethodId: @entangle($getStatePath('payment_method_id')),
        error: null,
        loading: false,

        init() {
            this.stripe = Stripe('{{ $stripeKey }}');
            this.elements = this.stripe.elements();

            this.cardElement = this.elements.create('card', {
                style: {
                    base: {
                        color: '#ffffff',
                        fontFamily: 'Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#dc2626',
                        iconColor: '#dc2626'
                    }
                }
            });

            this.cardElement.mount('#card-element');

            this.cardElement.on('change', (event) => {
                this.error = event.error ? event.error.message : null;
            });
        },

        async createPaymentMethod() {
            if (this.paymentMethodId) return; // Already have one

            this.loading = true;
            this.error = null;

            const { paymentMethod, error } = await this.stripe.createPaymentMethod({
                type: 'card',
                card: this.cardElement,
            });

            if (error) {
                this.error = error.message;
                this.loading = false;
            } else {
                this.paymentMethodId = paymentMethod.id;
                this.loading = false;
            }
        }
    }" x-init="init" wire:ignore>
    <div class="rounded-lg border border-gray-700 bg-gray-800 p-4 shadow-sm">
        <label for="card-element" class="block text-sm font-medium text-gray-300 mb-2">
            Card Details
        </label>
        <div id="card-element"
            class="p-3 bg-gray-900 rounded border border-gray-600 focus-within:ring-2 focus-within:ring-custom-500">
            <!-- Stripe Element will be inserted here -->
        </div>

        <div x-show="error" x-text="error" class="mt-2 text-sm text-red-500" x-cloak></div>
    </div>

    <div class="flex items-center gap-3">
        <button type="button" x-on:click="createPaymentMethod" x-bind:disabled="loading || paymentMethodId"
            class="fi-btn fi-btn-size-sm rounded-lg bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20 transition disabled:opacity-50">
            <span x-show="!loading && !paymentMethodId">Validate Card</span>
            <span x-show="loading" x-cloak>Validating...</span>
            <span x-show="paymentMethodId" x-cloak class="flex items-center gap-1 text-green-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Card Saved
            </span>
        </button>

        <p x-show="!paymentMethodId" class="text-xs text-gray-400">Click to secure your trial. No charges today.</p>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>