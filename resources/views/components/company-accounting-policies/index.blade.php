<html>
    <head>
        <style>
            @font-face {
                font-family: 'Calibri';
                src: url('../../../../fonts/calibri-regular.ttf') format('truetype');
                font-weight: normal;
                font-style: normal;
            }
        </style>
        @if ($style)
            {!! $style !!}
        @endif
    </head>
    <body>
        <div class="mb-3">
            <h1 style="text-align: center;">{{ $company->name }}</h1>
            <h1 style="text-align: center;">Notes to the financial statemetns</h1>
            <h1 style="text-align: center;">For the year ended {{ \Carbon\Carbon::parse($company->end_date)->format('M d, Y') }}</h1>
            <br>
            @forelse ($policies as $groupName => $accounting_policies)
                <p>
                    <strong>
                        @if ($groupName === 'COMPANY AND ITS OPERATIONS')
                            1.
                        @elseif ($groupName === 'BASIS OF PREPARATION')
                            2.
                        @elseif ($groupName === 'SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES')
                            3.
                        @endif
                        {{ $groupName }}
                    </strong>
                </p>
                @forelse ($accounting_policies as $policy)
                    <p>
                        <strong>
                            {{ $policy->index }}
                            @if ( $policy->policy_heading === 'COMPANY AND ITS OPERATIONS' )
                                {{ 1 . '.' . ($loop->index + 1) }}
                            @elseif ( $policy->policy_heading === 'BASIS OF PREPARATION' )
                                {{ 2 . '.' . ($loop->index + 1) }}
                            @elseif ( $policy->policy_heading === 'SUMMARY OF SIGNIFICANT ACCOUNTING POLICIES' )
                                {{ 3 . '.' . ($loop->index + 1) }}
                            @endif
                            {{ $policy->title }}
                        </strong>
                    </p>
                    <p>
                        {!! $policy->content !!}
                    </p>
                @empty

                @endforelse
            @empty
            @endforelse
        </div>
    </body>
</html>
