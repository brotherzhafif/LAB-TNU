{{-- filepath:
c:\Users\u\Coding_Project\LAB_TNU\resources\views\filament\resources\tool-booking-resource\pages\selesai-peminjaman-alat.blade.php
--}}
<x-filament-panels::page>
    <form action="{{ route('tool-booking.selesai-upload', ['record' => $this->booking->id]) }}" method="POST"
        enctype="multipart/form-data" id="selesai-form"
        class="bg-white dark:bg-gray-900 rounded-xl shadow-xl px-8 py-8 space-y-8 w-full mx-auto">
        @csrf

        <x-filament::section>
            <div>
                <label for="jumlah_dikembalikan"
                    class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
                    Jumlah Alat Dikembalikan
                </label>
                <div class="relative">
                    <select name="jumlah_dikembalikan" id="jumlah_dikembalikan"
                        class="filament-input w-full bg-gray-50 dark:bg-gray-800 rounded-lg border-none focus:ring-2 focus:ring-primary-200 text-lg font-semibold"
                        required>
                        <option value="" disabled selected>Pilih jumlah</option>
                        @for ($i = 0; $i <= $this->booking->jumlah; $i++)
                            <option value="{{ $i }}" {{ old('jumlah_dikembalikan', $this->booking->jumlah_dikembalikan) == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Masukkan jumlah alat yang benar sesuai pengembalian. max {{ $this->booking->jumlah }}.
                </div>
            </div>

            <div class="mt-6">
                <label for="bukti_selesai" class="block text-sm font-semibold mb-2 text-gray-700 dark:text-gray-200">
                    Upload Bukti Selesai
                </label>
                <label id="upload-label"
                    class="flex flex-col items-center px-6 py-8 bg-white dark:bg-gray-800 text-primary-600 rounded-lg shadow-lg tracking-wide uppercaseborder-2 border-primary-500 focus:border-primary-600 focus:ring-2 focus:ring-primary-200 cursor-pointer hover:bg-primary-50 dark:hover:bg-primary-900 transition"
                    style="min-height: 160px;">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5m0 0l5 5m-5-5v12" />
                    </svg>
                    <span class="mt-2 text-base leading-normal font-semibold">Pilih Gambar</span>
                    <input type="file" name="bukti_selesai" id="bukti_selesai" accept="image/*" required class="hidden"
                        onchange="previewImage(event)" />
                </label>
                <div class="text-xs text-gray-500 mt-3 text-center">
                    Format gambar (jpg, png, webp, dll), maksimal 2MB.
                </div>
                <div id="preview-container" class="w-full flex justify-center mt-4"></div>
            </div>
        </x-filament::section>

        <div id="loading-indicator" class="hidden text-center text-primary-600 font-semibold">
            Mengupload file, mohon tunggu...
        </div>

        <div class="flex justify-end">
            <x-filament::button type="submit" id="submit-btn" color="primary" icon="heroicon-m-check-circle">
                Selesai &amp; Kembalikan
            </x-filament::button>
        </div>
    </form>

    <script>
        function previewImage(event) {
            const input = event.target;
            const label = document.getElementById('upload-label');
            const container = document.getElementById('preview-container');
            container.innerHTML = '';
            if (input.files && input.files[0]) {
                const file = input.files[0];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Hide upload icon and text
                        label.classList.add('hidden');
                        // Show preview
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded shadow border border-primary-400';
                        img.style.maxHeight = '180px';
                        img.style.maxWidth = '320px';
                        img.style.objectFit = 'cover';
                        container.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                }
            } else {
                // If no file, show label again
                label.classList.remove('hidden');
            }
        }

        document.getElementById('selesai-form').addEventListener('submit', function () {
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('loading-indicator').classList.remove('hidden');
        });
    </script>
</x-filament-panels::page>