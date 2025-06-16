@php

$collectValue = collect($value);
$others = $collectValue->filter(function($item) use ($options) {
    return !in_array($item, $options);
});

$isOther = false;
if ($others->count() > 0) {
    $others = array_values($others->toArray());
    $isOther = true;
}

$optionValue = $collectValue->map(function($item) {
    return $item->value;
})->toArray();

@endphp

<div class="">
    <label class="label-size fw-bold mb-sm-0 mb-2 form-label">
        {{ $label }}
    </label>
    <div class="ms-2">
        @foreach($options["options"] as $option)
            @include('download-pdf-component.questions.checkbox', [
                "option" => $option,
                "isChecked" => in_array($option, $optionValue)
            ])
            
            @if(in_array($option, $optionValue))

            @php
                $findItem = $collectValue->where('value', $option)
                    ->first();
            @endphp

            <div class="margin-tab1">
                - {{ $options["label"] }} {{ $findItem->data ?? '' }}
            </div>
            @endif
        @endforeach


        @include('download-pdf-component.questions.checkbox', [
            "option" => $otherOptionLabel,
            "isChecked" => $isOther
        ])

        @if($isOther)
        <div class="margin-tab1">
            - {{ $others[0]->value ?? '' }} <br />
            {{ $options["label"] }} {{ $others[0]->data ?? '' }}
        </div>
        @endif
    </div>
</div>