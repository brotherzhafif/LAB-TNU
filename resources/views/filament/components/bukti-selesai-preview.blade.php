@if($getRecord() && $getRecord()->bukti_selesai)
    <div class="flex flex-col items-center space-y-2 my-4">
        <span class="text-sm text-gray-500">Preview Bukti Selesai:</span>
        <img src="{{ Storage::disk('s3')->url($getRecord()->bukti_selesai) }}" alt="Bukti Selesai"
            class="rounded-lg border border-gray-300 shadow-md max-w-xs max-h-60 object-contain transition-transform hover:scale-105">
        <a href="{{ Storage::disk('s3')->url($getRecord()->bukti_selesai) }}" target="_blank"
            class="text-xs text-blue-600 hover:underline">Lihat ukuran penuh</a>
    </div>
@endif