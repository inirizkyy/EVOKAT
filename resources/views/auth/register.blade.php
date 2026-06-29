<x-guest-layout>
    <div class="mb-8">
        <h3 class="font-['Playfair_Display'] text-3xl font-bold text-heading mb-2">Buat Akun Baru</h3>
        <p class="text-body-subtle text-[15px]">Silakan lengkapi formulir di bawah ini untuk mendaftarkan akun admin baru.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <x-input-floating 
            type="text" 
            name="name" 
            label="Nama Lengkap" 
            :required="true" 
            autofocus 
        />

        <!-- Email Address -->
        <x-input-floating 
            type="email" 
            name="email" 
            label="Alamat Email" 
            :required="true" 
        />

        <!-- Password -->
        <x-input-floating 
            type="password" 
            name="password" 
            label="Kata Sandi" 
            :required="true" 
        />

        <!-- Confirm Password -->
        <x-input-floating 
            type="password" 
            name="password_confirmation" 
            label="Konfirmasi Kata Sandi" 
            :required="true" 
        />

        <div class="flex items-center justify-between mt-4">
            <a class="text-sm font-bold text-brand hover:text-brand-strong transition-colors" href="{{ route('login') }}">
                {{ __('Sudah punya akun? Masuk disini.') }}
            </a>
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3.5 rounded-full text-[15px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-brand-softer">
                <i class="fa-solid fa-user-plus mr-2"></i> {{ __('Daftar Sekarang') }}
            </button>
        </div>
    </form>
</x-guest-layout>
