var host = window.location.host;
// $BASE_URL = 'http://'+host+'/';  
$BASE_URL = 'http://localhost/angin/';  
function cek_data(){
      var
      $http,
      $self = arguments.callee;
      if (window.XMLHttpRequest) {
          $http = new XMLHttpRequest();
      }else if (window.ActiveXObject) {
          try {
              $http = new ActiveXObject('Msxml2.XMLHTTP');
          } catch(e) {
              $http = new ActiveXObject('Microsoft.XMLHTTP');
          }
      }
      if($http) {
          $http.onreadystatechange = function(){
              if (/4|^complete$/.test($http.readyState)) {
                setTimeout(function(){$self();}, 1000000);
              }
          };
          $http.open('GET', $BASE_URL+'dashboard/generate_cuaca' + '/' + new Date().getTime(), true);
          $http.send(null);
      }
}
function date_time(id){
    date = new Date;
    year = date.getFullYear();
    month = date.getMonth();
    months = new Array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    d = date.getDate();
    day = date.getDay();
    days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
    h = date.getHours();
    if(h<10){
        h = "0"+h;
    }
    m = date.getMinutes();
    if(m<10){
        m = "0"+m;
    }
    s = date.getSeconds();
    if(s<10){
        s = "0"+s;
    }
    result = ''+days[day]+', '+d+'-'+months[month]+'-'+year+' '+h+':'+m+':'+s;
    document.getElementById(id).innerHTML = result;
    setTimeout('date_time("'+id+'");','1000');
    return true;
}
function waktos(id){
    date = new Date;
    year = date.getFullYear();
    month = date.getMonth();
    months = new Array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    d = date.getDate();
    day = date.getDay();
    days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
    h = date.getHours();
    if(h<10){
        h = "0"+h;
    }
    m = date.getMinutes();
    if(m<10){
        m = "0"+m;
    }
    s = date.getSeconds();
    if(s<10){
        s = "0"+s;
    }
    result = ' '+h+':'+m+':'+s;
    document.getElementById(id).innerHTML = result;
    setTimeout('waktos("'+id+'");','1000');
    return true;
}
function kaping(id){
    date = new Date;
    year = date.getFullYear();
    month = date.getMonth();
    months = new Array('Januari', 'Pebruari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
    d = date.getDate();
    day = date.getDay();
    days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
    h = date.getHours();
    if(h<10){
        h = "0"+h;
    }
    m = date.getMinutes();
    if(m<10){
        m = "0"+m;
    }
    s = date.getSeconds();
    if(s<10){
        s = "0"+s;
    }
    result = ''+days[day]+', '+d+'-'+months[month]+'-'+year+'';
    document.getElementById(id).innerHTML = result;
    setTimeout('kaping("'+id+'");','1000');
    return true;
}
function hapus(id,page,link,action){
  bootbox.confirm("Yakin Akan Menghapus " +page+ " Berikut ?", function(result) {
    if (result) {
      setTimeout(function(){
        $.ajax({
          url : $BASE_URL+link+'/'+action+'/'+id,
          dataType : 'json',
          type : 'post',
          success : function(json) {
            $.unblockUI();
            if(json.say == "ok") {
              window.location.href = $BASE_URL+link;
            }else{
              $.gritter.add({title:"Informasi Penghapusan !",text: page+ " ini tidak bisa dihapus,terkait dengan database lain !"});return false;
            }
          }
        });       
      },1000);
    }
  });
}
function edit(id,page,link){
  bootbox.confirm("Yakin Akan Mengedit " +page+ " Berikut ?", function(result) {
    if (result) {
      $.blockUI({
          css: { 
              border: 'none', 
              padding: '15px', 
              backgroundColor: '#000', 
              '-webkit-border-radius': '10px', 
              '-moz-border-radius': '10px', 
              opacity: 2, 
              color: '#fff' 
          },
          message : 'Sedang Melakukan Pengecekan Data <br/> Mohon menunggu ... '
      });
      setTimeout(function(){
                $.unblockUI();
            },1000);
      $.ajax({
        url : $BASE_URL+link+'/cekdata/'+id,
        dataType : 'json',
        type : 'post',
        success : function(json) {
          $.unblockUI();
          if (json.say == "ok") {
            window.location.href = $BASE_URL+link+'/edit/'+id;
          }else{
            $.gritter.add({title:"Informasi Pengeditan !",text: page+ " ini tidak ditemukan di database !"});return false;
          }
        }
      });       
    }
  });
}
function detil(id,page,link){
  bootbox.confirm("Melihat Detil Potensi Energi Angin " +page+ " Berikut ?", function(result) {
    if (result) {
      $.blockUI({
          css: { 
              border: 'none', 
              padding: '15px', 
              backgroundColor: '#000', 
              '-webkit-border-radius': '10px', 
              '-moz-border-radius': '10px', 
              opacity: 2, 
              color: '#fff' 
          },
          message : 'Sedang Melakukan Pengecekan Data <br/> Mohon menunggu ... '
      });
      setTimeout(function(){
                $.unblockUI();
            },1000);
      $.ajax({
        url : $BASE_URL+link+'/cekdata/'+id,
        dataType : 'json',
        type : 'post',
        success : function(json) {
          $.unblockUI();
          if (json.say == "ok") {
            window.location.href = $BASE_URL+link+'/detil/'+id;
          }else{
            $.gritter.add({title:"Informasi Pengeditan !",text: page+ " ini tidak ditemukan di database !"});return false;
          }
        }
      });       
    }
  });
}