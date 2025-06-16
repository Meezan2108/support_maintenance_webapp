<div class="mb-4">
    <div class="mb-0">
        <h4 class="m-0">
            {{ $questionGroup->order }}.
            {{ $questionGroup->title }}
        </h4>
        <small class="fst-italic">{{ $questionGroup->description }}</small>
    </div>
    @foreach($questionGroup->section as $section)
        @include('download-pdf.project-monitoring.eop._partials.wrapper-benefits-section', [
            'section' => $section,
            'answers' => $answers
        ])
    @endforeach
</div>