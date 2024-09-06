@extends('partials.layout')
@section('content')
    <div class="inline-flex flex-col items-center justify-between gap-4 pb-4 bg-white w-60 h-96 rounded-2xl animate-pulse">
        <div class="relative h-48 bg-gray-200 w-60 rounded-2xl"></div>
        <div class="flex flex-col items-center self-stretch justify-start h-24 gap-3 px-4 pb-3 border-b border-zinc-300">
            <div class="self-stretch h-20 flex-col justify-start items-start gap-1.5 flex">
                <div class="self-stretch text-center text-gray-900 text-lg font-extrabold font-['Mulish'] leading-normal">
                </div>
                <div class="self-stretch text-center text-zinc-800 text-xs font-normal font-['Mulish'] leading-tight"></div>
            </div>
        </div>
        <button class="inline-flex items-center justify-center h-10 gap-2 px-5 py-3 text-white rounded-lg bg-emerald-600">An
            Other Button</button>
        <button class="w-1/2 h-12 bg-gray-200 rounded-full btn-primary">Get Started</button>
    </div>
@endsection
