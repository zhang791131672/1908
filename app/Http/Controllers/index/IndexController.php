<?php

namespace App\Http\Controllers\index;
use App\Cate;
use App\Goods;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Cache;
class IndexController extends Controller
{
    //
    function index(){
        $goods_name=request()->goods_name??'';
        $where=[];
        if($goods_name){
            $where[]=['goods_name','like',"%".$goods_name."%"];
        }
        $CateInfo=Cache::get('CateInfo_'.$goods_name);
        if(!$CateInfo){
            echo "分类数据库";
            $CateInfo=Cate::limit(4)->where('pid','=',0)->get();
            Cache::put('CateInfo_'.$goods_name,$CateInfo,300);
        }
        $goods_img=Cache::get('goods_img');
        if(!$goods_img){
            echo "图片展示数据库";
            $goods_img=Goods::limit(4)->select('goods_img')->get();
            Cache::put('goods_img',$goods_img,300);
        }
        $GoodsInfo=Cache::get('GoodsInfo'.$goods_name);
        if(!$GoodsInfo){
            $GoodsInfo=Goods::where($where)->limit(8)->get();
            Cache::put('GoodsInfo_'.$goods_name,$GoodsInfo,300);
        }
        $count=Cache::get('count');
        if(!$count){
            $count=Goods::count();
            Cache::put('count',$count,300);
        }
        return view('index.index',['CateInfo'=>$CateInfo,'goods_img'=>$goods_img,'GoodsInfo'=>$GoodsInfo,'goods_name'=>$goods_name,'count'=>$count]);
    }

}
