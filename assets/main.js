var url=window.location;$("ul.sidebar-menu a").filter(function(){return this.href==url}).parent().addClass("active"),$("ul.treeview-menu a").filter(function(){return this.href==url}).parentsUntil(".sidebar-menu > .treeview-menu").addClass("active"),$(".collapse.active").addClass("in"),$(document).ready(function(){$("#sidebar-filter").keyup(function(){var e=$(this).val(),t=0;no_col=0,$(".sidebar-menu > li > a").each(function(){var e=$(this).attr("href");"#"==e.substr(0,1)&&$(e).toggleClass("in")}),$(".sidebar-menu li").each(function(){0>$(this).text().search(RegExp(e,"i"))?$(this).fadeOut():($(this).show(),t++)})})});
$("ul.navbar-menu a").filter(function(){return this.href==url}).parent().addClass("active"),$("ul.dropdown-menu a").filter(function(){return this.href==url}).parentsUntil(".navbar-menu > .dropdown-menu").addClass("active"),$(document).ready(function(){$("#sidebar-filter").keyup(function(){var a=$(this).val(),n=0;no_col=0,$(".navbar-menu > li > a").each(function(){var a=$(this).attr("href");"#"==a.substr(0,1)&&$(a).toggleClass("in")}),$(".navbar-menu li").each(function(){0>$(this).text().search(RegExp(a,"i"))?$(this).fadeOut():($(this).show(),n++)})})});

function log_r(msg="", msg2=""){
  if (msg!="") { console.log(msg); }
  if (msg!="" && msg2!="") { console.log(msg, msg2); }
}

function get_token_csrf(requestType='')
{
  csrf = $('meta[name="csrf-token"]');
  return { 'X-CSRF-TOKEN': csrf.attr('content'), 'X-REQUEST-TYPE':requestType };
}

function up_token_csrf(token='')
{
  if (token!='') { $('meta[name="csrf-token"]').attr('content', token); }
}

$(document).ready(function () {
  setTimeout(function(){ swalLoading('close'); },50);
  window.onbeforeunload = function (e){ swalLoading('show'); }
  // Event listener untuk tombol back atau forward browser
  window.addEventListener('popstate', function(event) { swalLoading('close'); });
  // Event listener untuk menangani cache dan memastikan loading spinner disembunyikan
  window.addEventListener('pageshow', function(event) {
      // Jika halaman dimuat ulang dari cache, pastikan loading spinner disembunyikan
      if (event.persisted) { swalLoading('close'); }
  });

  if ($("input.float").length) {
    $("input.float").number(!0, 2, ",", ".");
  }
  $(document).on('click', 'a[href="#"]', function(event) {
      event.preventDefault(); // Menghentikan perilaku default
  });
});

showTooltip();
function showTooltip() {
  $('[data-toggle="tooltip"]').tooltip({ container: 'body' });
}

// Alert
function swalLoading(aksi='show') {
  if (aksi=='close') {
    swal.close();
    if ( $.isFunction($.fn.stopNotif) ) { startNotif(); }
  }else {
    if ($('.swal2-backdrop-show').length) { return true; }
    if ( $.isFunction($.fn.stopNotif) ) { stopNotif(); }
    return swal.fire({
          html: '<b style="color:#f1f1f1;font-size:20px">Loading . . .</b>',
          showConfirmButton: false, allowOutsideClick: false, allowEscapeKey:false,
          background: 'transparent', didOpen: () => { swal.showLoading() }
        });
  }
}

function responseSessionExpired(res_msg='') {
  if (res_msg=='Session Expired!') {
    if ( $.isFunction($.fn.stopNotif) ) { stopNotif(); }
    swal.fire({
            icon: 'error', title: res_msg, html: 'Please login again',
            showConfirmButton: false, allowOutsideClick: false,
            allowEscapeKey:false, timer: 3000,
          });
    setTimeout(function(){
      window.location.href = baseUrl();
    }, 2500);
  }
}

function swalResponse(res_alert='', res_msg='', showBtn=false, dTimeOut=3000) {
  if (res_msg=='Session Expired!') {
    responseSessionExpired(res_msg);
  }else {
    if ( $.isFunction($.fn.stopNotif) ) { startNotif(); }
    if (res_alert=='success') {
      if (showBtn) {
        return swal.fire( "Success!", res_msg, res_alert );
      }else {
        if (dTimeOut=='x') {
          return swal.fire({
                  icon: res_alert, title: "Success!", html: res_msg,
                  showConfirmButton: false, allowOutsideClick: false,
                  allowEscapeKey:false
                });
        }else {
          return swal.fire({
                  icon: res_alert, title: "Success!", html: res_msg,
                  showConfirmButton: false, allowOutsideClick: false,
                  allowEscapeKey:false, timer: dTimeOut,
                });
        }
      }
    }else {
      if (showBtn) {
        return swal.fire({
          icon: res_alert, title: "Oops!", html: res_msg,
          showConfirmButton: false, allowOutsideClick: false,
          allowEscapeKey:false, timer: dTimeOut,
        });
      }else {
        return swal.fire( "Oops!", res_msg, res_alert );
      }
    }
  }
}

// Validasi
function onValidUsername(nameId='username') {
  $('#'+nameId).val($('#'+nameId).val().replace(/[^a-zA-Z0-9]/g, ''));
}

function onValidNama(nameId='', only='') {
  names = $('#'+nameId);
  if (only=='abjad-space') {
    vals = names.val().replace(/[^a-z A-Z]/g, '');
  }else {
    vals = names.val().replace(/[^a-zA-Z 0-9]/g, '');
  }
  names.val(vals);
}

function onValidSpecial(nameId='') {
  $('#'+nameId).val($('#'+nameId).val().replace(/[^a-zA-Z 0-9 &_,-. +()\[\]\\]/g, ''));
}

function validateNumber(input) {
    input.value = input.value.replace(/[^0-9.]/g, '');
}

function validatePhone(input) {
    input.value = input.value.replace(/[^0-9+]/g, '');
}

function validateAbjad(input) {
    input.value = input.value.replace(/[^a-z A-Z ]/g, '');
}

// Form
function form_disabled(namenya='',ket='',stt='')
{
  if (stt=='all') {
    $("#"+namenya+" *").prop("disabled", ket);
  }else {
    $('[name="'+namenya+'"]').attr('disabled', ket);
  }
}

function select2_selected(name='', val='', val2='')
{
  $(name).val(val);
  nama = $(name+' :selected').text();
  if (val2=='') {
    $('.select2-selection__rendered').attr('title', nama);
    $('.select2-selection__rendered').html(nama);
  }else {
    $('.'+val2+' .select2-selection__rendered').attr('title', nama);
    $('.'+val2+' .select2-selection__rendered').html(nama);
  }
}

function showPwd(i='') {
  password = $(`[name="password${i}"]`);
  btnShowPwd = $(`.btnShowPwd${i}`);
  icon = $(`.iconPwd${i}`);
  icon.removeClass('glyphicon-eye-open');
  icon.removeClass('glyphicon-eye-close');
  if (password.attr('type') == 'text') {
    btnShowPwd.attr('title', 'Show Password');
    password.attr('type', 'password');
    icon.addClass('glyphicon-eye-open');
  }else {
    btnShowPwd.attr('title', 'Hide Password');
    password.attr('type', 'text');
    icon.addClass('glyphicon-eye-close');
  }
  password.focus();
}

function prosesSubmit({
  csrf=false, method="GET", form='', url='', fd, redirect='',
  processData=false, contentType=false, cache=false, loading=true,
  callbackResponse=null, callbackSuccess=null, callbackFailed=null, callbackError=null,
}) {
  if (loading) { if ( $.isFunction(window.swalLoading) ) { swalLoading(); } }
  if (form!='') { form_disabled(form, true, 'all'); }
  fd = (fd) ? fd:'';
  $.ajax({
    type: method,
    url : url,
    data: fd,
    dataType: "json",
		cache: cache,
    headers: (csrf) ? get_token_csrf():'',
    processData: processData,
    contentType: contentType,
    beforeSend: function(){ },
    success: function( data ) {
      if (csrf) { up_token_csrf(data.token); }
      if (callbackResponse !== null) {
        if (loading) { swalLoading('close'); } if (form!='') { form_disabled(form, false, 'all'); }
        return callbackResponse(data);
      }
      if (data.status || data.status==1) {
        if (callbackSuccess !== null) {
          if (loading) { swalLoading('close'); } if (form!='') { form_disabled(form, false, 'all'); }
          return callbackSuccess(data);
        }

        swalResponse('success', data.message, false, 'x');
        if (data && data.data && data.data.redirect) {
          setTimeout(function(){
            window.location.href = data.data.redirect;
          }, 2500);
        }else {
          if (redirect=='clear') {
            $('#'+form+' *').each(function(key, field) {
              var field_name = field.name;
              if ($('[name="'+field_name+'"]').length!=0) {
                $('[name="'+field_name+'"]').val('');
              }
            });
            if (form!='') { form_disabled(form, false, 'all'); }
            if (loading) { setTimeout(function(){ swalLoading('close'); }, 2500); }
          }else {
            setTimeout(function(){
              if (data.redirect) { window.location.href = data.redirect; }
              if (redirect) { window.location.href = redirect; }
              else { onRefresh(); }
            }, 2500);
          }
        }
      }else {
        if (callbackFailed !== null) {
          swalLoading('close'); if (form!='') { form_disabled(form, false, 'all'); }
          return callbackFailed(data);
        }
        get_pesan = (data.message=='') ? 'Fail! An error occurred, please try again!':data.message;
        swalResponse('warning', get_pesan);
        if (form!='') { form_disabled(form, false, 'all'); }
      }
    },
    error: function(error) {
      if (callbackError !== null) {
        swalLoading('close'); if (form!='') { form_disabled(form, false, 'all'); }
        return callbackError(error);
      }
      if (error.responseJSON && error.responseJSON.message) {
        swalResponse('error', error.responseJSON.message.toString());
      }else {
        if (error.status == 401) { window.location.href = baseUrl('login'); }
        swalResponse('error', 'Error! There was an error, please try again!');
      }
      if (form!='') { form_disabled(form, false, 'all'); }
    }
  });
}


async function prosesSubmitConfirm({
    title='Are you sure ?', html='', icon='info',
    textBtnOK='Yes', textBtnCancel='Check again', send=null
}) {
  Swal.fire({
    title: title,
    html: html,
    icon: icon,
    showCancelButton: true,
    cancelButtonColor: '#d33',
    confirmButtonText: textBtnOK,
    cancelButtonText: textBtnCancel,
  }).then((result) => {
    if (result.isConfirmed) {
      if (send !== null) {
        return prosesSubmit(send);
      }
    }
  })
}


function prosesHTML({
  csrf=false, method="GET", form='viewForm', url='', data='', loading=true,
  callbackResponse=null, callbackError=null,
}) {
  if (loading) { swalLoading(); }
  $.ajax({
    type:'POST', url:url,
    data:data,
    headers:(csrf) ? get_token_csrf():'',
    success: function(response) {
      if (loading) { swalLoading('close'); }
      if (callbackResponse !== null) {
        return callbackResponse(response);
      }
      $('#'+form).html(response);
    },
    error: function(error) {
      if (callbackError !== null) {
        if (loading) { swalLoading('close'); }
        return callbackError(data);
      }
      if (error.responseJSON && error.responseJSON.message) {
        swalResponse('error', error.responseJSON.message.toString());
      }else {
        if (error.status == 401) { window.location.href = baseUrl('login'); }
        swalResponse('error', 'Error! There was an error, please try again!');
      }
    }
  });
}

function onLogout(ket='') {
  Swal.fire({
    title: 'Are you sure ?',
    html: `Logout of Account ${ket}`,
    icon: 'info',
    showCancelButton: true,
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes',
    cancelButtonText: 'No',
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = baseUrl(ket=='' ? 'logout':'logout?redirect='+ket);
    }
  })
}

// Datatable
function handleAjaxError(xhr, textStatus, error) {
    if (error=='Unauthorized') {
      setTimeout(function(){
        swalResponse('warning', 'Session Expired!');
      }, 1000);
      setTimeout(function(){
        window.location.href = baseUrl('login');
      }, 3000);
    }else
    if (xhr.responseText=='Permission Denied!!') {
      setTimeout(function(){
        swalResponse('warning', xhr.responseText);
      }, 1000);
      setTimeout(function(){
        window.location.href = baseUrl();
      }, 3000);
    }else
    if (xhr.responseText=='Session Expired!') {
      setTimeout(function(){
        responseSessionExpired(xhr.responseText);
      }, 1000);
    }else
    if (textStatus === 'timeout') {
      setTimeout(function(){
        swalResponse('error', 'The server takes too long to send data. Please try again!');
      }, 1000);
    }else {
      if (xhr.responseText=='') {
        setTimeout(function(){
          swalResponse('error', 'An error occurred on the server. Please try in a few minutes!');
        }, 1000);
      }else{
        setTimeout(function(){
          swalResponse('warning', xhr.responseText);
        }, 1000);
        setTimeout(function(){
          onRefresh();
        }, 3000);
      }
    }
    console.log(xhr.responseText);
    console.log(error);
}

function prosesDatatable({
  method="GET", name='fileData', url='', dom='', csrf=true,
  cache=false, actionAlign=null, actions=null, order=null,
  autoNumber=true, columns=null, columnDefs=null, columnHeads=null, columnWidths=null,
  columnHeader=null, columnFooter=null, footerCallback=null
}) {

  if (columnHeader !== null) {
    $(`#${name}`).html(columnHeader);
  }else {
    if (!$(`#${name} > thead > tr`).length) {
      $(`#${name}`).html(`<thead><tr></tr></thead>`);
    }
  }
  // Sesuaikan Column
  columnsCustom=[]; columnDefsCustom=[];
  if (autoNumber) {
    if (!$(`#${name} > thead > tr th#autoNumber`).length) {
      $(`#${name} > thead > tr`).append(`<th width="1%" id="autoNumber">#</th>`);
    }
    columnsCustom.push({ data: null });
    columnDefsCustom.push({
      orderable:false, searchable:false, className:"text-center align-top", targets: 0,
      render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
      }
    })
  }
  if (columns !== null) {
    $.each( columns, function( key, value ) {
      setValHead = (columnHeads !== null && columnHeads[key]) ? columnHeads[key] : value;
      setWidth = (columnWidths  !== null && columnWidths[key]) ? `${columnWidths[key]}%` : 'auto';
      if (columnHeader == null) {
        if (!$(`#${name} > thead > tr th#head${key}`).length) {
          $(`#${name} > thead > tr`).append(`<th width="${setWidth}" id="head${key}">${setValHead}</th>`);
        }else{
          $(`#${name} > thead > tr th#head${key}`).html(setValHead);
        }
      }
      columnsCustom.push({ data: value });
    });
  }

  actionAlign = (actionAlign==null) ? 'center':actionAlign;
  if (actions !== null) {
    if (!$(`#${name} > thead > tr th#btnAksi`).length) {
      $(`#${name} > thead > tr`).append(`<th width="9%" id="btnAksi">Action</th>`);
    }
    targetCustom = columnsCustom.length;
    columnsCustom.push({ data: null });
    columnDefsCustom.push({
      orderable:false, searchable:false, className:"text-"+actionAlign+" align-top", targets: targetCustom,
      render: actions
    })
  }
  if (columnDefs !== null) {
    $.each( columnDefs, function( key, value ) { columnDefsCustom.push(value); });
  }

  if (columnFooter !== null) {
    $(`#${name}`).append(columnFooter);
  }

  var orderIdx = (autoNumber) ? 1:0;

  swalLoading();
  var oTable = $('#'+name).DataTable({
      lengthMenu: [[ 10, 25, 50 , 100, -1], [ 10, 25, 50, 100, "All"]],
      scrollX: true,
      processing: true,
      serverSide: true,
      destroy: true,
      ajax: {
          url: url,
          headers:(csrf) ? get_token_csrf('dataTables'):'',
          method: method,
          cache: cache,
          error: handleAjaxError
      },
      fnDrawCallback: function (oSettings) {
        if (oSettings.json.data[0] && oSettings.json.data[0].no == 'x') {
          window.location.href = baseUrl();
        }
        swalLoading('close');
      },
      columns: columnsCustom,
      order: (order) ? order:[[orderIdx, 'asc']],
      columnDefs: columnDefsCustom,
      footerCallback: footerCallback
  });
  setTimeout(function() { showTooltip(); }, 500);
}

// Custom
function baseUrl(url='') {
  if (BASEURL) {
    rootBaseUrl = BASEURL;
  }else {
    var rootBaseUrl = $('head > base').attr('href');
    if (!rootBaseUrl) {
      rootBaseUrl = window.location.origin+'/';
      if (url=='') return rootBaseUrl;
    }
  }
  return `${rootBaseUrl}${url}`;
}
function onRefresh() { window.location.reload(); }

function readURLImg(input) {
    var imgView = $(input).data('img_view');
    var imgDefault = $(input).data('img_default');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+imgView).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }else {
      $('#'+imgView).attr('src', imgDefault);
    }
}

function format_tglnya(tglnya='', formatnya='')
{
  if (tglnya=='') { return ''; }
  var $harinya = ['Minggu','Senin','Selasa','Rabu','Kamis',"Jum'at",'Sabtu'];
  var $blnnya  = [ 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  var $blnnya2  = [ 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agst', 'Sep', 'Okt', 'Nov', 'Des'];
  var formattedDate = new Date(tglnya);
  var d = formattedDate.getDate();
  var d = (d < 10) ? '0'+d:d;
  var day = formattedDate.getDay();
  var m = formattedDate.getMonth();
  var y = formattedDate.getFullYear();
  var h = formattedDate.getHours();
  h = (h < 10) ? '0'+h:h;
  var i = formattedDate.getMinutes();
  i = (i < 10) ? '0'+i:i;
  var s = formattedDate.getSeconds();
  s = (s < 10) ? '0'+s:s;
  if (formatnya=='d m Y') {
     return d + " " + $blnnya[m] + " " + y;
  }else if (formatnya=='d mmm Y') {
     return d + " " + $blnnya2[m] + " " + y;
  }else if (formatnya=='dd-mm-yyyy') {
     m++;
     var m = (m < 10) ? '0'+m:m;
     return d + "-" + m + "-" + y;
  }else if (formatnya=='waktu') {
     return $harinya[day] +", " + d + " " + $blnnya[m] + " " + y + " " + h + ":" + i;
  }else if (formatnya=='jam') {
     return h + ":" + i;
  }else if (formatnya=='d mmm Y H:i:s') {
     return d + " " + $blnnya2[m] + " " + y + " " + h + ":" + i + ":" +s;
  }else{
     return $harinya[day] +", " + d + " " + $blnnya[m] + " " + y;
  }
}

function convertTZ(date, tzString='') {
    tzString = (tzString=='') ? "Asia/Jakarta":tzString;
    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));
}

function formatRupiah(val='', rp='')
{
  if (val!='') {
    tag = $('[name="'+val+'"]');
    if (tag.length!=0) {
      get = get_formatRupiah(tag.val(), rp);
      tag.val(get);
    }
  }
}
//Fungsi formatRupiah
function get_formatRupiah(angka=0, prefix=''){
  var number_string = angka.replace(/[^,\d]/g, '').toString(),
  split   = number_string.split(','),
  sisa    = split[0].length % 3,
  rupiah  = split[0].substr(0, sisa),
  ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
}

function hanyaAngka(evt) {
  var charCode = (evt.which) ? evt.which : event.keyCode
   if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
  return true;
}

function formatTGL(inputDate='', format='Y-m-d')
{
  if (inputDate=='') { return false; }
  if (format=='Y-m-d') {
    var newdate = inputDate.split("-").reverse().join("-");
  }else {
    var newdate = inputDate;
  }
  const date = new Date(newdate);
  var day = date.getDate();
  var month = date.getMonth() + 1;
  const year = date.getFullYear();
  if (day < 10) { var day = "0"+day; }
  if (month < 10) { var month = "0"+month; }
  if (format=='Y-m-d') {
    return year+'-'+month+'-'+day;
  }else if (format=='d-m-Y') {
    return day+'-'+month+'-'+year;
  }
}

$(function () {
  $('body').on('hidden.bs.modal', function (e) {
    $('body').css('padding-right', '0px');
    $('#modalForm').html('');
  });
  
  if ($('form').length && $('form').data('parsley-validate')) {
    $('form').parsley().on('field:validated', function() {
      if ($('.parsley-error').length === 0) {
        var idError = $(this)[0].domOptions.errorsContainer;
        $(idError).html('');
      }
    });
  }
});

function image_error(field) {
  img = 'img/null.png';
  $(field).attr('src', img);
}

function trimNumber(s) {
  s = s.toString().replace(/[^0-9]/g, '');
  while (s.substr(0,1) == '0' && s.length>1) { s = s.substr(1,9999); }
  return s;
}

$('select').on('change', function() {
  id = $(this).data('parsley-id');
  if ($(this).val()!='') {
    if ($('#parsley-id-'+id).length) {
      $('#parsley-id-'+id).remove();
    }
  }
});


function checkURL404(url, attrID) {
  $.ajax({
    url: url,
    type: 'GET',
    success: function (data, status, xhr) {
      // console.log('Success :', xhr);
      if (xhr.status === 200) {
        $('#'+attrID).show();
      }else {
        $('#'+attrID).hide();
      }
    },
    error: function (xhr) {
      // console.log('Error :', xhr);
      if (xhr.status === 404) {
        $('#'+attrID).hide();
      } else {
        console.log('Error: ' + xhr.status);
        $('#'+attrID).show();
      }
    }
  });
}


function initializeDateRangePicker(startId, endId, edit=false) {
  var startDatePicker = UIkit.datepicker('#' + startId, { format: 'DD-MM-YYYY' });
  var endDatePicker = UIkit.datepicker('#' + endId, { format: 'DD-MM-YYYY' });
  if (edit) {
    endDatePicker.options.minDate = $('#'+startId).val();
    endDatePicker.update();
  }

  startDatePicker.on('change', function() {
    // Mendapatkan nilai Start Date
    var startDate = $('#'+startId).val();
    var endDate = $('#'+endId).val();

    // Memperbarui batasan maksimal untuk End Date
    endDatePicker.options.minDate = startDate;
    endDatePicker.update();

    // Jika End Date lebih kecil dari Start Date, atur ulang End Date
    var parts = startDate.split('-');
    var tanggal1Ymd = new Date(parts[2] + '-' + parts[1] + '-' + parts[0]);
    var parts = endDate.split('-');
    var tanggal2Ymd = new Date(parts[2] + '-' + parts[1] + '-' + parts[0]);
    if (tanggal2Ymd < tanggal1Ymd) {
      $('#'+endId).val(startDate);
    }
    $('#'+endId).removeAttr('disabled');
  });
}


async function postSubmit(form='formSubmit', url='', redirect='')
{
  var fd = new FormData();
  $('#'+form+' *').each(function(key, field) {
    var field_name = field.name;
    var field_type = field.type;
    if (field_type === 'file') {
      if ($('form#'+form+' [name="'+field_name+'"]').val() !== '') {
        fd.append(field_name, $('input[name='+field_name+']')[0].files[0]);
      }
    }else{
      if ($('form#'+form+' [name="'+field_name+'"]').length!=0) {
        if ($('form#'+form+' [name="'+field_name+'"] required').val() == '') {
          return false;
        }
        fd.append(field_name, $('form#'+form+' [name="'+field_name+'"]').val());
      }
    }
  });
  await prosesSubmit({
    csrf: true,
    method:"POST", form:form,
    url:url, fd:fd,
    redirect:redirect
  });
}

function hitungUmur(tanggalLahir='') {
    if (tanggalLahir=='') { return '0'; }
    var hariIni = new Date();
    var lahir = new Date(tanggalLahir);
    var umur = hariIni.getFullYear() - lahir.getFullYear();
    var m = hariIni.getMonth() - lahir.getMonth();
    // Jika bulan saat ini lebih kecil dari bulan lahir,
    // atau kita berada di bulan yang sama tetapi tanggalnya lebih kecil,
    // maka belum berulang tahun.
    if (m < 0 || (m === 0 && hariIni.getDate() < lahir.getDate())) {
        umur--;
    }
    return umur;
}
