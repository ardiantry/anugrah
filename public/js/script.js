 
// popup
const container           = document.getElementById('popup');
const content             = document.getElementById('popup-content');
const closer              = document.getElementById('popup-closer');
const unitsSelect         = document.getElementById('units');
const typeSelect          = document.getElementById('type');
const stepsSelect         = document.getElementById('steps');
const overlay           = new ol.Overlay({
              element: container,
              autoPan: true, 
              autoPanAnimation: {
              duration: 250
              }
              });

closer.onclick            =function() {
              overlay.setPosition(undefined);
              closer.blur();
              return false;
              };
// popup 

//view
var view=new ol.View({
          center: ol.proj.fromLonLat([106.816666,-6.200000]),
          zoom: 4,
          dataProjection: 'EPSG:4326',
            featureProjection: 'EPSG:3857',
        });
//view
//maps
 var map = new ol.Map({
        target: 'map',
        overlays: [overlay],
        view: view

      });

//maps
//base osm
window.osm=new ol.layer.Tile({
            source: new ol.source.OSM()
          });
map.addLayer(window.osm);
//base osm
//set height
var windowHeight  = $('.side_bar').height();
                    $('#map').css({
                    height : (parseInt(windowHeight)+200)+"px"    
                    });
map.updateSize();  
//set height

//base google
var stylesbing = [  
          's',
          'r'
          ];
let    gmaps            =[];
for(let k of stylesbing)
{
var google_base       =new ol.layer.Tile({
              source    : new ol.source.XYZ({
              url: 'https://mt0.google.com/vt/lyrs='+k+'&hl=en&x={x}&y={y}&z={z}', 
              crossOrigin : "Anonymous"
              })
              });
    gmaps.push(google_base); 
}; 
//base google


//get api region
fetch(`http://www.emsifa.com/api-wilayah-indonesia/api/districts/3372.json`)
.then(response => response.json())
.then(districts => 
  {
    var list_kec=`<option >--pilih kecamatan--</option>`;
    for(let li of districts)
    {
      var selected=get_id_kec==li.id?'selected="selected"':'';
     list_kec+=`<option value="`+li.id+`" `+selected+`>`+li.name+`</option>`;

    }
    $('select[name="id_kec"]').html(list_kec);

});

if(get_id_kec!='')
{
     kelurahan(get_id_kec,get_id_kel);
     blok_sm(get_id_kel,get_id_blok);
   if(get_fiscal_parcels)
   {

      $('input[name="fiscal_parcels"]').prop('checked',true);
   }
    if(get_buildings)
   {
      $('input[name="buildings"]').prop('checked',true);
   }

     loaddata(get_id_kec,get_id_kel,get_id_blok,get_fiscal_parcels,get_buildings);


}

$('body').delegate('select[name="id_kec"]','change',function(e)
{
 
    e.preventDefault();
    var is_vilage=$(this).val();
    if(is_vilage=='')
    {
      return;
    }
   kelurahan(is_vilage);

});
//get region

function kelurahan(is_vilage,get_id_kel='')
{

    $('select[name="id_desa"]').removeAttr('readonly');
    fetch(`http://www.emsifa.com/api-wilayah-indonesia/api/villages/`+is_vilage+`.json`)
    .then(response => response.json())
    .then(villages => 
    { 
        var list_kec=`<option value="">--pilih desa--</option>`;
        for(let li of villages)
        {
         
         var selected_=get_id_kel==li.id?'selected="selected"':'';
         list_kec+=`<option value="`+li.id+`" `+selected_+`>`+li.name+`</option>`;

        }
        $('select[name="id_desa"]').html(list_kec); 
    });

}
$('body').delegate('select[name="id_desa"]','change',function(e)
{
   e.preventDefault(); 
   var id_desa=$(this).val();
   blok_sm(id_desa);
});

function blok_sm(is_vilage,id_blok='')
{

    $('select[name="id_blok"]').removeAttr('readonly');
    
      const formblok   = new FormData(); 
      formblok.append('_token',_token);
      formblok.append('is_vilage',is_vilage); 
      fetch(getblokdata, { method: 'POST',body:formblok}).then(res => res.json()).then(data => 
      { 
        var list_kec=`<option value="">--pilih blok--</option>`;
        for(let li of data.get_dt)
        {
         
         var selected_=id_blok==li.id_blok?'selected="selected"':'';
         list_kec+=`<option value="`+li.id_blok+`" `+selected_+`>`+li.id_blok+`</option>`;

        }
        $('select[name="id_blok"]').html(list_kec); 
    });

}
//get nop
$('body').delegate('#nop','submit',function(e)
{
  e.preventDefault(); 
   var fiscal_parcels='';
   var buildings='';
   var id_desa=$('select[name="id_desa"]').val(); 
   if(!id_desa)
   {
      alert('Desa Wajib di isi');
      return;
   }  
   if($('input[name="fiscal_parcels"]').is(':checked'))
   {
      var fiscal_parcels=1; 
   }
   if($('input[name="buildings"]').is(':checked'))
   { 
      var buildings=1;
   } 
   var get_kec=$('select[name="id_kec"]').val();
   var get_blok=$('select[name="id_blok"]').val();

   loaddata(get_kec,id_desa,get_blok,fiscal_parcels,buildings);
   loaddatalegalparsel($('input[name="Legal_parcels"]').is(':checked'));
 
});
 


//load layer with api
function loaddata(id_kec,id_desa,get_blok="",fiscal_parcels='',buildings='')
{ 
 
      var fiscal_url    =fiscal_parcels==1?'&fiscal_parcels='+fiscal_parcels:'';
      var buildings_url =buildings==1?'&buildings='+buildings:'';
      var id_blok       =get_blok!=""?'&id_blok='+get_blok:'';
      var url_          ='?id_kec='+id_kec+'&id_kel='+id_desa+id_blok+fiscal_url+buildings_url;
      window.id_desa    =id_desa;

      window.history.pushState({'historycontent':url_}, null,url_); 
      $('input[name="informasi"]').attr('disabled','disabled'); 
      $('input[name="ubah"]').attr('disabled','disabled'); 
      $('#pplayer').css('display','none');
      var fiscal=0;
      var building=0;

      for(let cek in window)
      {
            if(cek.indexOf('fiscal_parcels_')!=-1||cek.indexOf('buildings_')!=-1)
            { 
                 console.log(cek);
               if(cek.indexOf('_fiscal_parcels_')==-1||cek.indexOf('_buildings_')==-1)
               {
                 map.removeLayer(window[cek]);
                  window[cek]=undefined;
               }
               

            } 
             if(cek.indexOf('_fiscal_parcels_')!=-1)
             {

              fiscal++;
             }
              if(cek.indexOf('_buildings_')!=-1)
             {
              building++;
             }
            
      }

       if(fiscal>0)
       {
          $('#pplayer2').find('.pp_fiscal_parcels').remove();

       }
       if(building>0)
       {
          $('#pplayer2').find('.pp_buildings').remove();

       }
      const formnop   = new FormData(); 
      formnop.append('_token',_token);
      formnop.append('id_desa',id_desa+get_blok);
      if(fiscal_parcels)
      {
         formnop.append('fiscal_parcels',fiscal_parcels);
      }
      if(buildings)
      {
         formnop.append('buildings',buildings);
      } 
      fetch(getdata_nop, { method: 'POST',body:formnop}).then(res => res.json()).then(data => 
      { 
        if(!data.error)
        {  
            if(data.data_geo.buildings)
            {
               $('input[name="informasi"]').removeAttr('disabled');
                $('input[name="ubah"]').removeAttr('disabled'); 
                loadarraywkt('buildings_',data.data_geo.buildings);
                informasi_layer('pp_buildings',data.data_geo.buildings);
            }
            if(data.data_geo.fiscal_parcels)
            {
               $('input[name="informasi"]').removeAttr('disabled');
               $('input[name="ubah"]').removeAttr('disabled');
               loadarraywkt('fiscal_parcels_',data.data_geo.fiscal_parcels);
               informasi_layer('pp_fiscal_parcels',data.data_geo.fiscal_parcels);
 
            } 
        }
        else
        {
             alert(data.error);
        }
      })
      .catch(function(error){
          alert(error);
      });
}
//load layer with api
// fungsi legal parsel
function loaddatalegalparsel(this_)
{
   //console.log(this_);
   $('#pplayer').css('display','none');
 
   if(this_)
   {
      
      var cek_l=0;
      for(let cek in window)
      {
            if(cek.indexOf('legal_parcels_')!=-1&&cek.indexOf('_legal_parcels')==-1)
            {   
              window[cek].setVisible(true);
              cek_l++;
            } 
         
      }  
       
      if(window['pp_legal_parcels'])
      {
          informasi_layer('pp_legal_parcels',window['pp_legal_parcels']); 
      }
      if(cek_l<=0)
      {
           // console.log(cek_l);
            const formlegal   = new FormData(); 
            formlegal.append('_token',_token);
            fetch(getlegalparsel, { method: 'POST',body:formlegal}).then(res => res.json()).then(data => 
            { 
                     loadarraywkt('legal_parcels_',data.data_legal);
                     informasi_layer('pp_legal_parcels',data.data_legal); 
            }); 
      }
      
   }else
   {
      $('#pplayer2').find('.pp_legal_parcels').remove();
      for(let cek in window)
      {
          if(cek.indexOf('legal_parcels_')!=-1)
            {   
              window[cek].setVisible(false); 
            }  
      }
   }

}
// fungsi legal parsel



// convert wkt to layer 
function loadarraywkt(name_wind,data_)
{
   var l=window.layer==undefined?2:window.layer; 
   var fokus_layer='';
   for(let li_ of data_)
   { 
      if(window[name_wind+li_.id]==undefined)
      {
           var wkt     =li_.geojson; 
            var vktor_='';
           if(li_.d_nop)
           {
               var lyer_=name_wind+li_.id;
               vktor_  =vektor_wkt(wkt,lyer_,style_default('D nop :' +li_.d_nop,lyer_));
           }
           else
           {
               vktor_  =vektor_wkt(wkt,name_wind+li_.id);  
           }
           vktor_.setZIndex(l);
           map.addLayer(vktor_);  
           window[name_wind+li_.id]=vktor_; 
           fokus_layer=name_wind+li_.id;
           l++;  
      } 

   }  
   if(window[fokus_layer]!=undefined)
   {

      window.layer      =l;  
      var coord_center  =[]; 
      var get_lyr_coor  =window[fokus_layer].getSource().getFeatures();
      get_lyr_coor.forEach(function(feat)
      {
         coord_center  =feat.getGeometry().getExtent();
      });
      var X = coord_center[0] + (coord_center[2]-coord_center[0])/2;
      var Y = coord_center[1] + (coord_center[3]-coord_center[1])/2;
      view.animate({zoom: 4},{center: [X,Y]});
   }

}
//convert wkt to layer

//style default
function style_default(text_="",layer="")
{  
var color='rgba(13, 187, 227, 0.4)';
if(layer.indexOf('fiscal_parcels')!=-1)
{
 color='rgba(234, 221, 25, 0.4)';
}
if(layer.indexOf('buildings')!=-1)
{
 color='rgba(219, 26, 26, 0.4)';
}

return new ol.style.Style
    ({
            stroke  : new ol.style.Stroke
            ({
                  color: '#000',
                  width: 1
            }),
            fill     : new ol.style.Fill({
            color    : color
            }),
            text: new ol.style.Text({ 
            fill: new ol.style.Fill({
                  color: '#fff'
               }),
            stroke: new ol.style.Stroke({
            color: '#000',
            width: 4
             }),
               text:text_
             })
    });
}
// style default
//style hehlevel
var highlightStyle = function()
{
  
     return new ol.style.Style({
     fill: new ol.style.Fill({
     color: 'rgba(235, 235, 235, 0.72)'
     }),
     stroke: new ol.style.Stroke({
     color: 'rgba(37, 178, 209, 0.99)',
     width: 5
      })
     });
}
//style hehlevel


function vektor_wkt(wkt,id,style_=style_default())
{
  //console.log(id_layer);
  var format = new ol.format.WKT();
  var feature = format.readFeature(wkt
      ,{
          dataProjection: 'EPSG:4326',
            featureProjection: 'EPSG:3857',
    }
  );
  feature.set('id',id);  
  var vector = new ol.layer.Vector
        ({
            source  : new ol.source.Vector
            ({
              features: [feature],
              wrapX   : false
          })
            ,style   : style_
        }); 
return vector;  
}
//bottom informasi 
function informasi_layer(name_,data_,show=true)
{ 
   var list_      ='';
   var name_th    =``; 
   window[name_]  =data_;
//console.log(name_);
   var ojk_neme   =Object.keys(data_[0]); 
   for(let nameky of ojk_neme)
   {
         if(nameky!='geojson')
            {
                name_th+=`<th>`+nameky+`</th>`;
            }
   }
   name_th=`<tr><th>aksi</th>`+name_th+`</tr>`;
   for(let v_ of data_)
   {  
      window[name_+'_'+v_.id]=v_; 

      list_+=      `<tr><th><input type="checkbox" name="layer_[]" value="`+name_+'_'+v_.id+`" checked="checked"></th></th>`;  
      for(let vk_ of ojk_neme)
      {
            if(vk_!='geojson')
               {  
                     list_+= `<th  class="klikfokus" data-idlayer="`+v_.id+`">`+v_[vk_]+`</th>`;
               }
       }
   list_+=      `</tr>`;
   }
      var tb_ = `<table class="table table-striped `+name_+`">
      <tr><td colspan="`+ojk_neme.length+`">`+name_.replace('pp_','')+`</td></tr>
      `+name_th+list_+`</table>`;

      if(show)
      {
         if(!$('#pplayer2').find('.table').hasClass(name_))
         { 
            $('#pplayer2').append(tb_);
         }
      }
      else
      {
         $('#pplayer2').find('.'+name_).remove();
      } 
      if($('#pplayer2').find('.table').length!=0)
      {
            $('#pplayer').css('display','flex'); 
      }

}
//bottom informasi 

//auto fokus to layer
$('body').delegate('.klikfokus','click',function(e)
{
   e.preventDefault(); 
   var idlayer =$(this).closest('tr').find('input[name="layer_[]"]').val();  
    var get_lyr_coor  =window[idlayer.replace('pp_','')].getSource().getFeatures(); 
        get_lyr_coor.forEach(function(feat) 
        {
        coord_center  =feat.getGeometry().getExtent();
         });
        if(coord_center)
        {

           var X = coord_center[0] + (coord_center[2]-coord_center[0])/2;
           var Y = coord_center[1] + (coord_center[3]-coord_center[1])/2;
           view.animate({zoom: 4},{center: [X,Y]}); 
        }
});
//auto fokus to layer
//hide show layer
$('body').delegate('input[name="layer_[]"]','change',function(e)
{
  e.preventDefault(); 

  var idlayer =$(this).val();
  idlayer=idlayer.replace('pp_','');
  if($(this).is(':checked'))
  { 
      window[idlayer].setVisible(true);
  } 
  else
  { 
      window[idlayer].setVisible(false); 
  }      
});
//hide show layer
// change base map
$('body').delegate('input[name="base"]','change',function(e)
{
    e.preventDefault();
    if(window.osm)
    { 
        window.osm.setVisible(false);
    }  
    if(window.satelite)
    { 
      window.satelite.setVisible(false);
    }  
    if(window.raster)
    { 
      window.raster.setVisible(false);
    } 
    switch($(this).val())
    {
      case 'satelite':
        if(!window.satelite)
        {
            window.satelite=gmaps[0];
            map.addLayer(window.satelite);
        }
        else
        {
          window.satelite.setVisible(true);
        }
      break;
      case 'osm':
      window.osm.setVisible(true);
      break;
      case 'raster':
       if(!window.raster)
        {
            window.raster=gmaps[1];
            map.addLayer(window.raster);
        }
        else
        {
          window.raster.setVisible(true);
        }
      break;
    } 

});
// change base map
//onclik to layer
map.on('click', function(evt)
{
   if(window.potong||window.ubah_layer||window.buatbaru)
   {
      return;
   }
      for(let cek in window)
      {
            if(cek.indexOf('fiscal_parcels_')!=-1 && cek.indexOf('pp_fiscal_parcels_')<=-1)
            {  
                window[cek].setStyle(style_default('',cek)); 
            } 
            if(cek.indexOf('buildings_')!=-1 && cek.indexOf('pp_buildings_')<=-1)
            { 
                window[cek].setStyle(style_default('',cek)); 
            } 
      } 

  overlay.setPosition(undefined);

  $('input[name="informasi"]').removeAttr('disabled');
  $('input[name="ubah"]').removeAttr('disabled');

  var feature = map.forEachFeatureAtPixel(evt.pixel,function(feature) {
    return feature;
    });
  $('.infolayer').empty();
  $('.ubahlayer').empty();
  if(feature)
  { 
     window[feature.get('id')].setStyle(highlightStyle); 
     window.edit=feature.get('id');
   // console.log(feature.get('id'));
      if($('input[name="informasi"]').is(':checked')==true)
      {
        overlay.setPosition(evt.coordinate); 
        isipopup('info',feature.get('id'));
      }
      if($('input[name="ubah"]').is(':checked')&&(window.edit.indexOf('fiscal_parcels')!=-1||window.edit.indexOf('buildings')!=-1))
      {
        overlay.setPosition(evt.coordinate);
        isipopup2('ubah',feature.get('id'));
      
      } 

  } 
});
//onclik to layer
//popup template
function isipopup(aksi,id_)
{ 
  $('input[name="informasi"]').attr('disabled','disabled');

  var id_neme='pp_'+id_; 
  var list_pp='';  

   $.each(window[id_neme],function(t,v)
   {
        if(t!='geojson')
        {
           list_pp+=`<tr><td>`+t+`</td><td>`+v+`</td></tr>`; 
        }
   });  
    $('.infolayer').html(list_pp);
  
    
}
function isipopup2(aksi,id_)
{ 
  $('input[name="ubah"]').attr('disabled','disabled');
  var id_neme='pp_'+id_;  
  var list_ubah='';  
      list_ubah =`<ul  data-id="`+id_neme+`">
                  <li class="potongbentuk">Potong</li>
                  <li class="ubahbentuk">Ubah bentuk</li>
                  <li class="UbahData">Ubah Data</li> 
                  <li class="hapusbentuk">hapus data</li>
                </ul>`; 
 
$('.ubahlayer').html(list_ubah);
   
}
//popup template

// poligon
$('body').delegate('.potongbentuk','click',function(e)
{
   e.preventDefault(); 
   overlay.setPosition(undefined);
   window.potong=window.edit;
 
   var draw_Vector_new  = new ol.layer.Vector({
        source: new ol.source.Vector(),
        style: new ol.style.Style({
          stroke: new ol.style.Stroke({
           color: 'rgba(37, 178, 209, 0.99)',
            width: 5
          }),
          fill: new ol.style.Fill({
            color: 'rgba(141, 135, 135, 0.68)'
          })
        })
      });

   map.addLayer(draw_Vector_new);
   draw_Vector_new.setZIndex(3);

   var create_new_persil = new ol.interaction.Draw({
   source   : draw_Vector_new.getSource(),
   type     : 'LineString', 
   });
   map.addInteraction(create_new_persil);

   var name_snap = new ol.interaction.Snap({
         source: window[window.edit].getSource()
         });
   map.addInteraction(name_snap);  
   create_new_persil.on('drawend',function(event) 
      { 
         map.removeInteraction(create_new_persil);
         map.removeInteraction(name_snap);
         var layer               =window[window.edit];
         var drawVector          =event.feature;
         var hasil               =Cuter(layer,drawVector); 
         //const myJSON         = JSON.stringify(hasil); 
         const Layer_Data_json   = new FormData();    
         var wktRep              = new Blob([hasil]);
        // var wktRep              = new Blob([myJSON], {type: "application/json"});
         Layer_Data_json.append('_token',_token); 
         Layer_Data_json.append('wktRep',wktRep); 
         Layer_Data_json.append('id_layer',window.edit);    
         fetch(simpandatajson, { method: 'POST',body:Layer_Data_json}).then(res => res.json()).then(data => 
         { 
              
               window.location.reload();

         });
         //console.log(hasil);
      }); 

});
$('body').delegate('.ubahbentuk','click',function(e)
{
    e.preventDefault();
   overlay.setPosition(undefined);
   $('.btnsimpan').html('<button class="btn btn-success btn-sm btn-blok" id="simpanLayer">simpan</button>');
      window.ubah_layer=window.edit;
      window.ExampleModify = new ol.interaction.Modify({
      source: window[window.edit].getSource()
      });
      map.addInteraction(window.ExampleModify);  

});


$('body').delegate('#simpanLayer','click',function(e)
{
   e.preventDefault();
   
         var layer_persil        = window[window.edit].getSource().getFeatures();
         var new_wkt,wkt_polygon;
         var formatwkt           = new ol.format.WKT(); 
         layer_persil.forEach(function(feat)
         {
            new_wkt    =feat.getGeometry();
             if (new_wkt.getType() === 'MultiPolygon') 
                  {
                     new_wkt = new_wkt.getPolygon(0);

                  } 
             wkt_polygon  = formatwkt.writeGeometry(new_wkt,{dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'}); 
         }); 
         const Layerjson   = new FormData();    
         var wktRep        = new Blob([wkt_polygon]); 
         Layerjson.append('_token',_token); 
         Layerjson.append('wktRep',wktRep); 
         Layerjson.append('id_layer',window.ubah_layer);  
         fetch(simpanubahjson, { method: 'POST',body:Layerjson}).then(res => res.json()).then(data => 
               { 
                    
                     window.location.reload();

               });
});
$('body').delegate('.UbahData','click',function(e)
{
      e.preventDefault();
      overlay.setPosition(undefined);
      $('#simpandataattrmodal').empty();
      $('#modalpagedata').modal('show');  
      window.ubah_data  =window.edit;
      var lidt          =window['pp_'+window.ubah_data];
      var ojk_neme      =Object.keys(lidt); 
      var label_        ='';
      for(let lbl_ of ojk_neme)
      {
            if(lbl_!='geojson'&&lbl_!='id')
            {
                  label_+=`<tr><td>`+lbl_+`</td><td><input name="`+lbl_+`" value="`+lidt[lbl_]+`" class="form-control"></td>`; 
                  label_+=`</tr>`;  
            }

      }
      $('#simpandataattrmodal').html('<table class="table">'+label_+'</table><button class="btn btn-success" type="submit">Update</button>'); 

});


$('body').delegate('#simpandataattrmodal','submit',function(e)
{
    e.preventDefault();
   const simpandata      = document.forms.namedItem('simpandataattrmodal');
   const Formini          = new FormData(simpandata); 
   Formini.append('_token',_token); 
   Formini.append('id_layer',window.ubah_data);  
   fetch(ubahdatapro, { method: 'POST',body:Formini}).then(res => res.json()).then(data => 
   {
      window.location.reload();
   });

});

function Cuter(layer,drawVector)
{

  
   var reader        = new jsts.io.WKTReader();
   var writer        = new jsts.io.WKTWriter();
   var formatwkt     = new ol.format.WKT(); 
   var wkt_polygon,wkt_line;
   var feats_layer   =layer.getSource().getFeatures();
      feats_layer.forEach(function(feat){
      var polygon = feat.getGeometry();
            if (polygon.getType() === 'MultiPolygon') 
            {
               polygon = polygon.getPolygon(0);

            } 
            wkt_polygon  = formatwkt.writeGeometry(polygon,{dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'}); 
      });

   var wkt_line      = formatwkt.writeGeometry(drawVector.getGeometry(),{dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'}); 
   var a             = reader.read(wkt_polygon);
   var b             = reader.read(wkt_line);
   var union         = a.getExteriorRing().union(b);

   var polygonizer   = new jsts.operation.polygonize.Polygonizer();
   polygonizer.add(union);
   

   var polygons      = polygonizer.getPolygons(); 
   var new_polygon=[];
   for (var i = polygons.iterator(); i.hasNext();) 
   {
       var polygon = i.next();
       var newpolygon=writer.write(polygon);
       new_polygon.push(newpolygon);
       
   } 
   return new_polygon;

}


$('body').delegate('input[name="buatlayer"]','change',function(e)
{
   e.preventDefault();
    overlay.setPosition(undefined);
   if($(this).is(':checked')==true)
   {
       
      window.buatbaru      =true; 
      window.draw  = new ol.layer.Vector({
                           source    : new ol.source.Vector(),
                           style     : new ol.style.Style({
                           stroke    : new ol.style.Stroke({
                           color     : 'rgba(37, 178, 209, 0.99)',
                           width     : 5
                           }),
                           fill      : new ol.style.Fill({
                           color     : 'rgba(141, 135, 135, 0.68)'
                            })
                        })
                  });
      map.addLayer(window.draw);
      window.draw.setZIndex(3);
      window.persil_ = new ol.interaction.Draw({
            source   : window.draw.getSource(),
            type     : 'Polygon', 
            });   
      map.addInteraction(window.persil_); 

      for(let cek in window)
      {
            if(cek.indexOf('fiscal_parcels_')!=-1&&cek.indexOf('pp_fiscal_parcels_')==-1||cek.indexOf('buildings_')!=-1&&cek.indexOf('pp_buildings_')==-1)
            { 
               var name_snap = new ol.interaction.Snap({
                  source:window[cek].getSource()
                  });
                  map.addInteraction(name_snap);  
                   
            }  
      } 


     window.persil_.on('drawend',function(event) 
      {  
             window.event_per=event.feature;
             $('#mODALPilihTable').modal({backdrop: 'static',keyboard: false}); 

      }); 



   }
   else
   {
      window.buatbaru=undefined;
      map.removeLayer(window.draw);  
      map.removeInteraction(window.persil_);
      for(let cek in window)
      {
            if(cek.indexOf('fiscal_parcels_')!=-1&&cek.indexOf('pp_fiscal_parcels_')==-1||cek.indexOf('buildings_')!=-1&&cek.indexOf('pp_buildings_')==-1)
            { 
               var name_snap = new ol.interaction.Snap({
                  source:window[cek].getSource()
                  });
                  map.removeInteraction(name_snap);  
                   
            }  
      } 
   } 
});
$('body').delegate('#simpanpilihModal','submit',function(e)
{
   e.preventDefault();
   var formatwkt       = new ol.format.WKT(); 
   var layer_persil    = window.event_per;
   var wkt_polygon     = formatwkt.writeGeometry(layer_persil.getGeometry(),{dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});  
   const Layerbarusimpan   = new FormData();    
   var wktRep          = new Blob([wkt_polygon]); 
   Layerbarusimpan.append('_token',_token); 
   Layerbarusimpan.append('wktRep',wktRep); 
   Layerbarusimpan.append('db',$('select[name="nama_db"]').val());  
   Layerbarusimpan.append('data_titik',window.id_desa);  
   fetch(simpandatajsonbaru, { method: 'POST',body:Layerbarusimpan}).then(res => res.json()).then(data => 
         { 
              
               window.location.reload();

         }); 


});
$('body').delegate('.hapusbentuk','click',function(e)
{ 
   e.preventDefault();
   if(!confirm('yakin menghapus data peta?'))
   {
      return
   }
   const Layerbarusimpan   = new FormData();     
   Layerbarusimpan.append('_token',_token);    
   Layerbarusimpan.append('data_id',window.edit);  
   fetch(hapus_layer, { method: 'POST',body:Layerbarusimpan}).then(res => res.json()).then(data => 
         { 
              
               window.location.reload();

         }); 
});
$('body').delegate('#modalunggah','click',function(e)
{ 
   e.preventDefault();
   $('#modalformunggah').modal('show'); 
});

$('body').delegate('#simpanunggah','submit',function(e)
{ 
   e.preventDefault();
   $('.aler-msg').empty();
   var form_= document.forms.namedItem("simpanunggah");
    const oData = new FormData(form_);
    if (window.XMLHttpRequest) 
    {
        var xmlhttp=new XMLHttpRequest();
    } 
    else 
    {  
        var xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    oData.append('_token',_token); 
    xmlhttp.responseType    = 'json';
    xmlhttp.crossDomain     = true;
    xmlhttp.async           = false;
    xmlhttp.cache           = false;
    xmlhttp.contentType     = false;
    xmlhttp.processData     = false;
    xmlhttp.open("POST",unggahdata,true);
    xmlhttp.upload.addEventListener('progress', function(e) {
            var max     = e.total;
                var current   = e.loaded;
                var Percentage  = (current * 100) / max; 
              $('.aler-msg').html(Percentage+ '%');

                
    });
        xmlhttp.onreadystatechange = function() 
        { 
           if (this.readyState==4 && this.status==200) 
          {  
               $('.aler-msg').empty();
            window.setTimeout(function() 
            {  
            // window.location.reload(); 
            }, 1000); 
          }
        }
  xmlhttp.send(oData);
});



