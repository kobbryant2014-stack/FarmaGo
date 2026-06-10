<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/farmago-login-logo.css') }}">
    @endpush

    <main class="login-shell min-h-screen lg:grid lg:grid-cols-[1.08fr_.92fr]">
        <section class="login-photo relative flex min-h-[44vh] items-end overflow-hidden p-6 text-white lg:min-h-screen lg:items-center lg:p-12">
            <div class="absolute inset-0 bg-gradient-to-t from-sky-950/50 via-sky-900/10 to-transparent"></div>
            <div class="login-brand-panel relative max-w-2xl">
                <a href="/" class="farmago-logo-home" aria-label="Ir al inicio de FarmaGo">
                    <span class="farmago-login-brand">
                        <img
                            src="{{ asset('images/farmago-logo.png') }}"
                            alt="{{ config('app.name', 'FarmaGo') }}"
                            class="farmago-login-logo"
                        >
                    </span>
                </a>
                <h1 class="login-hero-title mt-8 max-w-xl text-4xl font-bold leading-tight text-white sm:text-5xl">
                    Gestion farmaceutica clara, rapida y segura.
                </h1>
                <p class="login-hero-copy mt-5 max-w-xl text-lg font-medium text-sky-50">
                    Controla productos, lotes, vencimientos, compras y ventas desde un panel amigable para tu farmacia.
                </p>
                <div class="login-benefits mt-7 flex flex-wrap gap-3 text-sm font-semibold">
                    <span class="rounded-full bg-white/90 px-4 py-2 text-sky-800">Inventario en orden</span>
                    <span class="rounded-full bg-emerald-400 px-4 py-2 text-emerald-950">Alertas de vencimiento</span>
                    <span class="rounded-full bg-cyan-200 px-4 py-2 text-cyan-950">Ventas mas agiles</span>
                </div>
            </div>
        </section>

        <section class="login-form-section flex items-center justify-center bg-gradient-to-br from-sky-50 via-white to-emerald-50 px-5 py-10 lg:px-10">
            <div class="login-form-wrap w-full max-w-md">
                <div class="login-form-heading mb-8">
                    <p class="text-sm font-bold uppercase tracking-wide text-emerald-600">Bienvenido</p>
                    <h2 class="mt-2 text-3xl font-bold text-slate-900">Ingresa a FarmaGo</h2>
                    <p class="mt-2 text-slate-600">Usa tus credenciales para continuar con la gestion diaria.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="login-card rounded-2xl border border-sky-100 bg-white/95 p-6 shadow-2xl shadow-sky-900/10">
                    @csrf

                    <div class="login-field">
                        <x-input-label for="email" value="Correo electronico" class="text-slate-700" />
                        <x-text-input
                            id="email"
                            class="mt-2 block w-full rounded-xl border-sky-100 bg-sky-50/70 px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-400 focus:ring-emerald-400"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="admin@farmago.com"
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="login-field mt-5">
                        <x-input-label for="password" value="Clave" class="text-slate-700" />
                        <x-text-input
                            id="password"
                            class="mt-2 block w-full rounded-xl border-sky-100 bg-sky-50/70 px-4 py-3 text-slate-900 shadow-sm focus:border-emerald-400 focus:ring-emerald-400"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Tu clave"
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="login-options mt-5 flex items-center justify-between gap-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-sky-200 text-emerald-500 shadow-sm focus:ring-emerald-400" name="remember">
                            <span class="ms-2 text-sm text-slate-600">Recordarme</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm font-semibold text-sky-700 hover:text-emerald-600" href="{{ route('password.request') }}">
                                Olvide mi clave
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="login-submit mt-6 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-sky-500 to-emerald-500 px-5 py-3 text-base font-bold text-white shadow-lg shadow-emerald-900/15 transition hover:from-sky-600 hover:to-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                        Iniciar sesion
                    </button>
                </form>
            </div>
        </section>
    </main>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
        <script src="{{ asset('js/farmago-login-logo.js') }}"></script>
    @endpush
</x-guest-layout>
