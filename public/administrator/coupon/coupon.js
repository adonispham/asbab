(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if ($('#coupons-table').length) {
        let urlAjax = $('#coupons-table').data('url');
        let columns;
        if (urlAjax.split('data/')[1] == 0) {
            columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'code',
                name: 'code',
                class: 'text-center'
            },
            {
                data: 'discount',
                name: 'discount',
                class: 'text-center'
            },
            {
                data: 'quantity',
                name: 'quantity',
                class: 'text-center'
            }];
        } else {
            columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'code',
                name: 'code',
                class: 'text-center'
            },
            {
                data: 'discount',
                name: 'discount',
                class: 'text-center'
            },
            {
                data: 'quantity',
                name: 'quantity',
                class: 'text-center'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }];
        }
        $('#coupons-table').DataTable({
            processing: true,
            responsive: true,
            dom: '<"flex-between"lf>t<"flex-between"ip>',
            language: {
                processing: "<div id='loader'>Đang tải dữ liệu !</div>",
                paginate: {
                    previous: '← Trước',
                    next: 'Sau →'
                },
                infoEmpty: '',
                zeroRecords: 'Không có dữ liệu!',
                search: 'Tìm',
                lengthMenu: '_MENU_ kết quả một trang',
                info: 'Hiển thị _START_ đến _END_ của _TOTAL_ kết quả'
            },
            serverSide: true,
            order: [0, 'desc'],
            ajax: urlAjax,
            columns: columns
        });
        $(document).on('click', '.action-delete', actionDelete);
    }

    $(document).on('click', '.btn-send-coupon a', function (e) {
        e.preventDefault();
        let that = $(this);
        $.ajax({
            type: 'GET',
            url: that.data('href'),
            dataType: 'json',
            success: function (data) {
                Swal.fire(
                    'Đã gửi!',
                    'Mã giảm giá đã được gửi.',
                    'success'
                )
            }
        });
    });

    function actionDelete(e) {
        e.preventDefault();
        let hrefData = $(this).data('href');
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Bạn sẽ không lấy lại được dữ liệu!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Hủy',
            confirmButtonText: 'Chắc chắn, xóa!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'GET',
                    url: hrefData,
                    dataType: 'json',
                    success: function (data) {
                        Swal.fire(
                            'Đã xóa!',
                            'Mã giảm giá đã được xóa.',
                            'success'
                        )
                        $('#coupons-table').DataTable().ajax.reload();
                    }
                });
            }
        })
    }

})(jQuery)
