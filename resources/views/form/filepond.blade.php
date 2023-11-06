@extends('partials.layout')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="pb-3 mb-4 text-xl font-semibold border-b-2 border-black">Filepond</h1>
                <form action="{{ route('filepond.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="images[]"
                        class="flex items-center justify-center px-5 py-10 bg-blue-500 grow-0 rounded-xl filepond" multiple>
                    <button class="w-full py-3 text-xl font-bold bg-blue-400 rounded-xl" type="submit">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection
