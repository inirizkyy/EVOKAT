<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-8">
        <h3 class="font-['Playfair_Display'] text-3xl font-bold text-heading mb-2">Selamat Datang Kembali</h3>
        <p class="text-body-subtle text-[15px]">Silakan masukkan email dan kata sandi Anda untuk mengakses dashboard admin.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <x-input-floating 
            type="email" 
            name="email" 
            label="Alamat Email" 
            :required="true" 
            autofocus 
        />

        <!-- Password -->
        <x-input-floating 
            type="password" 
            name="password" 
            label="Kata Sandi" 
            :required="true" 
        />

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded border-border-default text-brand shadow-sm focus:ring-brand focus:ring-opacity-50 transition-colors" name="remember">
                <span class="ms-2 text-sm text-body font-medium group-hover:text-heading transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-brand hover:text-brand-strong transition-colors" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3.5 rounded-full text-[15px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-brand-softer">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Masuk ke Dashboard
            </button>
        </div>
    </form>
</x-guest-layout>
