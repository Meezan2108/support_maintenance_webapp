@php

$others = collect($value)->filter(function($item) use ($options) {
    return !in_array($item, $options);
});

$isOther = false;
if ($others->count() > 0) {
    $others = array_values($others->toArray());
    $isOther = true;
}

@endphp

<div class="">
    <label class="label-size fw-bold mb-sm-0 mb-2 form-label">
        {{ $label }}
    </label>
    <div class="ms-2">
        @foreach($options as $option)
        @include('download-pdf-component.questions.checkbox', [
            "option" => $option,
            "isChecked" => in_array($option, $value)
        ])
        @endforeach
        @include('download-pdf-component.questions.checkbox', [
            "option" => $otherOptionLabel,
            "isChecked" => $isOther
        ])

        @if($isOther)
        <div class="margin-tab1">
            - {{ $others[0] ?? '' }}
        </div>
        @endif
    </div>
</div>