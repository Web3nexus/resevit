@extends('layouts.landing')

@section('title', 'Contact Us - Resevit')

@section('content')
    <section class="py-24 bg-brand-offwhite">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Info Column -->
                <div>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-brand-primary mb-8 tracking-tight">Let's Talk About
                        Your Restaurant's Future</h1>
                    <p class="text-xl text-slate-600 mb-12 leading-relaxed">
                        Have questions about our platform? Our experts are here to help you optimize your operations.
                    </p>

                    <div class="space-y-10">
                        <div class="flex items-start space-x-5">
                            <div
                                class="w-12 h-12 bg-brand-accent/20 rounded-xl flex items-center justify-center text-brand-primary flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-brand-primary mb-1">Email Us</h4>
                                <p class="text-slate-600 italic">hello@resevit.com</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-5">
                            <div
                                class="w-12 h-12 bg-brand-accent/20 rounded-xl flex items-center justify-center text-brand-primary flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-brand-primary mb-1">Our Headquarters</h4>
                                <p class="text-slate-600">123 Gastronomy St, Suite 400<br>San Francisco, CA 94103</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-5">
                            <div
                                class="w-12 h-12 bg-brand-accent/20 rounded-xl flex items-center justify-center text-brand-primary flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-brand-primary mb-1">Support Hours</h4>
                                <p class="text-slate-600">Monday — Friday: 9AM - 6PM EST<br>Weekend: Priority Support Only
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Column -->
                <div class="bg-slate-50 p-8 md:p-12 rounded-[2.5rem] border border-slate-100 shadow-sm">
                    @if(session('success'))
                        <div class="mb-8 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-bold text-brand-primary mb-2">Full Name</label>
                                <input type="text" name="name" id="name" required
                                    class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-accent focus:border-transparent outline-none transition-all"
                                    placeholder="John Doe">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-bold text-brand-primary mb-2">Email
                                    Address</label>
                                <input type="email" name="email" id="email" required
                                    class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-accent focus:border-transparent outline-none transition-all"
                                    placeholder="john@example.com">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-bold text-brand-primary mb-2">Subject</label>
                            <select name="subject" id="subject"
                                class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-accent focus:border-transparent outline-none transition-all bg-white">
                                <option>General Inquiry</option>
                                <option>Sales Question</option>
                                <option>Technical Support</option>
                                <option>Partnership</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-bold text-brand-primary mb-2">Message</label>
                            <textarea name="message" id="message" rows="5" required
                                class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-brand-accent focus:border-transparent outline-none transition-all"
                                placeholder="How can we help you?"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full py-4 bg-brand-primary text-white font-extrabold rounded-xl hover:bg-brand-secondary transition-all shadow-lg hover:shadow-xl">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection