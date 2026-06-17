@extends('layouts.apps')
@section('content')
<style>
.scanner-area{min-height:400px;background:#f7f9fc;border:2px dashed #cbd5e1;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;overflow:hidden;position:relative}
.scanner-area.active{border-color:#4b49ac;background:#f0f0ff}
.scanner-area video,.scanner-area canvas{width:100%!important;height:400px!important;object-fit:cover!important;border-radius:10px}
#qr-reader video{width:100%!important;height:400px!important;object-fit:cover!important}
#qr-reader img[alt="QR frame"]{width:240px!important;height:240px!important;opacity:0.8!important}
#qr-reader__scan_region{background:transparent!important}
#qr-reader__dashboard{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,0.5);padding:8px}
@media (max-width:576px){
  .scanner-area,.scanner-area video,#qr-reader video{height:320px!important}
}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.info-item{background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:10px 12px}
.info-item label{display:block;font-size:11px;text-transform:uppercase;color:#64748b;font-weight:600;margin-bottom:2px}
.info-item .value{font-size:15px;font-weight:700;color:#1f2937}
.status-badge{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-weight:700;font-size:16px}
.status-badge.diterima{background:#dcfce7;color:#166534}
.status-badge.ditolak{background:#fee2e2;color:#991b1b}
.section-label{font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:#64748b;font-weight:700;margin-bottom:10px}
</style>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-crosshairs-gps"></i>
        </span> Kunjungan Toko
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('toko.index') }}">Toko</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kunjungan</li>
        </ul>
    </nav>
</div>

<div id="alertArea"></div>

<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="section-label mb-0">Daftar Toko</p>
                <p class="text-muted mb-0 small">Klik salah satu toko untuk langsung mengisi data.</p>
            </div>
            <a href="{{ route('toko.create') }}" class="btn btn-sm btn-primary">
                <i class="mdi mdi-plus me-1"></i> Tambah Toko
            </a>
        </div>
        <div class="row g-2" id="tokoListContainer">
            <div class="col-12 text-center text-muted py-3">
                <span class="spinner-border spinner-border-sm me-1"></span> Memuat daftar toko...
            </div>
        </div>
    </div>
</div>

<div class="row g-4">

    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="section-label mb-0">Scan QR Code Toko</p>
                        <p class="text-muted mb-0 small">Arahkan kamera ke QR code toko.</p>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" id="startCameraBtn">
                        <i class="mdi mdi-qrcode-scan me-1"></i> Mulai Scan
                    </button>
                </div>
                <div id="scanner-area" class="scanner-area mb-3" style="min-height:400px">
                    <div id="scanner-placeholder" class="text-center text-muted">
                        <i class="mdi mdi-qrcode-scan" style="font-size:4rem"></i>
                        <div class="fw-semibold mt-2">Area Scanner</div>
                        <small>Klik "Mulai Scan" untuk memulai</small>
                    </div>
                    <div id="qr-reader" style="width:100%;display:none"></div>
                </div>
                <div class="alert alert-light border mb-0">
                    <small class="text-muted d-block mb-1">QR Terdeteksi</small>
                    <strong id="detectedBarcode">-</strong>
                </div>
                <div id="scannerFlash" class="mt-2 text-center" style="display:none">
                    <span class="badge bg-success px-3 py-2">
                        <i class="mdi mdi-flash mdi-spin"></i> Scanner Aktif — Arahkan QR Code
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">

        <div class="card mb-3" id="tokoInfoCard" style="display:none">
            <div class="card-body">
                <p class="section-label">Informasi Toko</p>
                <div class="info-grid">
                    <div class="info-item"><label>Barcode</label><div class="value" id="tokoBarcode">-</div></div>
                    <div class="info-item"><label>Nama Toko</label><div class="value" id="tokoNama">-</div></div>
                    <div class="info-item"><label>Latitude Toko</label><div class="value" id="tokoLat">-</div></div>
                    <div class="info-item"><label>Longitude Toko</label><div class="value" id="tokoLng">-</div></div>
                    <div class="info-item"><label>Accuracy Toko</label><div class="value" id="tokoAccuracy">-</div></div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="salesLocationCard" style="display:none">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <p class="section-label mb-0">Lokasi Sales</p>
                        <p class="text-muted mb-0 small">Ambil posisi GPS perangkat.</p>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="getLocationBtn">
                        <i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi
                    </button>
                </div>
                <div class="info-grid">
                    <div class="info-item"><label>Latitude Sales</label><div class="value" id="salesLat">-</div></div>
                    <div class="info-item"><label>Longitude Sales</label><div class="value" id="salesLng">-</div></div>
                    <div class="info-item"><label>Accuracy Sales</label><div class="value" id="salesAccuracy">-</div></div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="validationCard" style="display:none">
            <div class="card-body">
                <p class="section-label">Validasi Jarak</p>
                <div class="info-grid">
                    <div class="info-item"><label>Jarak Aktual</label><div class="value" id="jarakAktual">-</div></div>
                    <div class="info-item"><label>Threshold Base</label><div class="value" id="thresholdBase">-</div></div>
                    <div class="info-item"><label>Accuracy Toko</label><div class="value" id="accTokoDisplay">-</div></div>
                    <div class="info-item"><label>Accuracy Sales</label><div class="value" id="accSalesDisplay">-</div></div>
                    <div class="info-item"><label>Threshold Efektif</label><div class="value" id="thresholdEfektif">-</div></div>
                </div>
            </div>
        </div>

        <div class="card mb-3" id="statusCard" style="display:none">
            <div class="card-body text-center">
                <p class="section-label">Status Kunjungan</p>
                <div id="statusBadge" class="status-badge mb-3">-</div>
                <div id="statusMessage" class="text-muted small mb-0">-</div>
            </div>
        </div>

        <div id="submitSection" style="display:none">
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary" id="submitKunjunganBtn">
                    <i class="mdi mdi-send me-1"></i> Submit Kunjungan
                </button>
                <button type="button" class="btn btn-outline-secondary" id="resetAllBtn">
                    <i class="mdi mdi-refresh me-1"></i> Reset
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
var THRESHOLD_BASE=300;
var state={toko:null,salesLocation:null,jarak:null,thresholdEfektif:null,status:null,scanner:null,isScanning:false};

function haversine(lat1,lon1,lat2,lon2){
  var R=6371000;
  var d2r=function(d){return d*Math.PI/180;};
  var dLat=d2r(lat2-lat1);
  var dLon=d2r(lon2-lon1);
  var a=Math.sin(dLat/2)*Math.sin(dLat/2)+Math.cos(d2r(lat1))*Math.cos(d2r(lat2))*Math.sin(dLon/2)*Math.sin(dLon/2);
  return R*2*Math.atan2(Math.sqrt(a),Math.sqrt(1-a));
}

function showAlert(type,msg){
  var area=document.getElementById('alertArea');
  var icon=type==='success'?'check-circle':type==='danger'?'alert-circle':'info';
  area.innerHTML='<div class="alert alert-'+type+' d-flex align-items-center gap-2"><i class="mdi mdi-'+icon+'"></i> '+msg+'</div>';
  setTimeout(function(){area.innerHTML='';},6000);
}

function hideAllCards(){
  var ids=['tokoInfoCard','salesLocationCard','validationCard','statusCard'];
  for(var i=0;i<ids.length;i++){
    var el=document.getElementById(ids[i]);
    if(el)el.style.display='none';
  }
  var s=document.getElementById('submitSection');
  if(s)s.style.display='none';
  var b=document.getElementById('statusBadge');
  if(b){b.className='status-badge mb-3';b.textContent='-';}
  var m=document.getElementById('statusMessage');
  if(m)m.textContent='-';
}

function resetAll(){
  if(state.scanner&&state.isScanning){
    state.scanner.stop().then(function(){
      state.isScanning=false;
      state={toko:null,salesLocation:null,jarak:null,thresholdEfektif:null,status:null,scanner:null,isScanning:false};
      document.getElementById('detectedBarcode').textContent='-';
      hideAllCards();
      document.getElementById('alertArea').innerHTML='';
      document.getElementById('scannerFlash').style.display='none';
      document.getElementById('startCameraBtn').innerHTML='<i class="mdi mdi-qrcode-scan me-1"></i> Mulai Scan';
    });
  } else {
    state={toko:null,salesLocation:null,jarak:null,thresholdEfektif:null,status:null,scanner:null,isScanning:false};
    document.getElementById('detectedBarcode').textContent='-';
    hideAllCards();
    document.getElementById('alertArea').innerHTML='';
    document.getElementById('scannerFlash').style.display='none';
    document.getElementById('startCameraBtn').innerHTML='<i class="mdi mdi-qrcode-scan me-1"></i> Mulai Scan';
  }
}

function loadTokoList(){
  var xhr=new XMLHttpRequest();
  xhr.open('GET',"{{ route('Geotag.tokos') }}",true);
  xhr.onload=function(){
    try{
      var json=JSON.parse(xhr.responseText);
      var c=document.getElementById('tokoListContainer');
      if(!json.status||!json.data||json.data.length===0){
        c.innerHTML='<div class="col-12 text-center text-muted py-3">Belum ada data toko. <a href="{{ route('toko.create') }}">Tambah toko</a></div>';
        return;
      }
      var html='';
      for(var i=0;i<json.data.length;i++){
        var t=json.data[i];
        html+='<div class="col-md-4 col-lg-3">'+
          '<div class="border rounded p-2 text-center" style="cursor:pointer;transition:all .2s" '+
          'onclick="selectTokoByBarcode(\''+t.barcode+'\')" '+
          'onmouseover="this.classList.add(\'bg-light\')" '+
          'onmouseout="this.classList.remove(\'bg-light\')">'+
          '<div class="fw-bold text-dark mb-1" style="font-size:13px">'+t.nama_toko+'</div>'+
          '<div class="badge bg-secondary mb-1" style="font-size:12px">'+t.barcode+'</div>'+
          '<div class="small text-muted">Lat: '+t.latitude+'<br>Lng: '+t.longitude+'</div>'+
          '</div></div>';
      }
      c.innerHTML=html;
    }catch(e){
      document.getElementById('tokoListContainer').innerHTML='<div class="col-12 text-center text-danger py-3">Gagal memuat daftar toko.</div>';
    }
  };
  xhr.onerror=function(){
    document.getElementById('tokoListContainer').innerHTML='<div class="col-12 text-center text-danger py-3">Gagal memuat daftar toko.</div>';
  };
  xhr.send();
}

function fetchToko(barcode,cb){
  var xhr=new XMLHttpRequest();
  xhr.open('GET',"{{ route('Geotag.search') }}?barcode="+encodeURIComponent(barcode),true);
  xhr.setRequestHeader('Accept','application/json');
  xhr.onload=function(){
    try{
      var json=JSON.parse(xhr.responseText);
      if(!json.status){showAlert('danger',json.message||'Toko tidak ditemukan.');cb(null);return;}
      cb(json.data);
    }catch(e){showAlert('danger','Gagal mengambil data dari server.');cb(null);}
  };
  xhr.onerror=function(){showAlert('danger','Gagal mengambil data dari server.');cb(null);};
  xhr.send();
}

window.selectTokoByBarcode=function(barcode){
  document.getElementById('detectedBarcode').textContent=barcode;
  // Clear previous sales data
  document.getElementById('salesLat').textContent='-';
  document.getElementById('salesLng').textContent='-';
  document.getElementById('salesAccuracy').textContent='-';
  hideAllCards();
  fetchToko(barcode,function(toko){
    if(!toko){document.getElementById('detectedBarcode').textContent='-';return;}
    state.toko=toko;
    document.getElementById('tokoBarcode').textContent=toko.barcode;
    document.getElementById('tokoNama').textContent=toko.nama_toko;
    document.getElementById('tokoLat').textContent=toko.latitude;
    document.getElementById('tokoLng').textContent=toko.longitude;
    document.getElementById('tokoAccuracy').textContent=toko.accuracy+' m';
    document.getElementById('tokoInfoCard').style.display='block';
    document.getElementById('salesLocationCard').style.display='block';
    document.getElementById('salesLocationCard').scrollIntoView({behavior:'smooth',block:'center'});
    showAlert('success','Toko "'+toko.nama_toko+'" ditemukan. Klik "Ambil Lokasi" untuk lanjut.');
  });
};

function getSalesLocation(){
  if(!state.toko){showAlert('danger','Pilih toko terlebih dahulu.');return;}
  if(!navigator.geolocation){showAlert('danger','Browser tidak mendukung Geolocation API.');return;}
  var btn=document.getElementById('getLocationBtn');
  btn.disabled=true;
  btn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span> Mengambil...';
  navigator.geolocation.getCurrentPosition(function(pos){
    var loc={lat:pos.coords.latitude,lng:pos.coords.longitude,accuracy:pos.coords.accuracy};
    state.salesLocation=loc;
    document.getElementById('salesLat').textContent=loc.lat;
    document.getElementById('salesLng').textContent=loc.lng;
    document.getElementById('salesAccuracy').textContent=loc.accuracy.toFixed(1)+' m';
    document.getElementById('salesLocationCard').style.display='block';
    btn.disabled=false;
    btn.innerHTML='<i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi';
    calculateJarak();
  },function(err){
    btn.disabled=false;
    btn.innerHTML='<i class="mdi mdi-crosshairs-gps me-1"></i> Ambil Lokasi';
    var msg='Gagal mendapatkan lokasi.';
    if(err.code===1)msg='Izin lokasi ditolak.';
    else if(err.code===2)msg='Posisi tidak tersedia.';
    else if(err.code===3)msg='Waktu habis.';
    showAlert('danger',msg);
  },{enableHighAccuracy:true,timeout:15000,maximumAge:0});
}

function calculateJarak(){
  if(!state.toko||!state.salesLocation)return;
  var latT=state.toko.latitude,lonT=state.toko.longitude,accT=state.toko.accuracy;
  var latS=state.salesLocation.lat,lonS=state.salesLocation.lng,accS=state.salesLocation.accuracy;
  var jarak=haversine(latT,lonT,latS,lonS);
  var thrEff=THRESHOLD_BASE+accT+accS;
  var status=jarak<=thrEff?'diterima':'ditolak';
  state.jarak=jarak;state.thresholdEfektif=thrEff;state.status=status;
  document.getElementById('jarakAktual').textContent=jarak.toFixed(1)+' m';
  document.getElementById('thresholdBase').textContent=THRESHOLD_BASE+' m';
  document.getElementById('accTokoDisplay').textContent=accT+' m';
  document.getElementById('accSalesDisplay').textContent=accS.toFixed(1)+' m';
  document.getElementById('thresholdEfektif').textContent=thrEff.toFixed(1)+' m';
  document.getElementById('validationCard').style.display='block';
  var badge=document.getElementById('statusBadge');
  var msg=document.getElementById('statusMessage');
  if(status==='diterima'){
    badge.className='status-badge diterima mb-3';
    badge.innerHTML='<i class="mdi mdi-check-circle"></i> DITERIMA';
    msg.textContent='Sales dalam radius efektif ('+thrEff.toFixed(1)+' m). Jarak: '+jarak.toFixed(1)+' m.';
  }else{
    badge.className='status-badge ditolak mb-3';
    badge.innerHTML='<i class="mdi mdi-close-circle"></i> DITOLAK';
    msg.textContent='Sales di luar radius efektif ('+thrEff.toFixed(1)+' m). Jarak: '+jarak.toFixed(1)+' m.';
  }
  document.getElementById('statusCard').style.display='block';
  document.getElementById('submitSection').style.display='block';
}

function submitKunjungan(){
  if(!state.toko||!state.salesLocation){showAlert('danger','Data belum lengkap.');return;}
  var btn=document.getElementById('submitKunjunganBtn');
  btn.disabled=true;
  btn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
  var payload={
    barcode_toko:state.toko.barcode,
    nama_toko:state.toko.nama_toko,
    lat_toko:state.toko.latitude,
    lng_toko:state.toko.longitude,
    accuracy_toko:state.toko.accuracy,
    lat_sales:state.salesLocation.lat,
    lng_sales:state.salesLocation.lng,
    accuracy_sales:state.salesLocation.accuracy,
    jarak:parseFloat(state.jarak.toFixed(2)),
    threshold:THRESHOLD_BASE,
    threshold_efektif:parseFloat(state.thresholdEfektif.toFixed(2)),
    status:state.status
  };
  var xhr=new XMLHttpRequest();
  xhr.open('POST',"{{ route('Geotag.kunjungan') }}",true);
  xhr.setRequestHeader('Content-Type','application/json');
  xhr.setRequestHeader('X-CSRF-TOKEN','{{ csrf_token() }}');
  xhr.setRequestHeader('Accept','application/json');
  xhr.onload=function(){
    try{
      var json=JSON.parse(xhr.responseText);
      if(json.status){
        showAlert('success','Kunjungan berhasil disimpan!');
        setTimeout(resetAll,2000);
      }else{showAlert('danger',json.message||'Gagal menyimpan.');}
    }catch(e){showAlert('danger','Gagal menyimpan.');}
    btn.disabled=false;
    btn.innerHTML='<i class="mdi mdi-send me-1"></i> Submit Kunjungan';
  };
  xhr.onerror=function(){showAlert('danger','Gagal menyimpan.');btn.disabled=false;btn.innerHTML='<i class="mdi mdi-send me-1"></i> Submit Kunjungan';};
  xhr.send(JSON.stringify(payload));
}

function loadQrLib(cb){
  if(typeof window.Html5Qrcode!=='undefined'){cb();return;}
  var urls=['https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js','https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js'];
  (function tryUrl(i){
    if(i>=urls.length){showAlert('danger','html5-qrcode gagal dimuat.');return;}
    var s=document.createElement('script');
    s.src=urls[i];s.async=true;
    s.onload=function(){if(typeof window.Html5Qrcode!=='undefined')cb();else tryUrl(i+1);};
    s.onerror=function(){tryUrl(i+1);};
    document.head.appendChild(s);
  })(0);
}

function startScanner(){
  if(state.isScanning)return;
  if(!window.isSecureContext){showAlert('danger','Kamera butuh HTTPS atau localhost.');return;}
  loadQrLib(function(){
    state.scanner=new Html5Qrcode('qr-reader');
    Html5Qrcode.getCameras().then(function(cams){
      if(!cams||!cams.length){showAlert('danger','Kamera tidak ditemukan.');return;}
      var cam=cams[0];
      for(var i=0;i<cams.length;i++){if(/back|rear|environment/i.test(cams[i].label)){cam=cams[i];break;}}
      state.scanner.start(cam.id,{fps:10,qrbox:{width:280,height:280},aspectRatio:1.0,disableFlip:false},onQrDetected,function(e){}).then(function(){
        state.isScanning=true;
        document.getElementById('scanner-placeholder').style.display='none';
        document.getElementById('qr-reader').style.display='block';
        document.getElementById('scanner-area').classList.add('active');
        document.getElementById('startCameraBtn').innerHTML='<i class="mdi mdi-camera-off me-1"></i> Stop Kamera';
        document.getElementById('scannerFlash').style.display='block';
        showAlert('info','Scanner aktif. Arahkan QR code ke kamera.');
      }).catch(function(e){showAlert('danger','Gagal: '+e.message);});
    }).catch(function(e){showAlert('danger','Tidak bisa akses kamera: '+e.message);});
  });
}

function stopScanner(){
  if(!state.isScanning||!state.scanner)return;
  state.scanner.stop().then(function(){
    state.isScanning=false;
    document.getElementById('qr-reader').style.display='none';
    document.getElementById('scanner-placeholder').style.display='flex';
    document.getElementById('scanner-area').classList.remove('active');
    document.getElementById('startCameraBtn').innerHTML='<i class="mdi mdi-qrcode-scan me-1"></i> Mulai Scan';
    document.getElementById('scannerFlash').style.display='none';
  }).catch(function(){});
}

function onQrDetected(decodedText){
  stopScanner();
  var barcode=decodedText.trim();
  document.getElementById('detectedBarcode').textContent=barcode;
  fetchToko(barcode,function(toko){
    if(!toko)return;
    state.toko=toko;
    document.getElementById('tokoBarcode').textContent=toko.barcode;
    document.getElementById('tokoNama').textContent=toko.nama_toko;
    document.getElementById('tokoLat').textContent=toko.latitude;
    document.getElementById('tokoLng').textContent=toko.longitude;
    document.getElementById('tokoAccuracy').textContent=toko.accuracy+' m';
    document.getElementById('tokoInfoCard').style.display='block';
    document.getElementById('salesLocationCard').style.display='block';
    document.getElementById('salesLocationCard').scrollIntoView({behavior:'smooth',block:'center'});
    showAlert('success','Toko "'+toko.nama_toko+'" ditemukan. Klik "Ambil Lokasi".');
  });
}

document.getElementById('startCameraBtn').addEventListener('click',function(){
  if(state.isScanning)stopScanner();else startScanner();
});
document.getElementById('getLocationBtn').addEventListener('click',getSalesLocation);
document.getElementById('submitKunjunganBtn').addEventListener('click',submitKunjungan);
document.getElementById('resetAllBtn').addEventListener('click',resetAll);

loadTokoList();
})();
</script>
@endpush
