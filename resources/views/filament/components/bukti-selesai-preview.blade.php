@if($getRecord() && $getRecord()->bukti_selesai)
    <img src="{{ asset('storage/' . $getRecord()->bukti_selesai) }}" style="max-width:200px;">
@endif