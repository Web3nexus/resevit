<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold">Secure Payment</h2>
                    <div class="flex gap-2">
                        <!-- Card icons could go here -->
                        <span class="text-gray-400 text-sm">Encrypted by Stripe</span>
                    </div>
                </div>
                <div class="mt-2 text-sm text-gray-600">
                    Order #{{ $order->order_number }} • {{ money($order->total) }}
                </div>
            </div>

            <div class="p-6">
                <form id="payment-form">
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Card Information</label>
                        <div id="payment-element" class="p-3 border border-gray-200 rounded-lg">
                            <!-- Stripe Elements will be inserted here -->
                        </div>
                    </div>

                    <button id="submit"
                        class="w-full bg-[#635BFF] text-white font-semibold py-3 px-4 rounded-lg hover:bg-[#5451D6] transition-colors flex items-center justify-center gap-2">
                        <div
                            class="spinner hidden w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin">
                        </div>
                        <span id="button-text">Pay {{ money($order->total) }}</span>
                    </button>

                    <div id="error-message" class="hidden mt-4 text-sm text-red-600"></div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
        const options = {
            clientSecret: '{{ $clientSecret }}',
            appearance: {
                theme: 'stripe',
            },
        };

        const elements = stripe.elements(options);
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        const form = document.getElementById('payment-form');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            setLoading(true);

            const { error } = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: '{{ route("tenant.order.confirmation", $order) }}',
                },
            });

            if (error) {
                const messageContainer = document.querySelector('#error-message');
                messageContainer.textContent = error.message;
                messageContainer.classList.remove('hidden');
                setLoading(false);
            } else {
                // Your customer will be redirected to your `return_url`.
            }
        });

        function setLoading(isLoading) {
            if (isLoading) {
                document.querySelector("#submit").disabled = true;
                document.querySelector("#button-text").classList.add("hidden");
                document.querySelector(".spinner").classList.remove("hidden");
            } else {
                document.querySelector("#submit").disabled = false;
                document.querySelector("#button-text").classList.remove("hidden");
                document.querySelector(".spinner").classList.add("hidden");
            }
        }
    </script>
</div>