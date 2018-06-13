$('#confirmDeleteModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var id = button.data('id'); // Extract info from data-* attributes
    var name = button.data('name');
    var modal = $(this)
    modal.find('#msg').html(
        'คุณต้องการลบ <b>' + name + "</b> หรือไม่ ?<br>" +
        "<div style='margin-top:30px;color:red'><b><i style='color:#ffcc00;' class='fa fa-exclamation-triangle' aria-hidden='true'></i>&nbsp &nbsp หมายเหตุ</b> &nbsp การลบจะทำให้เอกสารที่อยู่บนไดร์ฟถูกลบด้วย</div>"
    );
    $('#btn-confirm').click(function() {
        window.location.href = window.location.href + "/delete/" + id;
    });
});

$('#confirmDeleteModal2').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var id = button.data('id'); // Extract info from data-* attributes
    var name = button.data('name');
    var modal = $(this)
    modal.find('#msg').html(
        'คุณต้องการลบ <b>' + name + "</b> หรือไม่ ?<br>"
    );
    $('#btn-confirm').click(function() {
        window.location.href = window.location.href + "/delete/" + id;
    });
});

$('#viewModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var id = button.data('id'); // Extract info from data-* attributes
    var modal = $(this)
    $.ajax({
        url: window.location.pathname + "/view/" + id,
        type: 'GET',
        dataType: 'JSON',
        data: { id: button.data('id') },
        success: function(data) {
            modal.find('#msg').html(data.html);
        }
    });
});

$('#viewAllfileModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var dSt = button.data('datest'); // Extract info from data-* attributes
    var dEnd = button.data('dateend');
    var path = button.data('path');
    var token = button.data('token');
    // console.log(dSt)
    //console.log(dEnd)
    //console.log(path)
    // console.log(token)
    var modal = $(this)
    $.ajax({
        url: path,
        type: 'POST',
        dataType: 'JSON',
        data: { _token: token, dateStart: dSt, dateEnd: dEnd },
        success: function(data) {
            modal.find('#msg').html(data.html);
        }
    });
});

$('#viewfileModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var path = button.data('path');
    var token = button.data('token');
    var tname = button.data('tname');
    var modal = $(this)
    $.ajax({
        url: path,
        type: 'POST',
        dataType: 'JSON',
        data: { _token: token },
        success: function (data) {
            modal.find('#exampleModalLabel').html(tname);
            modal.find('#msg').html(data.html);
        }
    });
});


/*$('#viewfileModal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var tid = button.data('tid'); // Extract info from data-* attributes
    var modal = $(this)
    $.ajax({
        url: "{{url('')}}/" + tid + "/file",
        type: 'POST',
        dataType: 'JSON',
        data: { _token: "{{ csrf_token() }}", id: button.data('id') },
        success: function(data) {
            modal.find('#msg').html(data.html);
        }
    });
});*/

function refresh() {
    window.location.href = window.location.pathname
}

function openAllFile(count) {
    for (var i = 0; i < count; i++) {
        var htmlid = "#showfile" + i;
        $(htmlid).click();
    }

}