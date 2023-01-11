<div class="offset-3 col-6 mt-4">

    <div class="row">
        <div class="col-6">
            <h2>Your Files</h2>
        </div>
        <div class="col-6">
            <a href="{{route("pdf.merge")}}">
                <button class="btn btn-warning btn-sm">Merge All</button>
            </a>
        </div>
    </div>

    @if($pdfs)
        @foreach($pdfs as $pdf)
            <div class="row mb-2">
                <div class="col-6">
                    {{ $pdf["name"] }}
                </div>
                <div class="col-6">
                    <a target="_blank" href="{{$pdf["url"]}}">
                        <button class="btn btn-success btn-sm">View in Browser</button>
                    </a>
                </div>
            </div>
        @endforeach
    @endif

</div>
