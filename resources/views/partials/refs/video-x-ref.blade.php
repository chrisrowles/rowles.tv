<script type="text/javascript">
    function xVideo() {
        return {
            id: null,
            data: @json(['rows' => $videos->items()]),
            list: @json(['options' => $all]),
            access(id = null) {
                if ((id === null && this.id === 'false') || (id === null && this.id === null)) {
                    _notify.send('error', 'Please select a valid video from the list.');
                    return false;
                }

                if (!id && this.id) id = this.id; // Dropdown selection with bound x-model (this.id)

                if (id) {
                    let route = '{{ route('api.video.get', ['id' => ':id']) }}'.replace(':id', id);
                    _video.get(route, this.$refs);

                    this.id = id; // Table selection with bound x-ref (id)
                }
            },
            update() {
                let route = '{{ route('api.video.update', ['id' => ':id']) }}'.replace(':id', this.id);
                _video.update(route, this.$refs);
            }
        }
    }
</script>
