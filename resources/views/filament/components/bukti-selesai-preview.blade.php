@if($getRecord() && $getRecord()->bukti_selesai)
    <style>
        .bukti-preview-container {
            width: 100%;
            height: 340px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            position: relative;
        }

        .bukti-preview-img {
            height: 100%;
            width: 100%;
            object-fit: contain;
            transition: transform 0.3s cubic-bezier(.4, 0, .2, 1);
            will-change: transform;
            cursor: zoom-in;
            pointer-events: auto;
        }
    </style>
    <div class="bukti-preview-container">
        <img src="{{ Storage::disk('s3')->url($getRecord()->bukti_selesai) }}" alt="Bukti Selesai" class="bukti-preview-img"
            id="bukti-preview-img">
    </div>
    <script>
        const img = document.getElementById('bukti-preview-img');
        const container = img.parentElement;

        let isHovering = false;

        container.addEventListener('mousemove', function (e) {
            isHovering = true;
            const rect = container.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            img.style.transform = `scale(1.7) translate(-${x - 50}%, -${y - 50}%)`;
        });

        container.addEventListener('mouseleave', function () {
            isHovering = false;
            img.style.transform = 'scale(1) translate(0,0)';
        });
    </script>
@endif