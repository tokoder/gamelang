
$(function() {
    $(document).on('submit', '.formsearch', function(e) {
        e.preventDefault()
        ajaxList();
    })

    $(document).on('change', '.sortdata, .filterdata', function(e) {
        e.preventDefault();
        ajaxList();
    })

    $(document).on('click', '.showdata', function(e) {
        e.preventDefault();

        var $this = $(this);
        var route = $this.data('url');
        var formData = {
            id: $this.data('id')
        };

        // Ajax config
        ajaxSetup(route, formData);
        $.ajax({
            beforeSend: function() {
                $this.prop("disabled", true)
                    .append("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(result, status, xhr) {
                $('#showmodal').modal('show');
                $('#showmodal .modal-body').html(result.html);
                $('#showmodal .modal-title').html($this.data('title'));
            },
            complete: function(xhr, status) {
                $this.prop("disabled", false)
                    .children().remove(".fa-spin");
            },
        });
    })

    $(document).on('click', '.formdata', function(e) {
        e.preventDefault();

        var $this = $(this);
        var route = $this.data('url');
        var formData = {
            id: $this.data('id')
        };

        // Ajax config
        ajaxSetup(route, formData);
        $.ajax({
            beforeSend: function(xhr) {
                $this.prop("disabled", true)
                    .append("<i class='fa fa-spinner fa-spin'></i>");
            },
            success: function(result, status, xhr) {
                $('#modal').modal('show');
                $('#form .modal-body').html(result.html);
                $('#form .modal-title').html($this.data('title'));
                $('#form').attr('action', $this.data('url') + '-submit');
            },
            complete: function(xhr, status) {
                $this.prop("disabled", false)
                    .children().remove(".fa-spin");
            },
        });
    })

    $(document).on('click', '.confirmdata', function(e) {
        e.preventDefault();

        var $this = $(this);
        var route = $this.data('url');
        var formData = {
            id: $this.data('id')
        };

        Swal.fire({
            title: "Anda yakin?",
            text: $this.data('title'),
            icon: "warning",
            showDenyButton: true,
            confirmButtonText: "Oke",
            denyButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                // Ajax config
                ajaxSetup(route, formData);
                $.ajax({
                    beforeSend: function(xhr) {
                        $this.prop("disabled", true)
                            .append("<i class='fa fa-spinner fa-spin'></i>");
                    },
                    success: function(result, status, xhr) {
                        if (xhr.status == 202) {
                            $('#tr_' + $this.data('id')).remove();
                        } else if (xhr.status == 201) {
                            ajaxList()
                        }
                    },
                    complete: function(xhr, status) {
                        $this.prop("disabled", false)
                            .children().remove(".fa-spin");

                        var result = eval("(" + xhr.responseText + ")");
                        Toast.fire({
                            icon: status,
                            title: result.msg,
                            timer: 3000
                        })
                    },
                });
            }
        });
    })

    $(document).on('click', '.confirmalldata', function(e) {
        e.preventDefault();

        // Get userid from checked checkboxes
        var ids = [];
        var checklist = '.checklistdata';
        $(checklist + ":checked").each(function() {
            ids.push($(this).val());
        });

        if (ids.length == 0) {
            return;
        }

        var $this = $(this);
        var route = $this.data('url');
        var formData = {
            ids: ids
        };

        Swal.fire({
            title: "Anda yakin?",
            text: $this.data('title'),
            icon: "warning",
            showDenyButton: true,
            confirmButtonText: "Oke",
            denyButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                // Ajax config
                ajaxSetup(route, formData);
                $.ajax({
                    success: function(result, status, xhr) {
                        $(checklist + ":checked").each(function() {
                            $('#tr_' + $(this).val()).remove();
                        });
                    },
                    complete: function(xhr, status) {
                        var result = eval("(" + xhr.responseText + ")");
                        Toast.fire({
                            icon: status,
                            title: result.msg,
                            timer: 3000
                        })
                    },
                });
            }
        });
    })

    ajaxList();
})

var Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timerProgressBar: true,
});

function ajaxSetup(route, data) {
    if (Array.isArray(data)) {
        //serialize data function
        var arr = {};
        for (var i = 0; i < data.length; i++) {
            arr[data[i]['name']] = data[i]['value'];
        }
        data = arr;
    }
    var formData = (data !== undefined) ? data : {};
    formData[cg.config.tokenName] = Cookies.get(cg.config.tokenCookie);

    $.ajaxSetup({
        type: "POST",
        url: site_url + route,
        data: formData,
        dataType: "json",
        // async: false,
        beforeSend: function(xhr) {},
        success: function(result, status, xhr) {},
        complete: function(xhr, status) {},
        error: function(xhr, status, error) {
            var result = eval("(" + xhr.responseText + ")");
            var title = (xhr.status == 404) ? result.msg : error;
            Toast.fire({
                icon: status,
                title: title,
                timer: 5000
            })
        }
    });
}

function ajaxList() {
    var $this = '#listdata';
    if ($($this).length == 0) {
        return false;
    }

    var route = $(".formsearch").attr('action');

    var formData = {};
    formData['filter'] = $(".filterdata").val();
    formData['keyword'] = $(".inputsearch").val();
    formData['sort'] = $(".sortdata").val();

    ajaxSetup(route, formData);
    $.ajax({
        beforeSend: function() {
            $($this).html('<div class="text-center">' +
                '<i class="fa fa-spinner fa-spin"></i>' +
                'Loading data...</div>');
        },
        success: function(result) {
            $($this).html(result.html)
        },
        error: function(xhr, status, error) {
            var result = eval("(" + xhr.responseText + ")");
            $($this).html(result.html)
        }
    });
}