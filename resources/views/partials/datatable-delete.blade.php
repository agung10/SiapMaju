<script>
    $(() => {
        let selector = 'a.btn-delete-datatable';
        @isset($selector)
            selector = '{{ $selector }}'
        @endisset

        $('body').on('click', selector, function(e) {
            let that = $(e.currentTarget);
            e.preventDefault()
            Swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data {{ $text }} ini?',
                icon: 'warning',
                showCancelButton: true,
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: that.attr('href'),
                        type: 'DELETE'
                    })
                    .done(() => {
                        swal.fire("Success", "Data {{ $text}} Berhasil di Hapus!", "success");

                        $('{{ $table }}').DataTable().ajax.reload();
                    })
                    .fail((err) => {
                        console.log(err)
                        swal.fire("Error", "Maaf Terjadi Kesalahan!", "error");
                    })
                } else if(result.dismiss === Swal.DismissReason.cancel) {
                    swal.fire("Proses Hapus Dibatalkan");
                }
            })
        })
    })
</script>