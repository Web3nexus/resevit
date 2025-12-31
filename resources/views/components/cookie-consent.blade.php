<div x-data="{ 
    show: false,
    init() {
        if (!this.getCookie('cookie_consent')) {
            setTimeout(() => { this.show = true; }, 1000);
        }
    },
    accept() {
        this.setCookie('cookie_consent', 'accepted', 365);
        this.show = false;
    },
    setCookie(name, value, days) {
        let expires = '';
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        document.cookie = name + '=' + (value || '') + expires + '; path=/';
    },
    getCookie(name) {
        let nameEQ = name + '=';
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
}" x-show="show" x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-full" class="fixed bottom-0 left-0 right-0 z-100 p-4 md:p-6"
    style="display: none;">
    <div class="max-w-7xl mx-auto">
        <div
            class="bg-white/95 backdrop-blur-md rounded-2xl md:rounded-3xl shadow-2xl border border-slate-200 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-xl font-bold text-brand-primary mb-2">We value your privacy</h2>
                <p class="text-slate-600 text-sm md:text-base leading-relaxed">
                    We use cookies to enhance your browsing experience, serve personalized ads or content, and analyze
                    our traffic. By clicking "Accept All", you consent to our use of cookies. Read our
                    <a href="{{ route('landing.cookie-policy') }}"
                        class="text-brand-accent font-semibold hover:underline">Cookie Policy</a>.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                <button @click="accept()"
                    class="w-full sm:w-auto px-8 py-3 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-primary/90 transition-all shadow-lg shadow-brand-primary/20 whitespace-nowrap">
                    Accept All
                </button>
            </div>
        </div>
    </div>
</div>