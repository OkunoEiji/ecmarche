<div>
    {{-- ショップのfilenameが無い場合、no_imageを表示 --}}
    @if(empty($filename))
        <img src="{{ asset('images/no_image.jpg') }}">
    @else
        {{-- ショップのfilenameがある場合、画像が表示 --}}
        <img src="{{ asset('storage/shops/' . $filename) }}">
    @endif
</div>