<div class="mb-1">
    <h5 class="mb-0">
        {{ strtolower(App\Helpers\NumberHelper::numberToRoman($section->order)) }}.
        {{ $section->title }}
    </h5>

    @foreach($section->question as $index => $question)
    <div class="mb-1  margin-tab1">
        @include('download-pdf-component.questions.' . $question->type, [
            'label' => strtolower(App\Helpers\NumberHelper::numberToAlphabet($index + 1))
                . '. ' . $question->content,
            'options' => $question->options,
            'value' => $answers['q_'. $question->id] ?? [],
            'otherOptionLabel' => 'Other, please specify:'
        ])
    </div>
    @endforeach
</div>