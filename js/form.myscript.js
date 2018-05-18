var list_dlt = [];
var tarrName = [];
var tarrVal = [];

function showLabelFile() {
    list_dlt = [];
    var len = document.getElementById('uploadFile').files.length;
    var res = '';
    for (var i = 0; i < len; i++) {
        res += "<div style='margin-bottom:5px;display:inline-block'>" +
            "<span class='label label-info label-tag' id='upload-file-info" + i + "'>" + $('#uploadFile').get(0).files[i].name + "<i id='icon-remove' style='margin-left:4px' onClick='removefile(" + i + ")' title='ลบ' class='fa fa-times-circle'></i></span>" +
            "</div>";
    }
    $('#tagFile').html(res);
    $('#list-dlt').html('');
}

function removefile(order) {
    var htmlid = '#upload-file-info' + order;
    $(htmlid).hide();
    var len = list_dlt.length;
    list_dlt[len] = order;
    $('#list-dlt').html("<input type='hidden' name='dltFile' value='" + list_dlt + "'>");
};


function printErrorMsg(msg) {
    $(".print-error-msg").find("ul").html('');
    $(".print-error-msg").css('display', 'block');
    $.each(msg, function(key, value) {
        $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
    });
}

function resetForm() {
    $('#tagFile').html("");
    document.getElementById('uploadFile').value = "";
    list_dlt = [];
    tarrName = [];
    tarrVal = [];

}

function resetEditForm() {
    $('#tagFile').html("");
    $('#tagFile').hide();
    document.getElementById('uploadFile').value = "";
    list_dlt = [];
    $('#oldTagFile').show();
    $('#btn-file').hide();
    $('#chkchange').html('');
}

function refresh() {
    window.location.href = window.location.pathname
}

function delAlbum() {
    $('#changeAlbum').val('yes')
    $('#div-album').hide()
    $('#div-filealbum').show()
}

function handleFiles(files){
    var isImg = true
    for (var m = 0; m < files.length; m++){
      if(!isImage(files[m])) {
        isImg = false
        break
      }
    }
    if(!isImg){
      $('#uploadFile').val('');
      $('#alert-image-album').show()
      $('#alert-image-album').html('เลือกรูปภาพเท่านั้น')
      $('#tagFile').hide();
      //console.log(files)
    }
    else {
      $('#alert-image-album').hide()
      $('#tagFile').show();
      labelAlbum()
    }
  }
  function isImage(file){
    return file['type'].split('/')[0]=='image';//returns true or false
 }


 

function resetForm() {
      $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        todayBtn: true,
        language: 'th',             //เปลี่ยน label ต่างของ ปฏิทิน ให้เป็น ภาษาไทย   (ต้องใช้ไฟล์ bootstrap-datepicker.th.min.js นี้ด้วย)
        thaiyear: true              //Set เป็นปี พ.ศ.
    }).datepicker("setDate", "0"); 
}
    
function checkDuplicate(newVal, arrVal) {
    for (var m = 0; m < arrVal.length; m++)
        if (newVal == arrVal[m]) return false;
    return true;
}

function setTch() {
    var resShow = "",
        hiddenText = "";
    for (var i = 0; i < tarrName.length; i++) {
        resShow += "<div class='col-sm-6'><li id='tagTch" + i + "'>" + (i + 1) + ".  " + this.tarrName[i] + "</li></div><div class='col-sm-1'><i id='icon-remove' onclick='removeTch(" + i + ")' class='fa fa-times-circle'></i></div>";
    }
    $('#teacher').html(resShow);
    hiddenText = "<input type='hidden' name='coTeacher' value='" + this.tarrVal + "'>";
    $('#tch').html(hiddenText);
}

function removeTch(index) {
    tarrName.splice(index, 1);
    tarrVal.splice(index, 1);
    if(tarrVal.length==0) $('#divTeacher').hide() 
    $('#tagTch' + index).hide();
    setTch();
}

function showSelect() {
    $('#divTeacher').show()
    var e = document.getElementById("mySLT");
    var value = e.options[e.selectedIndex].value;
    var text = e.options[e.selectedIndex].text;
    if (checkDuplicate(value, tarrVal)) {
        tarrVal[tarrVal.length] = value;
        tarrName[tarrName.length] = text;
    }
    setTch();
}