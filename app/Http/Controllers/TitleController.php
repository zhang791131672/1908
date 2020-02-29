<?php

namespace App\Http\Controllers;

use App\Cate;
use Illuminate\Http\Request;
use App\Article;
use App\Category;
//use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
class TitleController extends Controller
{
    //
    function index(){
        $a_title=request()->a_title??'';
        $where=[];
        if($a_title){
            $where[]=['a_title','like',"%".$a_title."%"];
        }
        $c_id=request()->c_id??'';
        if($c_id){
            $where[]=['article.c_id','=',$c_id];
        }
        $page=request()->page??1;
        //Cache::flush();
        //die;
        Redis::del('Category');
        Redis::del('data');die;
        //$Category=Cache::get('Category');
        $Category=Redis::get('Category');
        dump($Category);
        if(!$Category){
            $Category=Category::get();
            $Category=serialize($Category);
            Redis::setex('Category',300,$Category);
            //Cache::put('Category',$Category,300);
        }
        $Category=unserialize($Category);
        //$data=Cache::get('data_'.$page.'_'.$a_title.'_'.$c_id);
        $data=Redis::get('data_'.$page.'_'.$a_title.'_'.$c_id);
        dump($data);
        if(!$data){
            $pagesize=config('app.pagesize');
            $data=Article::leftjoin('category','article.c_id','=','category.c_id')->where($where)->paginate($pagesize);
            //Cache::put('data_'.$page.'_'.$a_title.'_'.$c_id,$data,300);
            $data=serialize($data);
            Redis::setex('data_'.$page.'_'.$a_title.'_'.$c_id,300,$data);
        }
        $data=unserialize($data);
        if(request()->ajax()){
            return view('title.pagesize',['data'=>$data,'Category'=>$Category,'query'=>request()->all()]);
        }
        //dd($Category);
        return view('title.index',['data'=>$data,'Category'=>$Category,'query'=>request()->all()]);
    }
}
