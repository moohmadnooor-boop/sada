<div class="modal" id="share-modal" role="dialog" aria-modal="true" aria-labelledby="share-modal-title" @if ($errors->hasAny(['content', 'author', 'category_id', 'book_id']) && old('_form') === 'share') data-open-on-load @endif>
    <div class="modal-panel">
        <div class="flex items-center justify-between mb-5">
            <h2 id="share-modal-title" class="font-display text-2xl font-bold">شارك اقتباساً</h2>
            <button type="button" class="icon-btn" data-close-modal aria-label="إغلاق"><x-icon name="close" /></button>
        </div>

        <form method="post" action="{{ route('quotes.store') }}" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="_form" value="share">
            <x-field type="textarea" name="content" label="نص الاقتباس" :max="155" :min="3" rows="4" required
                placeholder="اكتب كلمات تستحق أن يبقى صداها..." hint="من 3 إلى 155 حرفاً" id="share-content" />
            <div class="grid sm:grid-cols-2 gap-4">
                <x-field name="author" label="قائل الاقتباس (اختياري)" :max="80" placeholder="مثال: المتنبي" hint="اتركه فارغاً إن كان من كلماتك" id="share-author" />
                <x-field type="select" name="category_id" label="التصنيف (اختياري)" hint="يساعد الآخرين في الوصول له" id="share-category">
                    <option value="">بدون تصنيف</option>
                    @foreach ($allCategories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->icon }} {{ $category->name }}</option>
                    @endforeach
                </x-field>
            </div>
            <x-field type="select" name="book_id" label="من كتاب (اختياري)" hint="اختر الكتاب إن كان الاقتباس منه ليظهر في المكتبة" id="share-book">
                <option value="">ليس من كتاب في المكتبة</option>
                @foreach ($allBooks as $book)
                    <option value="{{ $book->id }}" @selected((string) old('book_id') === (string) $book->id)>{{ $book->title }} — {{ $book->author }}</option>
                @endforeach
            </x-field>
            <button type="submit" class="btn btn-primary btn-lg mt-2">إرسال <x-icon name="sparkle" class="w-4 h-4" /></button>
        </form>
    </div>
</div>
