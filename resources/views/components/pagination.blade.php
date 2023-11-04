@php
    $total = $args['total'];
    $page = $args['page'];
    $endNumber = $startNumber + $args['perPage'] - 1;
    $endNumber = $endNumber > $total ? $total : $endNumber;
    $nextLink = $endNumber == $total ? '' : request()->fullUrlWithQuery(['page' => $args['page'] + 1]);
    $previousLink = $page == 1 ? '' : request()->fullUrlWithQuery(['page' => $args['page'] - 1]);
    $paginationButtonClasses = 'flex items-center justify-center px-4 h-10 text-base font-medium text-white
                 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white';
@endphp
<div class="flex flex-col items-center mt-12">
    <!-- Help text -->
    <span class="text-sm text-gray-700 dark:text-gray-400">
        @if ($total > 0)
            Показано от
            <span class="font-semibold text-gray-900 dark:text-white">{{ $startNumber }}</span> до
            <span class="font-semibold text-gray-900 dark:text-white">{{ $endNumber }}</span> из
        @endif
        <span class="font-semibold text-gray-900 dark:text-white">{{ $args['total'] }}</span>
        @php
            echo ($total == 1 || ($total > 20 && $total % 10 == 1)) ? 'Документа' : 'Документов';
        @endphp
    </span>
    <!-- Buttons -->
    @if (!($startNumber == 1 && $endNumber == $total) && $total != 0)
        <div class="inline-flex mt-2 xs:mt-0">
            <a href="{{ $previousLink }}"
                @class([
                     $paginationButtonClasses,
                     'rounded-l',
                     'bg-gray-800 hover:bg-gray-900' => !empty($previousLink),
                     'bg-gray-400 cursor-default' => empty($previousLink)
                 ])
                {!! empty($previousLink) ? 'style="pointer-events: none"' : '' !!}
            >
                Назад
            </a>
            <a href="{{ $nextLink }}"
                @class([
                      $paginationButtonClasses,
                      'rounded-r',
                      'bg-gray-800 hover:bg-gray-900' => !empty($nextLink),
                      'bg-gray-400 cursor-default' => empty($nextLink)
                  ])
                {!! empty($nextLink) ? 'style="pointer-events: none"' : '' !!}
            >
                Вперед
            </a>
        </div>
    @endif
</div>
