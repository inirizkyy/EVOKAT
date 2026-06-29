@extends('layouts.admin')
@section('title', 'Pengaturan Website')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default mb-8 overflow-hidden">
            <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft">
                <h6 class="m-0 font-bold text-heading text-lg flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-brand"></i> Form Pengaturan Website
                </h6>
                <p class="text-sm text-body-subtle mt-1">Sesuaikan informasi kontak dan alamat yang tampil pada halaman depan.</p>
            </div>
            <div class="p-8">
                @if(!$pengaturan)
                    <div class="p-4 mb-6 rounded-xl border border-border-warning-subtle bg-warning-soft text-fg-warning flex items-start shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation mt-1 mr-3"></i>
                        <p class="text-[14px] m-0">Data pengaturan belum tersedia (Seeder belum dijalankan).</p>
                    </div>
                @else
                <form action="{{ route('admin.pengaturan.update', $pengaturan->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <h5 class="text-md font-bold text-brand-strong mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info"></i> Informasi Umum
                        </h5>
                        <div class="space-y-5">
                            <x-input-floating 
                                type="text" 
                                name="nama_instansi" 
                                label="Nama Instansi" 
                                :value="$pengaturan->nama_instansi" 
                                :required="true" 
                            />
                            
                            <x-textarea-floating 
                                name="deskripsi_singkat" 
                                label="Deskripsi Singkat Web" 
                                :value="$pengaturan->deskripsi_singkat" 
                                :required="true" 
                                rows="3" 
                            />
                        </div>
                    </div>

                    <div class="pt-6 border-t border-border-default border-dashed">
                        <h5 class="text-md font-bold text-brand-strong mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-address-book"></i> Kontak & Alamat
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <x-input-floating 
                                type="text" 
                                name="telepon" 
                                label="Telepon" 
                                :value="$pengaturan->telepon" 
                                :required="true" 
                            />
                            <x-input-floating 
                                type="email" 
                                name="email" 
                                label="Email" 
                                :value="$pengaturan->email" 
                                :required="true" 
                            />
                        </div>
                        
                        <x-textarea-floating 
                            name="alamat" 
                            label="Alamat Lengkap" 
                            :value="$pengaturan->alamat" 
                            :required="true" 
                            rows="2" 
                        />
                    </div>

                    <div class="pt-6 border-t border-border-default border-dashed">
                        <h5 class="text-md font-bold text-brand-strong mb-4 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot"></i> Peta Lokasi
                        </h5>
                        <x-textarea-floating 
                            name="maps_embed" 
                            label="Google Maps Embed (Iframe)" 
                            :value="$pengaturan->maps_embed" 
                            rows="4" 
                        />
                        <p class="text-xs text-body-subtle mt-2 ml-1"><i class="fa-solid fa-info-circle"></i> Paste kode &lt;iframe&gt; dari Google Maps untuk menampilkannya di halaman Kontak.</p>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-border-default flex justify-end">
                        <button type="submit" class="inline-flex justify-center items-center px-8 py-3 rounded-full text-[15px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-brand-softer">
                            <i class="fa-solid fa-save mr-2"></i> Update Pengaturan
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-1">
        <div class="bg-brand/5 border border-brand/20 rounded-xl p-6 relative overflow-hidden shadow-sm">
            <div class="absolute -right-6 -bottom-6 text-brand opacity-10 text-[8rem] pointer-events-none select-none z-0 rotate-12">
                <i class="fa-solid fa-lightbulb"></i>
            </div>
            <div class="relative z-10">
                <h6 class="font-bold text-brand-strong mb-3 flex items-center gap-2">
                    <i class="fa-regular fa-lightbulb text-brand"></i> Tips Pengaturan
                </h6>
                <ul class="text-sm text-body-subtle space-y-3">
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-success mt-1"></i>
                        <span>Pastikan nomor telepon dan email aktif karena akan digunakan pemohon untuk menghubungi instansi.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fa-solid fa-check text-success mt-1"></i>
                        <span>Untuk peta, gunakan fitur "Share" > "Embed a map" di Google Maps.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
