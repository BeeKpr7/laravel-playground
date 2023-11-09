@extends('partials.layout')
@section('content')
    <div class="inline-flex flex-col items-center justify-between gap-4 pb-4 bg-white w-60 h-96 rounded-2xl">
        <div class="relative h-48 w-60">
            <img class="absolute top-0 left-0 h-48 w-60 rounded-2xl" src="https://via.placeholder.com/240x200" />
        </div>
        <div class="flex flex-col items-center self-stretch justify-start h-24 gap-3 px-4 pb-3 border-b border-zinc-300">
            <div class="self-stretch h-20 flex-col justify-start items-start gap-1.5 flex">
                <div class="self-stretch text-center text-gray-900 text-lg font-extrabold font-['Mulish'] leading-normal">
                    Sebastian</div>
                <div class="self-stretch text-center text-zinc-800 text-xs font-normal font-['Mulish'] leading-tight">
                    Sebastian creates circles and squares that actually make you want to interact with them.</div>
            </div>
        </div>
        <button class="inline-flex items-center justify-center h-10 gap-2 px-5 py-3 text-white rounded-lg bg-emerald-600">
            <p class="text-sm font-semibold leading-normal text-center">Get Started</p>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path
                    d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" />
                <path
                    d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z" />
                <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
            </svg>
        </button>
    </div>
@endsection
