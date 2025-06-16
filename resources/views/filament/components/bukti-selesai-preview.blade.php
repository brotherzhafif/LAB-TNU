@if($getRecord() && $getRecord()->bukti_selesai)
    <img src="{{ Storage::disk('s3')->url($getRecord()->bukti_selesai) }}" style="max-width:200px;">
@endif