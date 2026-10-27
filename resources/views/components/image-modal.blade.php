<div class="modal" id="image-modal" role="dialog" aria-modal="true" aria-labelledby="image-modal-title">
    <div class="modal-panel !max-w-md">
        <div class="flex items-center justify-between mb-4">
            <h2 id="image-modal-title" class="font-display text-2xl font-bold">حوّل الاقتباس لصورة</h2>
            <button type="button" class="icon-btn" data-close-modal aria-label="إغلاق"><x-icon name="close" /></button>
        </div>

        <canvas class="image-preview" width="1080" height="1350" data-image-canvas></canvas>

        <div class="mt-4 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2" role="radiogroup" aria-label="نمط الصورة">
                <button type="button" class="style-swatch is-selected" data-image-style="night" style="background: linear-gradient(135deg,#0e1424,#1f3a4a)" title="صدى الليل" aria-label="صدى الليل"></button>
                <button type="button" class="style-swatch" data-image-style="sand" style="background: linear-gradient(135deg,#efe6d8,#b5653a)" title="صدى الرمل" aria-label="صدى الرمل"></button>
                <button type="button" class="style-swatch" data-image-style="sea" style="background: linear-gradient(135deg,#f2f6f8,#1f4e79)" title="صدى الماء" aria-label="صدى الماء"></button>
            </div>
            <button type="button" class="btn btn-primary btn-sm" data-image-download><x-icon name="download" class="w-4 h-4" /> تحميل</button>
        </div>
        <p class="hint">مقاس 1080×1350 مناسب للستوري ومنصات التواصل.</p>
    </div>
</div>
