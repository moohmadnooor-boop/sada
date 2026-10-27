<footer class="mt-24 border-t border-[var(--border)]">
    <div class="container-sada py-10 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex flex-col items-center md:items-start gap-2">
            <x-logo />
            <p class="text-muted text-sm">كلمات يبقى صداها.</p>
        </div>
        <ul class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm text-muted">
            <li><a href="{{ route('quotes.index') }}" class="hover:text-[var(--accent)]">الاقتباسات</a></li>
            <li><a href="{{ route('trending') }}" class="hover:text-[var(--accent)]">الأكثر صدى</a></li>
            <li><a href="{{ route('books.index') }}" class="hover:text-[var(--accent)]">المكتبة</a></li>
            @guest
                <li><a href="{{ route('signup') }}" class="hover:text-[var(--accent)]">إنشاء حساب</a></li>
            @endguest
        </ul>
        <p class="text-muted text-sm text-center md:text-end leading-relaxed">
            قيلت الكلمة مرّة، وبقي صداها… <span class="font-display font-bold text-accent" dir="ltr">MO7</span><br>
            <span class="text-xs">© {{ now()->year }} صدى</span>
        </p>
    </div>
</footer>
