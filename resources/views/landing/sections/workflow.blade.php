<section class="py-24 bg-brand-offwhite" id="how-it-works">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <h2 class="text-4xl font-extrabold text-brand-primary mb-6 tracking-tight">
                {{ $section->title ?? 'Seamless Integration in 3 Simple Steps' }}
            </h2>
            <p class="text-lg text-slate-600">
                {{ $section->subtitle ?? 'We’ve made it incredibly easy to switch. Here is how your journey begins.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-16 relative">
            <!-- Connector Line (Desktop) -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-slate-100 -translate-y-12"></div>

            @foreach($section->items as $index => $item)
                <div class="relative flex flex-col items-center text-center">
                    <div
                        class="w-20 h-20 bg-white border-4 border-brand-accent rounded-full flex items-center justify-center text-2xl font-black text-brand-primary mb-8 z-10 shadow-lg shadow-brand-accent/20">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="text-2xl font-bold text-brand-primary mb-4">{{ $item->title }}</h3>
                    <p class="text-slate-600 leading-relaxed">{{ $item->description }}</p>
                </div>
            @endforeach

            @if($section->items->isEmpty())
                <!-- Default Workflow -->
                <div class="relative flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-white border-2 border-brand-accent rounded-full flex items-center justify-center text-xl font-bold text-brand-primary mb-6 z-10">
                        1</div>
                    <h3 class="text-xl font-bold mb-4">Create Account</h3>
                    <p class="text-slate-600">Sign up and configure your restaurant's basic details and floor plan.</p>
                </div>
                <div class="relative flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-white border-2 border-brand-accent rounded-full flex items-center justify-center text-xl font-bold text-brand-primary mb-6 z-10">
                        2</div>
                    <h3 class="text-xl font-bold mb-4">Integrate Tools</h3>
                    <p class="text-slate-600">Connect your POS, social media, and payment gateways seamlessly.</p>
                </div>
                <div class="relative flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-white border-2 border-brand-accent rounded-full flex items-center justify-center text-xl font-bold text-brand-primary mb-6 z-10">
                        3</div>
                    <h3 class="text-xl font-bold mb-4">Go Live</h3>
                    <p class="text-slate-600">Start accepting reservations and delighting your guests instantly.</p>
                </div>
            @endif
        </div>
    </div>
</section>