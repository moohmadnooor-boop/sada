@extends('Layout.app')

@section('content')
    <div class="container-sada grid min-h-[calc(100vh-4.5rem)] items-center gap-10 py-12 md:grid-cols-2">
        <div class="animate-fade-up">
            @yield('form')
        </div>

        <div class="hidden md:flex flex-col items-center justify-center text-center animate-fade-up" style="--i: 2">
            <x-echo-art class="w-full max-w-md" />
            <p class="font-quote text-2xl leading-loose mt-6 max-w-sm">@yield('aside', '«الكلمة الطيبة لا تموت، يبقى صداها»')</p>
        </div>
    </div>
@endsection
