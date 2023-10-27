@extends('layouts.main')

@section('pageTitle', 'Документ')

@section('content')
    <h2 class="text-left mb-6 w-full">{{ $document['id'] }}</h2>
    <table class="w-full mt-6">
        <tr>
            <td>Создан:</td>
            <td>{{ \Carbon\Carbon::createFromIsoFormat('YYYY-D-M HH:mm:ssZ', $document['createAt'])->diffForHumans() }}</td>
        </tr>
        <tr>
            <td>Обновлен:</td>
            <td>{{ \Carbon\Carbon::createFromIsoFormat('YYYY-D-M HH:mm:ssZ', $document['modifyAt'])->diffForHumans()}}</td>
        </tr>
        @if (!empty($document['payload']))
            <tr>
                <td class="pt-12" colspan="2">
                    <div class="json-doc hidden border-gray-300 border p-6 rounded-lg bg-slate-50">
                     {{ json_encode($document['payload']) }}
                    </div>
                </td>
            </tr>
        @endif
    </table>
    <a href="{{ route('document.index') }}"
       class="mt-12 text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
        К списку документов
    </a>
@endsection
