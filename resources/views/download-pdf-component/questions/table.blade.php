<div class="">
    <label class="label-size fw-bold mb-sm-0 mb-2 form-label">
        {{ $label }}
    </label>
    <div class="ms-2 mt-1">
        <table class="table table-border" style="width:100%">
            <thead>
                <tr>
                    @foreach($options as $option)
                    <td>{{ $option }}</td>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($value as $row)
                    <tr>
                    @foreach($options as $key => $option)
                        <td>{{$row[$key] ?? '' }}</td>
                    @endforeach
                    </tr>
                @endforeach

                @if(!$value)
                    <tr>
                        <td colspan="{{ count($options) }}">&nbsp;</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>