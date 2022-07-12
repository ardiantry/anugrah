<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        return view('home');
    }
    public function welcome(Request $request)
    {
        return view('welcome');
    }
    public function getdata_nop(Request $request)
    {


        $alert = '';
        $alert .= $request->input('id_desa') ? '' : 'desa wajib diisi';
        $data_geo = array();
        if ($alert == "") {
            $data_desa = $request->input('id_desa');
            if ($request->input('buildings')) {
                $data_geo['buildings'] = DB::select('SELECT d_nop,idbgn,id,luasbgn,njopbgnm2, ST_AsText(geom) AS geojson  FROM buildings where d_nop like \'' . $data_desa . '%\'');
            }
            if ($request->input('fiscal_parcels')) {
                $data_geo['fiscal_parcels'] = DB::select('SELECT d_nop,d_luas,id,luas,njop,alamatobj,blok,no, ST_AsText(geom) AS geojson  FROM fiscal_parcels where d_nop like \'' . $data_desa . '%\'');
            }
            //  if($request->input('legal_parcels'))
            // {
            //     bn$legal_parcels=DB::select('SELECT d_onp,idBgn,id,luasBgn,njopBgnM2, ST_AsText(geom) AS geojson  FROM legal_parcels where d_onp like '.$data_desa.'%');

            // }
            //  if($request->input('jalan'))
            // {
            //     $jalan=DB::select('SELECT d_onp,idBgn,id,luasBgn,njopBgnM2, ST_AsText(geom) AS geojson  FROM jalan where d_onp like '.$data_desa.'%');

            // }

        }
        print json_encode(array('data_geo' => $data_geo, 'error' => $alert));
    }
    public function simpandatajson(Request $request)
    {
        $file           = $request->file('wktRep');
        $tempPath       = $file->getRealPath();
        $data_json      = file_get_contents($tempPath);
        $get_dt         = array();
        if (preg_match("/fiscal_parcels_/i", $request->input('id_layer'))) {
            $id_ =   str_replace('fiscal_parcels_', '', $request->input('id_layer'));

            $get_dt = DB::table('fiscal_parcels')->select('d_nop', 'id', 'd_luas', 'luas', 'njop', 'alamatobj', 'alamatobj', 'blok', 'no')->where('id', $id_)->first();
        }

        if (preg_match("/buildings_/i", $request->input('id_layer'))) {
            $id_ = str_replace('buildings_', '',  $request->input('id_layer'));
            $get_dt = DB::table('buildings')->select('idbgn', 'id', 'd_nop', 'blok', 'no', 'njopbgn', 'luasbgn', 'njopbgnm2')->where('id', $id_)->first();
        }
        if (preg_match("/legal_parcels_/i", $request->input('id_layer'))) {
            $id_ = str_replace('legal_parcels_', '',  $request->input('id_layer'));
            $get_dt = DB::table('legal_parcels')->select('id', 'nib', 'tipehak', 'penggunaanlahan', 'tataruang', 'ketinggian', 'kemiringan')->where('id', $id_)->first();
        }
        $pol = explode('POLYGON', $data_json);
        $i = 0;
        $ii = 0;
        foreach ($pol as $key) 
        {
            if ($key) {
                $geom = 'Polygon' . trim($pol[$i], ","); 
                //echo $geom;
                if (preg_match("/fiscal_parcels_/i", $request->input('id_layer'))) {
                    if ($ii == 0) {
                        DB::table('fiscal_parcels')->where('id', @$get_dt->id)->update(['geom' => $geom]);
                    } else {
                        DB::table('fiscal_parcels')->insert([
                            'geom'      => $geom,
                            'd_nop'     => @$get_dt->d_nop,
                            'd_luas'    => @$get_dt->d_luas,
                            'luas'      => @$get_dt->luas,
                            'njop'      => @$get_dt->njop,
                            'alamatobj' => @$get_dt->alamatobj,
                            'alamatobj' => @$get_dt->alamatobj,
                            'blok'      => @$get_dt->blok,
                            'no'        => @$get_dt->no
                        ]);
                    }
                }
                if (preg_match("/buildings_/i", $request->input('id_layer'))) {
                    if ($ii == 0) {
                        DB::table('buildings')->where('id', @$get_dt->id)->update(['geom' => $geom]);
                    } else {
                        DB::table('buildings')->insert([
                            'geom'      => $geom,
                            'idbgn'     => @$get_dt->idbgn,
                            'd_nop'     => @$get_dt->d_nop,
                            'blok'      => @$get_dt->blok,
                            'no'        => @$get_dt->no,
                            'njopbgn'   => @$get_dt->njopbgn,
                            'luasbgn'   => @$get_dt->luasbgn,
                            'njopbgnm2' => @$get_dt->njopbgnm2,
                        ]);
                    }
                }
                if (preg_match("/legal_parcels_/i", $request->input('id_layer'))) {
                    if ($ii == 0) 
                    {
                        DB::table('legal_parcels')->where('id', @$get_dt->id)->update(['geom' => $geom]);
                    } 
                    else 
                    {
                        DB::table('legal_parcels')->insert([
                            'geom'                  => $geom,
                            'nib'                   => @$get_dt->nib.'-copy-'.$ii,
                            'tipehak'               => @$get_dt->tipehak,
                            'penggunaanlahan'       => @$get_dt->penggunaanlahan,
                            'tataruang'             => @$get_dt->tataruang,
                            'ketinggian'            => @$get_dt->ketinggian,
                            'kemiringan'            => @$get_dt->kemiringan
                        ]);
                    }
                }

                $ii++;
            }
            $i++;
        }
        print json_encode(array('error' => false));
        // dd($data_json);
    }
    public function simpanubahjson(Request $request)
    {

        $file           = $request->file('wktRep');
        $tempPath       = $file->getRealPath();
        $data_json      = file_get_contents($tempPath);
        $get_dt         = array();
        $pol            = explode('POLYGON', $data_json);
        $polygon        = @$pol[1] ? 'Polygon' . @$pol[1] : false;

        if ($polygon != false) 
        {
            if (preg_match("/fiscal_parcels_/i", $request->input('id_layer'))) {
                $id_ =   str_replace('fiscal_parcels_', '', $request->input('id_layer'));

                $get_dt = DB::table('fiscal_parcels')->select('id')->where('id', $id_)->first();
                if (@$get_dt->id) {
                    DB::table('fiscal_parcels')->where('id', @$get_dt->id)->update(['geom' => $polygon]);
                }
            }
            if (preg_match("/buildings_/i", $request->input('id_layer'))) {
                $id_ = str_replace('buildings_', '',  $request->input('id_layer'));
                $get_dt = DB::table('buildings')->select('id')->where('id', $id_)->first();
                if (@$get_dt->id) {
                    DB::table('buildings')->where('id', @$get_dt->id)->update(['geom' => $polygon]);
                }
            }
            if (preg_match("/legal_parcels_/i", $request->input('id_layer'))) {
                $id_ = str_replace('legal_parcels_', '',  $request->input('id_layer'));
                $get_dt = DB::table('legal_parcels')->select('id')->where('id', $id_)->first();
                if (@$get_dt->id) {
                    DB::table('legal_parcels')->where('id', @$get_dt->id)->update(['geom' => $polygon]);
                }
            }
        }

        print json_encode(array('error' => false));
    }
    public function ubahdatapro(Request $request)
    {
        if (preg_match("/fiscal_parcels_/i", $request->input('id_layer'))) {

            $id_ =   str_replace('fiscal_parcels_', '', $request->input('id_layer'));
            DB::table('fiscal_parcels')->where('id', $id_)->update([
                'd_nop'     => @$request->d_nop,
                'd_luas'    => @$request->d_luas,
                'luas'      => @$request->luas,
                'njop'      => @$request->njop,
                'alamatobj' => @$request->alamatobj,
                'alamatobj' => @$request->alamatobj,
                'blok'      => @$request->blok,
                'no'        => @$request->no
            ]);
        }
        if (preg_match("/buildings_/i", $request->input('id_layer'))) {

            $id_ = str_replace('buildings_', '',  $request->input('id_layer'));
            DB::table('buildings')->where('id', $id_)->update([

                'idbgn'     => @$request->idbgn,
                'd_nop'     => @$request->d_nop,
                'blok'      => @$request->blok,
                'no'        => @$request->no,
                'njopbgn'   => @$request->njopbgn,
                'luasbgn'   => @$request->luasbgn,
                'njopbgnm2' => @$request->njopbgnm2,

            ]);
        }
        if (preg_match("/legal_parcels_/i", $request->input('id_layer'))) 
         { 
            $id_ = str_replace('legal_parcels_', '',  $request->input('id_layer'));
            DB::table('legal_parcels')->where('id', $id_)->update([ 
                'nib'                   => @$request->nib,
                'tipehak'               => @$request->tipehak,
                'penggunaanlahan'       => @$request->penggunaanlahan,
                'tataruang'             => @$request->tataruang,
                'ketinggian'            => @$request->ketinggian,
                'kemiringan'            => @$request->kemiringan

            ]);
        }
        
        print json_encode(array('error' => false));
    }
    public function simpandatajsonbaru(Request $request)
    {

        $file           = $request->file('wktRep');
        $tempPath       = $file->getRealPath();
        $data_json      = file_get_contents($tempPath);
        $get_dt         = array();
        $pol            = explode('POLYGON', $data_json);
        $polygon        = @$pol[1] ? 'Polygon' . @$pol[1] : false;
        if ($polygon) 
        {
            if (preg_match("/legal_parcels/i", @$request->db)) 
            {
             DB::table(@$request->db)->insert([
                    'nib'       => Carbon::now()->format('dhis'),
                    'geom'      => $polygon
                ]);
            }
            else
            {

                DB::table(@$request->db)->insert([
                    'd_nop'     => @$request->data_titik,
                    'geom'      => $polygon
                ]);
            }
        }
        print json_encode(array('error' => false));
    }
    public function hapus_layer(Request $request)
    {


        if (preg_match("/fiscal_parcels_/i", $request->input('data_id'))) {

            $id_ =   str_replace('fiscal_parcels_', '', $request->input('data_id'));
            DB::table('fiscal_parcels')->where('id', $id_)->delete();
        }
        if (preg_match("/buildings_/i", $request->input('data_id'))) {

            $id_ = str_replace('buildings_', '',  $request->input('data_id'));
            DB::table('buildings')->where('id', $id_)->delete();
        }
        if (preg_match("/legal_parcels_/i", $request->input('data_id'))) {

            $id_ = str_replace('legal_parcels_', '',  $request->input('data_id'));
            DB::table('legal_parcels')->where('id', $id_)->delete();
        }
        print json_encode(array('error' => false));
    }
    public function getlegalparsel(Request $request)
    {

           $data_legal= DB::select('SELECT 
                                        id,
                                        nib,
                                        tipehak,
                                        penggunaanlahan,
                                        tataruang,
                                        ketinggian,
                                        kemiringan,
                                        koordinat, 
                                        ST_AsText(geom) AS geojson  FROM legal_parcels');

             print json_encode(array('data_legal' => $data_legal)); 
    }


    public function getland_use(Request $request)
    {

           $land_uses= DB::select('SELECT 
                                        id,
                                        idlahan,
                                        tema,
                                        jenis,
                                        kegiatan,
                                        sumber,
                                        sumber, 
                                        ST_AsText(geom) AS geojson  FROM land_uses');

             print json_encode(array('land_uses' => $land_uses)); 
    }
 public function getblokdata(Request $request)
    {
             $get_dt = DB::select('SELECT d_nop FROM fiscal_parcels where d_nop like \'' . $request->input('is_vilage'). '%\'');
             $i=0;
             $get_dta=array();
             foreach ($get_dt as $key ) 
             {
                $id_blok=substr($key->d_nop,strlen($request->input('is_vilage')),3); 

               $get_dta[$id_blok]['id_blok']=$id_blok;          
                $i++;
             } 

             sort($get_dta);
             print json_encode(array('get_dt' => $get_dta)); 
    }
 public function unggahdata(Request $request)
    {
    ini_set('upload_max_filesize', '500M'); 
    ini_set('post_max_size', '550M');
    ini_set('memory_limit', '1024M');
    ini_set('max_input_time', 3000);
    ini_set('max_execution_time', 3000);



    $file           = $request->file('csv'); 
    $file->move(public_path('csv'), $file->getClientOriginalName());
        if(file_exists(public_path('csv/'.$file->getClientOriginalName())))
            {
                $tempPath           = public_path('csv/'.$file->getClientOriginalName()); 
                $filefopen          = fopen($tempPath,"r");
                $importData_arr     = array();
                $i = 0;
                while (($filedata = fgetcsv($filefopen, 1000, ",")) !== FALSE) 
                {
                    $num = count($filedata );
                    for ($c=0; $c < $num; $c++) 
                    {
                            $importData_arr[$i][] = $filedata [$c];
                    }
                    $i++;
                }
                fclose($filefopen);
                 
            }
  //  $file_telah_ada = array_merge(array(),glob(public_path('csv').'\{,.}*', GLOB_BRACE));


        
    }


}
