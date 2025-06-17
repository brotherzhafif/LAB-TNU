@if($getRecord() && $getRecord()->bukti_selesai)
    <style>
        .bukti-frame-container {
            width: 100%;
            height: 340px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            background: #fafafa;
            overflow: auto;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bukti-frame-img {
            display: block;
            max-width: none;
            max-height: none;
            width: auto;
            height: auto;
            transition: transform 0.2s;
        }

        .bukti-frame-toolbar {
            position: absolute;
            top: 10px;
            right: 16px;
            z-index: 2;
            display: flex;
            gap: 8px;
        }

        .bukti-frame-btn {
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 4px 10px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: background 0.15s;
        }

        .bukti-frame-btn:hover {
            background: #f3f4f6;
        }
    </style>
    <div class="bukti-frame-container" id="bukti-frame-container">
        <div class="bukti-frame-toolbar">
            <button type="button" class="bukti-frame-btn" id="zoom-in">+</button>
            <button type="button" class="bukti-frame-btn" id="zoom-out">−</button>
        </div>
        <img src="{{ Storage::disk(config('filesystems.default'))->url($getRecord()->bukti_selesai) }}" alt="Bukti Selesai"
            id="bukti-frame-img" class="bukti-frame-img" style="transform: scale(1);">
    </div>
    <script>
        const img = document.getElementById('bukti-frame-img');
        const zoomInBtn = document.getElementById('zoom-in');
        const zoomOutBtn = document.getElementById('zoom-out');
        let scale = 1;

        zoomInBtn.addEventListener('click', function () {
            scale += 0.2;
            img.style.transform = `scale(${scale})`;
        });

        zoomOutBtn.addEventListener('click', function () {
            scale = Math.max(0.2, scale - 0.2);
            img.style.transform = `scale(${scale})`;
        });
    </script>
@endif