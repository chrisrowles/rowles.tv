<div id="video-container"></div>

@section('scripts')
    @parent
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            _video.setup('{{ $file }}')
        });
    </script>
@endsection
