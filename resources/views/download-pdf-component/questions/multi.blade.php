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
    </div>
</div>