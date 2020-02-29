<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Goods;
use App\Cart;
class GoodsController extends Controller
{
    //
    function index($id){
        //Redis::flushall();die;
        $GoodsInfo=Redis::get('GoodsInfo_'.$id);
        if(!$GoodsInfo){
            $GoodsInfo=Goods::find($id);
            $GoodsInfo=serialize($GoodsInfo);
            Redis::set('GoodsInfo_'.$id,$GoodsInfo);
        }
        $GoodsInfo=unserialize($GoodsInfo);
        $count=Redis::setnx('num_'.$id,1);
        if(!$count){
            $count=Redis::incr('num_'.$id);
        }
        return view('goods.proinfo',['GoodsInfo'=>$GoodsInfo,'count'=>$count]);
    }
    function addcart(){
        $goods_id=request()->goods_id??'';
        $buy_number=request()->buy_number;
        if($buy_number==0){
           echo json_encode(['code'=>2,'font'=>'购买数量不能为空']);die;
        }
        if(request()->session()->has('user_id')) {
             $res=$this->addCartDb($goods_id,$buy_number);
        }
        if($res){
            echo json_encode(['code'=>1,'font'=>'加入购物车成功']);die;
        }else{
            echo json_encode(['code'=>2,'font'=>"加入购物车失败"]);die;
        }
    }
    function addCartDb($goods_id,$buy_number){
        $where=[
            ['user_id','=',session('user_id')],//这个用户
            ['goods_id','=',$goods_id],//商品id
            ['cart_del','=',1],//隐藏条件,回收站为正常
        ];
        $goods_num=Goods::where('goods_id','=',$goods_id)->value('goods_num');
        $CartInfo=Cart::where($where)->first();
        if(!empty($CartInfo)){
            if(($CartInfo['buy_number']+$buy_number)>$goods_num){
                $buy_number=$goods_num;
            }else{
                $buy_number=$CartInfo['buy_number']+$buy_number;
            }
            $res=Cart::where($where)->update(['buy_number'=>$buy_number,'add_time'=>time()]);
        }else{
            if($buy_number>$goods_num){
                $buy_number=$goods_num;
            }
            $arr=["goods_id"=>$goods_id,'buy_number'=>$buy_number,'add_time'=>time(),'user_id'=>session('user_id')];
            $res=Cart::create($arr);
        }
        return $res;
    }
    function CarIndex(){
        if(request()->session()->has('user_id')) {
            $CartInfo=$this->getCartInfoDb();
        }
        $count=$CartInfo['count'];
        $CartInfo=$CartInfo['CartInfo'];
        return view('goods.car',['CartInfo'=>$CartInfo,'count'=>$count]);
    }
    function getCartInfoDb(){
        $CartInfo=[];
        $where=[
            ["user_id",'=',session('user_id')],
            ['cart_del','=',1],
        ];
        $CartInfo['count']=Cart::where($where)->count();
        $CartInfo['CartInfo']=Goods::leftjoin('cart','goods.goods_id','=','cart.goods_id')->where($where)->orderby('add_time','desc')->get();
        return $CartInfo;
    }
    function changeNumber(){
        $goods_id=request()->goods_id;
        $buy_number=request()->buy_number;
        if(request()->session()->has('user_id')){
            $res=$this->changeNumberDb($goods_id,$buy_number);
        }
        if($res){
            echo json_encode(['code'=>1,'font'=>'']);
        }else{
            echo json_encode(['code'=>2,'font'=>'操作失败']);
        }
    }
    function changeNumberDb($goods_id,$buy_number){
        $where=[
            ['user_id','=',session('user_id')],
            ['goods_id','=',$goods_id],
            ['cart_del','=',1]
        ];
        $res=Cart::where($where)->update(['buy_number'=>$buy_number]);
        return $res;
    }
    function getTotal(){
        $goods_id=request()->goods_id;
        if(empty($goods_id)){
            echo json_encode(['code'=>2,'font'=>"操作失败"]);
        }
        $goods_price=Goods::where("goods_id",'=',$goods_id)->value("goods_price");
        if(empty($goods_price)){
            echo json_encode(['code'=>2,'font'=>"操作失败"]);
        }
        if(request()->session()->has('user_id')){
            $Total=$this->getTotalDb($goods_id,$goods_price);
        }
        echo json_encode(['code'=>1,'font'=>$Total]);
    }
    function getTotalDb($goods_id,$goods_price){
        $where=[
            ['user_id','=',session('user_id')],//这个用户
            ['goods_id','=',$goods_id],//这个商品id
            ['cart_del','=',1],//没有删除
        ];
        $buy_number=Cart::where($where)->value('buy_number');//获取到购买数量
        return $goods_price*$buy_number*0.5;//返回一个小计,也就是用单价*购买数量
    }
    function getMoney(){
        $goods_id=request()->goods_id;
        if(request()->session()->has('user_id')){
            $money=$this->getMoneyDb($goods_id);
        }
        echo $money;
    }
    function getMoneyDb($goods_id){
        $where=[
            ['user_id','=',session('user_id')],
            ['cart.goods_id','in',$goods_id],
            ['cart_del','=',1],
        ];
        $info=Cart::leftjoin("goods",'goods.goods_id','=','cart.goods_id')
            ->where($where)
            ->select("buy_number","goods_price")
            ->get();
        $money=0;
        foreach($info as $k=>$v){
            $money+=$v['buy_number']*$v['goods_price'];
        }
        $money=$money*0.5;
        dd($money);die;
        return $money;
    }
}
