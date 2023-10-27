@extends('layouts.main')

@section('pageTitle', 'Документы')

@section('content')
    @php
        $pagination = $content['pagination'];
        $page = $pagination['page'];
        $perPage = $pagination['perPage'];
        $startNumber = $page * $perPage - $perPage + 1;
        $number = $startNumber;
    @endphp
    @foreach($content['document'] as $document)
        <a href="{{ route('document.show', $document['id']) }}" class="hover:bg-gray-100 pl-6 pr-6 p-6 mt-4 mr-4 ml-4 border rounded-xl w-full">
            <div>
                <b>{{ $number++ }}</b><span class="float-right">{{ $document['id'] }}</span>
            </div>
            <div class="w-full text-center text-slate-300 text-sm relative top-0 h-0">
                {{ \Carbon\Carbon::createFromIsoFormat('YYYY-D-M HH:mm:ssZ', $document['createAt'])->diffForHumans() }}
            </div>
        </a>
    @endforeach
    <x-pagination :startNumber="$startNumber" :args="$pagination"/>
@endsection
