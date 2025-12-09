@if($style)
    {!! $style !!}
@endif
<div class="mb-3">
    @if ($auditReport)
        {!! $auditReport->content !!}
    @endif
</div>