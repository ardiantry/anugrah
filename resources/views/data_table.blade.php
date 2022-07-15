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
	 			<div class="card-body">
	 				<table class="table">
	 					<thead id="dataTable"><tr><td class="text-center"><div class="alert alert-warning">Tidak ada data yg ditampilkan</div></td></tr></thead>
	 				</table>
	 			</div>
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

			// function blok_sm(is_vilage,id_blok='')
			// {

			//     $('select[name="id_blok"]').removeAttr('readonly');
			    
			//       const formblok   = new FormData(); 
			//       formblok.append('_token',_token);
			//       formblok.append('is_vilage',is_vilage); 
			//       fetch(getblokdata, { method: 'POST',body:formblok}).then(res => res.json()).then(data => 
			//       { 
			//         var list_kec=`<option value="">--pilih blok--</option>`;
			//         for(let li of data.get_dt)
			//         {
			         
			//          var selected_=id_blok==li.id_blok?'selected="selected"':'';
			//          list_kec+=`<option value="`+li.id_blok+`" `+selected_+`>`+li.id_blok+`</option>`;

			//         }
			//         $('select[name="id_blok"]').html(list_kec); 
			//     });

			// }
			//get nop
			$('body').delegate('#nop','submit',function(e)
			{
			  e.preventDefault();  
			   var id_desa=$('select[name="id_desa"]').val(); 
			   if(!id_desa)
			   {
			      alert('Desa Wajib di isi');
			      return;
			   }  
			   if($('input[name="fiscal_parcels"]').is(':checked'))
			   {
			       window.fiscal_parcels=1; 
			   }
			   if($('input[name="buildings"]').is(':checked'))
			   { 
			       window.buildings=1;
			   } 

			   if($('input[name="Legal_parcels"]').is(':checked'))
			   {
			     window.legal_parcels =1;
			   }
				   window.get_kec=$('select[name="id_kec"]').val(); 
				   window.id_desa=id_desa;  
			 	tampilkanData();
			});


			$('body').delegate('#sidehide','click',function(e)
			{
				 e.preventDefault(); 
				 $(this).closest('.side_bar').toggleClass('show');
				 console.log($(this).closest('.side_bar').hasClass('show'));
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
			function tampilkanData()
			{ 
					const formnop   = new FormData(); 
					formnop.append('_token',_token); 
					if(window.fiscal_parcels)
					{
						formnop.append('fiscal_parcels',window.fiscal_parcels);
					}
					if( window.legal_parcels)
					{
						formnop.append('legal_parcels', window.legal_parcels);
					} 
					if( window.buildings)
					{
						formnop.append('buildings', window.buildings);
					} 
					if(window.get_kec)
					{
						formnop.append('get_kec', window.get_kec);
					}
					if(window.id_desa)
					{
						formnop.append('id_desa', window.id_desa);
					}  

					fetch('{{url('get-data-tabel')}}', { method: 'POST',body:formnop}).then(res => res.json()).then(data => 
					{ 

					}); 
			}
			
	});
</script>

@endsection
