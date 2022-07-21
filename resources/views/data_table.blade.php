@extends('layouts.app') 
@section('content')
<link rel="stylesheet" href="{{asset('css/stylemaps.css')}}" type="text/css"> 
<style type="text/css">
	.side_bar { 
  background: rgb(207, 221, 247); 
   overflow-y: unset;
  left: -250px;
 transition: all .3s ease;
 padding-bottom: unset; 
}
.mt-20 {
  margin-top: 80px;
} 
#sidehide {
  position: absolute;
  right: -26px;
  z-index: 100;
}
#sidehide i {
  transform: rotate(-90deg);
  
  transition: all .3s ease;
}
.side_bar.show
{
 	left: 0px;
}
.side_bar.show #sidehide i
{
	transform: rotate(90deg);
}
.page_content
{
	transition: all .3s ease;
}  
html, body
{
	height: unset;
}
.table td, .table th {
  padding: 1px 5px;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
  font-size: 15px;
}
</style>
<div  class="side_bar"> 
	<button class="btn btn-success btn-sm" id="sidehide"><i class="fa fa-sort-down"></i></button>
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
           <!--   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <label>Blok</label>
                <select name="id_blok" class="form-control" readonly="readonly"> 
                </select> 
            </div> -->
          </div> 
          <div class="form-group clr_l">
              <input type="checkbox" name="fiscal_parcels" value="1"> 
              <div class="form-control">
                  <label>Fiscal_parcels</label>  
              </div>
          </div>  
          <div class="form-group clr_l">
               <input type="checkbox" name="buildings" value="1">
                  <div class="form-control">
                      <label>Buildings</label>  
                  </div>
          </div>    
          <div class="form-group clr_l">
                    <input type="checkbox" name="Legal_parcels" value="1">
                     <div class="form-control">
                    <label>Legal_parcels</label>  
                </div>
            </div>  
            <div class="form-group text-center">
                <button class="btn btn-success btn-sm" type="submit" ><i class="fa fa-search"></i> Tampilkan</button> 
            </div>
      </form> 
      
 </div>
 <div class="container">
	 <div class="row justify-content-end mt-20">
	 	<div class="page_content col-md-12">
	 		<div class="card">
	 			<div class="card-body" id="datatable">
	 				<div id="tbfisikal">Silahkan Isikan form untuk menampilkan data</div>
	 				<div id="tbparcels"></div>
	 				<div id="tbbuildings"></div> 
	 				
	 			</div>
	 		</div>
	 	</div> 
	 </div>
 </div>
<div id="modalEdit" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg"> 
        <div class="modal-content">  
        	<div class="modal-header"></div>
                <div class="modal-body"> 
                	<form id="simpanEdit" name="simpanEdit"> 
                		
                	</form>
                </div>
                <div class="modal-footer">   
                </div>
        </div>

    </div>
</div>


<script type="text/javascript">
	$(document).ready(function()
	{

		 var _token               ='{{csrf_token()}}';
		 var getdata_nop          ='{{route('getdata_nop')}}';
		 var getblokdata          ='{{route('getblokdata')}}';  
         var get_id_kec           ='{{@$app->request->input('id_kec')}}';
         var get_id_kel           ='{{@$app->request->input('id_kel')}}';
         var get_id_blok          ='{{@$app->request->input('id_blok')}}';
         var get_fiscal_parcels   ='{{@$app->request->input('fiscal_parcels')}}';
         var get_buildings        ='{{@$app->request->input('buildings')}}';
         var get_legal_parcels   ='{{@$app->request->input('legal_parcels')}}';
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
			   //blok_sm(id_desa);
			});
 
			$('body').delegate('#nop','submit',function(e)
			{
			  e.preventDefault();  

					$('#tbfisikal').html('Silahkan Isikan form untuk menampilkan data');

					$('#tbparcels').empty();

					$('#tbbuildings').empty();


			   var id_desa=$('select[name="id_desa"]').val(); 
			   if(!id_desa)
			   {
			      alert('Desa Wajib di isi');
			      return;
			   }  
				window.id_desa=id_desa;  
				window.get_kec=$('select[name="id_kec"]').val(); 
			   if($('input[name="fiscal_parcels"]').is(':checked'))
			   {
			   	window.cari_fis=undefined;
			 	  tampilkanDatafiscal_parcels();
			   }
			   if($('input[name="buildings"]').is(':checked'))
			   { 
			   		window.cari_build=undefined;
			       tampilkanDatabuildings();
			   } 

			   if($('input[name="Legal_parcels"]').is(':checked'))
			   {
			   		window.cari_par=undefined;
			 	 	tampilkanDatalegal_parcels();
			   }

			});


			$('body').delegate('#sidehide','click',function(e)
			{
				 e.preventDefault(); 
				 $(this).closest('.side_bar').toggleClass('show'); 
				 if($(this).closest('.side_bar').hasClass('show'))
				 {

				 	$('.page_content').removeClass('col-md-12');
				 	$('.page_content').addClass('col-md-10');
				 }
				 else
				 {
				 	$('.page_content').removeClass('col-md-10');
				 	$('.page_content').addClass('col-md-12');
				 }
			});

			function tampilkanDatafiscal_parcels(new_pg=true)
			{ 

				if(new_pg)
					{
					$('#tbfisikal').html('loading...');
				}
					const formnop   = new FormData(); 
					formnop.append('_token',_token); 
					formnop.append('fiscal_parcels',1);
					if(window.cari_fis)
					{
					formnop.append('cari',window.cari_fis); 
					}
					console.log(window.cari_fis);
					var page_=window.fiscal_parcels_page!=undefined?'?page='+window.fiscal_parcels_page:'?page='+1;
					var url_wind=window.url_wind==undefined?'{{url('get-data-tabel')}}'+page_:window.url_wind; 
					fetch(url_wind, { method: 'POST',body:formnop}).then(res => res.json()).then(data => 
					{ 

						var dt_fiscal_parcels	=	data.fiscal_parcels.data;
						window.fiscal_parcels_page=undefined;
						var tb_  				='';
						var colspan 			=0;
						if(dt_fiscal_parcels.length!=0)
						{
							var ojk_neme   =Object.keys(dt_fiscal_parcels[0]);  
							var name_th    =``; 

							for(let nameky of ojk_neme)
							{
								if(nameky!='geojson')
								{
									colspan+=1;
									name_th+=`<th>`+nameky+`</th>`;
								} 							}
							name_th=`<tr>`+name_th+`<td>Aksi</td></tr>`;
							var list_="";
							window['fiscal_parcels']=[];
							for(let v_ of data.fiscal_parcels.data)
							{
								   
							   list_+= `<tr>`;
							   var data_edt=[];
								for(let vk_ of ojk_neme)
								{
									if(vk_!='geojson')
									{  
										data_edt[vk_]=v_[vk_];
									      list_+= `<th  class="klikfokus" data-idlayer="`+v_.id+`">`+v_[vk_]+`</th>`;
									}
								}
								window['fiscal_parcels'][v_.id]=data_edt;
								   list_+= `<td><a data-tbl="fiscal_parcels" data-id="`+v_.id+`"  class="btn btn-warning btn-sm Edit" href="#">Edit</a></td></tr>`;

							}
							if(data.fiscal_parcels.next_page_url)
							{
								var page=data.fiscal_parcels.next_page_url;
								window.fiscal_parcels_page=page.split('?page=')[1];
							}
							
						}

						 
						 	if(new_pg)
						 	{ 
						 		var val_cari='';
						 		if(window.cari_fis!=undefined)
						 		{
									val_cari=window.cari_fis;
						 		}
								$('#tbfisikal').html(`<table class="table table-striped">
										<tr>
										<td colspan="`+(parseInt(colspan)+1)+`">
										fiscal_parcels
										<form id="carifiscal_parcels"><div class="input-group"><input class="form-control" value="`+val_cari+`" name="carifiscal_parcels"><button type="submit" class="btn btn-primary btn-sm">Cari</button></div></form>
										</td>
										</tr>
										`+name_th+`<tbody id="listfiscal_parcels">`+list_+`</tbody></table><div id="fiscal_parcelsladmore"></div>`);
						 	}
						 	else
						 	{
						 		$('#listfiscal_parcels').append(list_);
						 	}

					 		var page_btn='';
					 		if(window.fiscal_parcels_page!=undefined)
					 		{
					 			$('#fiscal_parcelsladmore').html('<a href="#">loadmore</a>');

					 		}
							

					}); 
			}

			function tampilkanDatalegal_parcels(new_pg=true)
			{ 
					if(new_pg)
					{
					$('#tbparcels').html('loading...');
						
					}
					const formnop   = new FormData(); 
					formnop.append('_token',_token); 
					if(window.cari_par!=undefined)
					{
						formnop.append('cari',window.cari_par); 
						
					}
					formnop.append('legal_parcels', 1);
					var page_=window.legal_parcels_page!=undefined?'?page='+window.legal_parcels_page:'?page='+1;
					var url_wind=window.url_wind==undefined?'{{url('get-data-tabel')}}'+page_:window.url_wind; 
					fetch(url_wind, { method: 'POST',body:formnop}).then(res => res.json()).then(data => 
					{ 

						var dt_legal_parcels	=	data.legal_parcels.data;
						window.legal_parcels_page=undefined;
						var tb_  				='';
						var colspan 			=0;
						if(dt_legal_parcels.length!=0)
						{
							var ojk_neme   =Object.keys(dt_legal_parcels[0]);  
							var name_th    =``; 
 							var data_edt=[];
							for(let nameky of ojk_neme)
							{
								if(nameky!='geojson')
								{
									colspan+=1;
									name_th+=`<th>`+nameky+`</th>`;
								} 							
							}
							name_th=`<tr>`+name_th+`<td>Aksi</td></tr>`;
							var list_="";
							window['legal_parcels']=[];
							for(let v_ of data.legal_parcels.data)
							{
								   list_+= `<tr>`;
								for(let vk_ of ojk_neme)
								{
									if(vk_!='geojson')
									{  
										
										  data_edt[vk_]=v_[vk_];
									      list_+= `<th  class="klikfokus" data-idlayer="`+v_.id+`">`+v_[vk_]+`</th>`;
									}
								}
									window['legal_parcels'][v_.id]=data_edt;
								    list_+= `<td><a data-tbl="legal_parcels" data-id="`+v_.id+`"  class="btn btn-warning btn-sm Edit" href="#" >Edit</a></td></tr>`;

							} 

							if(data.legal_parcels.next_page_url)
							{
								var page=data.legal_parcels.next_page_url;
								window.legal_parcels_page=page.split('?page=')[1];
							}
						 
						}
						if(new_pg)
							{  
								var val_per='';
								if(window.cari_par!=undefined)
								{
								val_per=window.cari_par; 

								}
								$('#tbparcels').html(`<table class="table table-striped">
									<tr>
									<td colspan="`+(parseInt(colspan)+1)+`">
									legal_parcels
									<form id="carilegal_parcels"><div class="input-group"><input class="form-control" value="`+val_per+`" name="carilegal_parcels"><button type="submit" class="btn btn-primary btn-sm">Cari</button></div></form>
									</td>
									</tr>
									`+name_th+`<tbody id="listtbparcels">`+list_+`</tbody></table><div id="parcelsladmore"></div>`);
							}
							else
							{
								$('#listtbparcels').append(list_);
							} 

					 		var page_btn='';
					 		if(window.legal_parcels_page!=undefined)
					 		{
					 			$('#parcelsladmore').html('<a href="#">loadmore</a>');

					 		}
								

					}); 
			}
			function tampilkanDatabuildings(new_pg=true)
			{ 

				if(new_pg)
					{
					$('#tbbuildings').html('loading...');
				}
					const formnop   = new FormData(); 
					formnop.append('_token',_token); 
					formnop.append('buildings', 1);

					if(window.cari_build!=undefined)
					{
						formnop.append('cari',window.cari_build);  
					}
					var page_=window.buildings_page!=undefined?'?page='+window.buildings_page:'?page='+1;
					var url_wind=window.url_wind==undefined?'{{url('get-data-tabel')}}'+page_:window.url_wind; 
					fetch(url_wind, { method: 'POST',body:formnop}).then(res => res.json()).then(data => 
					{ 

						var dt_buildings	 	=data.buildings.data; 
						var tb_  				='';
						var colspan 			=0;
						window.buildings_page 	=undefined;
						if(dt_buildings.length!=0)
						{
							var ojk_neme   =Object.keys(dt_buildings[0]);  
							var name_th    =``; 

							for(let nameky of ojk_neme)
							{
								if(nameky!='geojson')
								{
									colspan+=1;
									name_th+=`<th>`+nameky+`</th>`;
								} 							}
							name_th=`<tr>`+name_th+`<td>Aksi</td></tr>`;
							var list_="";
							window['buildings']=[];


							for(let v_ of data.buildings.data)
							{
								   list_+= `<tr>`;
								    var data_edt=[];
								for(let vk_ of ojk_neme)
								{
									if(vk_!='geojson')
									{   
										  data_edt[vk_]=v_[vk_]; 
									      list_+= `<th  class="klikfokus" data-idlayer="`+v_.id+`">`+v_[vk_]+`</th>`;
									}
								}

									window['buildings'][v_.id]=data_edt;
								   list_+= `<td><a data-tbl="buildings" data-id="`+v_.id+`" class="btn btn-warning btn-sm Edit" href="#">Edit</a></td></tr>`;

							} 
						if(data.buildings.next_page_url)
							{
								var page=data.buildings.next_page_url;
								window.buildings_page=page.split('?page=')[1];
							}
						 
						}
						if(new_pg)
							{ 
								var val_per='';
 
								if(window.cari_build!=undefined)
								{
								val_per=window.cari_build;  
								}
								$('#tbbuildings').html(`<table class="table table-striped">
									<tr>
									<td colspan="`+(parseInt(colspan)+1)+`">
									buildings
									<form id="caribuildings"><div class="input-group"><input class="form-control" value="`+val_per+`" name="caribuildings"><button type="submit" class="btn btn-primary btn-sm">Cari</button></div></form>
									</td>
									</tr>
									`+name_th+`<tbody id="listbuildings">`+list_+`</tbody></table><div id="buildingsladmore"></div>`);
							}
							else
							{
								$('#listbuildings').append(list_);
							} 

					 		var page_btn='';
					 		if(window.buildings_page!=undefined)
					 		{
					 			$('#buildingsladmore').html('<a href="#">loadmore</a>');

					 		}
								

					}); 
			}

			$('body').delegate('#fiscal_parcelsladmore a','click',function(e)
			{
				e.preventDefault();
				 tampilkanDatafiscal_parcels(false);

			}); 
			$('body').delegate('#parcelsladmore a','click',function(e)
			{
				e.preventDefault();
				 tampilkanDatalegal_parcels(false);

			});
			$('body').delegate('#buildingsladmore a','click',function(e)
			{
				e.preventDefault();
				 tampilkanDatabuildings(false);

			});


		$('body').delegate('.Edit','click',function(e)
		{
			e.preventDefault();
			window.id_layer_edit=$(this).data('id');
			window.tbl_name=$(this).data('tbl');
			$('#modalEdit').modal('show');
			console.log(window.tbl_name);

		});

		$('#modalEdit').on('show.bs.modal', function (event) 
		{
			$('#simpanEdit').empty();
			//var data_edit =window.tbl_name+'_'+window.id_layer_edit;
			var data_edit=window[window.tbl_name][window.id_layer_edit];
			window.ubah_data=window.tbl_name+'_'+window.id_layer_edit;
							var ojk_name   =Object.keys(data_edit);  
							var form=``;
							for(let lb of ojk_name)
							{
								if(lb!='id')
								{

									form+=`<div class="form-group">
											<label>`+lb+`</label>
											<input value="`+data_edit[lb]+`" name="`+lb+`" class="form-control">
									</div>`;
								}
							}
							$(this).find('.modal-header').html(window.tbl_name);
							$('#simpanEdit').html(form+'<div class="form-group"><button class="btn btn-primary btn-sm" type="submit">Simpan</button></div>');

		});		
		$('body').delegate('#simpanEdit','submit',function(e)
		{
				e.preventDefault();
				var ubah_data 			=window.ubah_data;
				const simpandata      	= document.forms.namedItem('simpanEdit');
				const Formini          = new FormData(simpandata); 
				Formini.append('_token',_token); 
				Formini.append('id_layer',window.ubah_data);  
				fetch('{{route('ubahdatapro')}}', { method: 'POST',body:Formini}).then(res => res.json()).then(data => 
				{
					//window.location.reload();
					$('#modalEdit').modal('hide');
					if(ubah_data.indexOf('fiscal_parcels')!=-1)
					{
						window.fiscal_parcels_page=undefined;
						tampilkanDatafiscal_parcels();
					}
					else if(ubah_data.indexOf('legal_parcels')!=-1)
					{
						window.legal_parcels_page=undefined;
						tampilkanDatalegal_parcels();
					} 
					else if(ubah_data.indexOf('buildings')!=-1)
					{
						window.buildings_page=undefined;
						tampilkanDatabuildings();
					}

				});
		});

		$('body').delegate('#carifiscal_parcels','submit',function(e)
		{
			e.preventDefault();
			window.cari_fis=$(this).find('input[name="carifiscal_parcels"]').val();
			window.fiscal_parcels_page=undefined;
			tampilkanDatafiscal_parcels();
			// tampilkanDatalegal_parcels(new_pg=true);
			// tampilkanDatabuildings(new_pg=true);

		});
		$('body').delegate('#caribuildings','submit',function(e)
		{
			e.preventDefault();
			window.cari_build=$(this).find('input[name="caribuildings"]').val();
			window.buildings_page=undefined
			//tampilkanDatafiscal_parcels();
			 //tampilkanDatalegal_parcels();
			tampilkanDatabuildings();

		});
	});
</script>

@endsection
