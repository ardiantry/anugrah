@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css" type="text/css">
 <style>

    .map {
    height: 400px;
    width: 100%;
    position: relative;
    }
      #pplayer {
  position: absolute;
  bottom: 0;
  width: 100%;
  z-index: 12;
  background: #fff;
  max-height: 175px;
  overflow-y: auto;
}
.table td, .table th {
  padding: 1px 5px;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
  font-size: 10px;
}
.klikfokus {
  cursor: pointer;
}

.basemap {
  margin: 0;
  padding: 0;
}
.basemap li {
  display: block;
  margin: 5px 10px 5px 0px;
  border: 1px solid #ccc;
  padding: 5px;
  border-radius: 3px;
}
.navbar {
  position: relative;
  padding: -0.5rem 1rem;
}
.navtools {
  position: absolute;
  z-index: 1;
  background: rgb(255, 255, 255);
  padding: 10px;
  box-shadow: 0px 3px 3px 1px rgba(136, 129, 129, 0.51);
  border-radius: 5px;
  right: 15px;
  top: 12px;
}
.navtools ul {
  display: ;
  margin: 0;
  padding: 0;
}
.navtools li {
  display: block;
}
 .ol-popup {
        position: absolute;
        background-color: white;
        box-shadow: 0 1px 4px rgba(0,0,0,0.2);
        padding: 15px;
        border-radius: 10px;
        border: 1px solid #cccccc;
        bottom: 12px;
        left: -50px;
        min-width: 280px;
      }
      .ol-popup:after, .ol-popup:before {
        top: 100%;
        border: solid transparent;
        content: " ";
        height: 0;
        width: 0;
        position: absolute;
        pointer-events: none;
      }
      .ol-popup:after {
        border-top-color: white;
        border-width: 10px;
        left: 48px;
        margin-left: -10px;
      }
      .ol-popup:before {
        border-top-color: #cccccc;
        border-width: 11px;
        left: 48px;
        margin-left: -11px;
      }
      .ol-popup-closer {
        text-decoration: none;
        position: absolute;
        top: 2px;
        right: 8px;
      }
      .ol-popup-closer:after {
        content: "✖";
      }
      
.ubahlayer ul {
  padding: 0;
  margin: 0;
}
.ubahlayer ul li {
  display: inline-block;
  border: 1px solid #ccc;
  padding: 5px;
  background: rgba(9, 7, 7, 0.53);
  color: #fff;
}
  .btnsimpan {
  position: absolute;
  z-index: 10;
  right: 36px;
  top: 13px;
}  
#modalpagedata td {
  font-size: 15px;
}
.box-shadow {
  box-shadow: 0px 4px 5px 0px rgba(51, 41, 41, 0.46);
}
    </style>
<div class="row"> 
    <div class="col-md-2 box-shadow"> 
        <div  class="side_bar"> 
            <form id="nop" name="nop">
                <div class="form-group">
                <label>Pilih Kecamatan</label>
                <select name="id_kec" class="form-control">
                </select> 
                </div>
                <div class="form-group">
                <label>Pilih Desa</label>
                <select name="id_desa" class="form-control" readonly="readonly"> 
                </select> 
                </div>
                <div class="form-group">
                <label>Fiscal_parcels</label> 
                <div class="form-control">
                <input type="checkbox" name="fiscal_parcels" value="1"> 
                </div>
                </div> 
               <!--  <div class="form-group">
                <label>Legal_parcels</label>
                 <div class="form-control">
                <input type="checkbox" name="legal_parcels" value="1">
                </div> 
                </div>   --> 
                <div class="form-group">
                <label>Buildings</label> 
                 <div class="form-control">
                <input type="checkbox" name="buildings" value="1">
                </div>
                </div>   
                <!-- <div class="form-group">
                <label>Jalan</label> 
                 <div class="form-control">
                <input type="checkbox" name="jalan" value="1">
                </div>
                </div>   -->
                 <div class="form-group">
                    <button class="btn btn-success" type="submit" >Sampilkan</button>
                 </div>  
            </form>
                <ul class="basemap">
                <li><input type="radio" name="base"  value="satelite"> Satelite</li>
                <li><input type="radio" name="base" value="osm" checked="checked"> OSM</li>
                <li><input type="radio" name="base" value="raster"> Raster</li>

                </ul>
        </div>  
    </div>
    <div class="col-md-10"> 
        <div id="map" class="map"> 
            <div id="pplayer"> 

            </div>
                <div class="navtools">
                    <ul>
                        <li><input type="checkbox" name="informasi" value="informasi" disabled="disabled"> Informasi</li>
                @if(@Auth::user()->id)
                        <li><input type="checkbox" name="ubah" value="ubah" disabled="disabled"> Ubah</li>
                        <li><input type="checkbox" name="buatlayer" value="buatlayer"> Buat Layer</li> 
                @endif
                    </ul>
                </div>
                <div class="btnsimpan"></div> 
                <div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                <div id="popup-content"> 
                    <table class="table infolayer"></table>
                    <div class="ubahlayer"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<div id="modalpagedata" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm"> 
        <div class="modal-content"> 
                 <div class="Alert_msg"></div>
                <div class="modal-body">
                  <form id="simpandataattrmodal"  name="simpandataattrmodal">
                    
                  </form>
                </div>
                <div class="modal-footer">   
                </div>
        </div>

    </div>
</div>

<div id="mODALPilihTable" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm"> 
        <div class="modal-content">  
                <div class="modal-body">
                  <form id="simpanpilihModal"  name="simpanpilihModal">
                    <div class="form-group">
                    <div class="input-group">
                      <select name="nama_db" class="form-control">
                        <option value="fiscal_parcels">fiscal_parcels</option>
                        <option value="buildings">buildings</option>
                      </select>
                      <button type="submit" class="btn btn-success">Simpan</button>
                    </div> 
                    </div>
                  </form>
                </div>
                <div class="modal-footer">   
                </div>
        </div>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/build/ol.js"></script>
<script src="{{asset('js/jsts.min.js')}}"></script> 
<script type="text/javascript"> 
         var _token               ='{{csrf_token()}}';
         var getdata_nop          ='{{route('getdata_nop')}}'; 
         var simpandatajson       ='{{route('simpandatajson')}}';
         var simpanubahjson       ='{{route('simpanubahjson')}}';
         var ubahdatapro          ='{{route('ubahdatapro')}}';
         var simpandatajsonbaru   ='{{route('simpandatajsonbaru')}}';
         var hapus_layer          ='{{route('hapus_layer')}}';


         

         var get_id_kec           ='{{@$app->request->input('id_kec')}}';
         var get_id_kel           ='{{@$app->request->input('id_kel')}}';

         var get_fiscal_parcels   ='{{@$app->request->input('fiscal_parcels')}}';
         var get_buildings        ='{{@$app->request->input('buildings')}}';


</script>
<script src="{{asset('js/script.js')}}"></script> 
@endsection
