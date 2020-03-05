<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Goods;
use App\Cart;
use App\Order;
use DB;
class PayController extends Controller
{
    //
    function index($goods_id){
        $goods_id=explode(',',$goods_id);
        $GoodsInfo=Cart::leftjoin('goods','cart.goods_id','=','goods.goods_id')
                ->whereIn('goods.goods_id',$goods_id)
                ->get();
        $money=0;
        foreach($GoodsInfo as $k=>$v){
            $money+=$v['buy_number']*$v['goods_price'];
        }
        $money=$money*0.5;
        $goods_id=array_column($GoodsInfo->toArray(),'goods_id');
        $goods_id=implode(',',$goods_id);
        $goods_id=htmlspecialchars($goods_id);
        return view('pay.index',['GoodsInfo'=>$GoodsInfo,'time'=>time(),'money'=>$money,'goods_id'=>$goods_id]);
    }
    function confirmOrder(){
        $goods_id=request()->goods_id;
        if(empty($goods_id)){
            fail('商品id不能为空');
        }else{
            $goods_id=explode(',',$goods_id);
            foreach($goods_id as $k=>$v){
                $where=[
                    ['user_id','=',session('user_id')],
                    ['cart_del','=',1],
                    ['goods_id','=',$v],
                ];
                $CartInfo=Cart::where($where)->first();
                if(empty($CartInfo)){
                    echo json_encode(['code'=>2,'font'=>'数据错误']);
                }
            }
        }
        $money=0;
        $where=[
            ['user_id','=',session('user_id')],
            ['cart_del','=',1],
        ];
        $GoodsInfo=Cart
            ::from('cart as c')
            ->leftJoin('goods as g','g.goods_id','=','c.goods_id')
            ->where($where)
            ->whereIn('g.goods_id',$goods_id)
            ->select('g.goods_id','goods_name','goods_img','goods_imgs','goods_num','goods_price','buy_number')
            ->get();
        foreach($GoodsInfo as $k=>$v){
            $money+=$v['buy_number']*$v['goods_price'];
        }
        $money=$money*0.5+10;
        DB::beginTransaction();
        $order_no=time().'1'.rand(1000,9999).session('user_id');
        $OrderInfo=[
            'pay_type'=>1,
            'order_money'=>$money,
            'order_no'=>$order_no,
            'user_id'=>session('user_id'),
            'order_time'=>time()
        ];
        $res1=Order::create($OrderInfo);
        if(empty($res1)){
            DB::rollback();
            echo json_encode(['code'=>2,'font'=>'添加订单表失败']);
        }
        $where=[
            ['user_id','=',session('user_id')],
        ];
        $res4=Cart::where($where)->whereIn('goods_id',$goods_id)->update(['cart_del'=>2]);
        // $res4=false;//测试事务,使用事务前提是表的存储引擎必须为innodb
        if(empty($res4)){
            DB::rollback();
            echo json_encode(['code'=>2,'font'=>'清空购物车失败']);
        }
        foreach($GoodsInfo as $k=>$v){
            $where=[
                ['goods_id','=',$v['goods_id']]
            ];
            $res5=Goods::where($where)->decrement('goods_num',$v['buy_number']);
            if(empty($res5)){
                DB::rollback();
                echo json_encode(['code'=>2,'font'=>'减少库存失败']);
            }
        }
        DB::commit();
        $arr=['code'=>1,'font'=>'下单成功'];
        echo json_encode($arr);die;
    }

    function success(){
        $OrderInfo=Order::first();
        return view('pay.success',['OrderInfo'=>$OrderInfo]);
    }
    function payMoney($ordersn){
        $OrderInfo=Order::where('order_no','=',$ordersn)->first();
        require_once app_path('lib/wappay/service/AlipayTradeService.php');
        require_once app_path('lib/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php');
        $config=config('alipay');
        $out_trade_no=$ordersn;
        if (!empty($out_trade_no)&& trim($out_trade_no)!=""){
            //商户订单号，商户网站订单系统中唯一订单号，必填

            //订单名称，必填
            $subject = '1908商城';

            //付款金额，必填
            $total_amount = $OrderInfo->order_money;

            //商品描述，可空
            $body = '';

            //超时时间
            $timeout_express="1m";

            $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($out_trade_no);
            $payRequestBuilder->setTotalAmount($total_amount);
            $payRequestBuilder->setTimeExpress($timeout_express);

            $payResponse = new \AlipayTradeService($config);
            $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
            return ;
        }
    }
    function return_url(){
        echo 444;
    }
}



