<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

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

        $pol = explode('POLYGON', $data_json);
        $i = 0;
        $ii = 0;
        foreach ($pol as $key) {
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

        if ($polygon != false) {
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
        if ($polygon) {
            DB::table(@$request->db)->insert([
                'd_nop'     => @$request->data_titik,
                'geom'      => $polygon
            ]);
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
        print json_encode(array('error' => false));
    }
}
