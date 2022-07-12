@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.14.1/css/ol.css" type="text/css">
<link rel="stylesheet" href="{{asset('css/stylemaps.css')}}" type="text/css"> 
    <!-- side bar -->
<div class="row"> 
    <div class="col-md-3"> 
        <div  class="side_bar"> 
            <form id="nop" name="nop">
                <div class="form-group row">
                  <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                      <label>Pilih Kecamatan</label>
                      <select name="id_kec" class="form-control">
                      </select> 
                  </div>
                  <div class="col-lg-12 col-md-12 col-sm-6 col-xs-6">
                      <label>Pilih Desa</label>
                      <select name="id_desa" class="form-control" readonly="readonly"> 
                      </select> 
                  </div>
                   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <label>Blok</label>
                      <select name="id_blok" class="form-control" readonly="readonly"> 
                      </select> 
                  </div>
                </div> 
                <div class="form-group clr_l">
                    <input type="checkbox" name="fiscal_parcels" value="1"> 
                    <div class="form-control">
                        <label>Fiscal_parcels</label> 
                        <div class="color_ Fiscal_parcels_clr"></div> 
                    </div>
                </div>  
                <div class="form-group clr_l">
                     <input type="checkbox" name="buildings" value="1">
                        <div class="form-control">
                            <label>Buildings</label> 
                            <div class="color_ buildings_clr"></div> 
                        </div>
                </div>    
                <div class="form-group clr_l">
                          <input type="checkbox" name="Legal_parcels" value="1">
                           <div class="form-control">
                          <label>Legal_parcels</label> 
                          <div class="color_ Legal_parcels_clr"></div> 
                      </div>
                  </div>  
                 <div class="input-group">
                    <button class="btn btn-success btn-sm" type="submit" ><i class="fa fa-search"></i> Tampilkan</button>
                    @if(@Auth::user()->id)
                    <span class="input-group-append"> 
                      <a href="#" class="btn btn-primary btn-sm" id="modalunggah">unggah data</a>
                    </span>
                    @endif
                 </div>  
            </form>
            <div class="border">
              
                      <div class="form-group clr_l">
                            <input type="checkbox" name="Land_use" value="1">
                             <div class="form-control">
                                  <label>Land_use</label> 
                                <div class="Land_use_clr"></div> 
                            </div>
                      </div> 
                       <div class="form-group clr_l">
                              <input type="checkbox" name="JaringanJalan" value="1">
                             <div class="form-control">
                                <label>JaringanJalan</label> 
                                <div class="Land_use_clr"></div> 
                            </div>
                      </div> 
                       <div class="form-group clr_l">
                             <input type="checkbox" name="JaringanPDAM" value="1">
                             <div class="form-control">
                                <label>Jaringan PDAM</label> 
                                <div class="Land_use_clr"></div> 
                            </div>
                      </div>  
                      <div class="form-group clr_l">
                                  <input type="checkbox" name="JaringanListrik" value="1">
                             <div class="form-control">
                                <label>JaringanListrik</label> 
                                <div class="Land_use_clr"></div> 
                            </div>
                      </div> 
                      <div class="form-group clr_l">
                                  <input type="checkbox" name="DataWajibPajak" value="1">
                             <div class="form-control">
                              <label>DataWajibPajak</label> 
                              <div class="Land_use_clr"></div> 
                            </div>
                      </div>  
            </div> 
            
       </div>   
    </div>  
    <div class="col-md-9"> 
        <div id="map" class="map">  
          <ul class="basemap">
            <li><input type="radio" name="base"  value="satelite"> Satelite</li>
            <li><input type="radio" name="base" value="osm" checked="checked"> OSM</li>
            <li><input type="radio" name="base" value="raster"> Raster</li> 
          </ul>
    </div> 
</div>

<div class="row" id="pplayer">
      <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 navtools" >    
          <ul>
          <li><input type="checkbox" name="informasi" value="informasi" disabled="disabled"> Informasi</li>
          @if(@Auth::user()->id)
          <li><input type="checkbox" name="ubah" value="ubah" disabled="disabled"> Ubah</li>
          <li><input type="checkbox" name="buatlayer" value="buatlayer"> Buat Layer</li> 
          @endif
          </ul> 
      </div>

          <div class="col-md-10 col-lg-10 col-sm-10 col-xs-12" id="pplayer2">  
          </div>
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
                        <option value="legal_parcels">Legal_parcels</option> 
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

<div id="modalformunggah" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm"> 
        <div class="modal-content">  
                <div class="modal-body">
                  <form id="simpanunggah"  name="simpanunggah">
                    <div class="aler-msg"></div>
                    <div class="form-group"> 
                        <label>Unggah file</label>
                        <input type="file" name="csv" class="form-control">
                    </div> 
                    <div class="form-group"> 
                        <label>Tabel yg ingin di insert</label>
                        <select class="form-control" name="jenis_tabel"> 
                          <option value="fiscal_parcels">fiscal_parcels</option>
                          <option value="buildings">buildings</option> 
                          <option value="legal_parcels">Legal_parcels</option> 
                        </select>
                    </div>
                     <div class="form-group"> 
                      <button type="submit" class="btn btn-success btn-sm">simpan</button>
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
         var getlegalparsel       ='{{route('getlegalparsel')}}'; 
         var getblokdata          ='{{route('getblokdata')}}'; 
         var unggahdata           ='{{route('unggahdata')}}'; 
         var getland_use          ='{{route('getland_use')}}'; 
         var nilai_zoom           ='{{@env('nilai_zoom')?@env('nilai_zoom'):4}}'; 

         

         var get_id_kec           ='{{@$app->request->input('id_kec')}}';
         var get_id_kel           ='{{@$app->request->input('id_kel')}}';
         var get_id_blok          ='{{@$app->request->input('id_blok')}}';
         var get_fiscal_parcels   ='{{@$app->request->input('fiscal_parcels')}}';
         var get_buildings        ='{{@$app->request->input('buildings')}}';
         var get_legal_parcels   ='{{@$app->request->input('legal_parcels')}}';



</script>
<script src="{{asset('js/script.js')}}"></script> 
@endsection
