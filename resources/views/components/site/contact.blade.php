<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-900 rounded-[3rem] overflow-hidden shadow-2xl"
            style="{{ !empty($data['background_color']) ? 'background-color: ' . $data['background_color'] : '' }}">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                <div class="p-12 lg:p-20 text-white flex flex-col justify-center">
                    <h2 class="text-4xl font-extrabold mb-12 uppercase tracking-tighter italic">Get in Touch</h2>

                    <div class="space-y-10">
                        @if($data['address'])
                            <div class="flex items-start space-x-6">
                                <div class="bg-white/10 p-4 rounded-2xl"><i
                                        class="fas fa-location-dot text-xl text-brand-accent"></i></div>
                                <div>
                                    <h5 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Our
                                        Location</h5>
                                    <p class="text-xl font-medium">{{ $data['address'] }}</p>
                                </div>
                            </div>
                        @endif

                        @if($data['phone'])
                            <div class="flex items-start space-x-6">
                                <div class="bg-white/10 p-4 rounded-2xl"><i
                                        class="fas fa-phone text-xl text-brand-accent"></i></div>
                                <div>
                                    <h5 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Phone
                                        Number</h5>
                                    <p class="text-xl font-medium">{{ $data['phone'] }}</p>
                                </div>
                            </div>
                        @endif

                        @if($data['email'])
                            <div class="flex items-start space-x-6">
                                <div class="bg-white/10 p-4 rounded-2xl"><i
                                        class="fas fa-envelope text-xl text-brand-accent"></i></div>
                                <div>
                                    <h5 class="text-xs font-black uppercase tracking-widest text-slate-500 mb-1">Direct
                                        Email</h5>
                                    <p class="text-xl font-medium">{{ $data['email'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="h-[400px] lg:h-auto bg-slate-800 grayscale">
                    <!-- Placeholder for actual Google Map if available -->
                    <div class="w-full h-full flex items-center justify-center opacity-40">
                        <i class="fas fa-map-location-dot text-9xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>