@extends("layout")

@section("content")
    <div class="row">
        <div class="offset-3 col-6 mb-2">

            @include("pdf.errors")
            @include("pdf.success")

            <h1>Upload PDF</h1>
            <form action="{{route('pdf.upload')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="pdfFile" class="form-label">Upload a PDF</label>
                    <input class="form-control"
                           name="file"
                           type="file"
                           id="pdfFile"
                           required
                           accept="application/pdf"
                           />
                </div>

                <button class="btn btn-primary" type="submit">Upload</button>
            </form>
            <hr>
        </div>

        @include("pdf.uploads")

    </div>
@endsection
