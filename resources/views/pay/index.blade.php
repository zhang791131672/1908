@extends('layouts.shop')
@section('title','确认订单')
@section('content')
<div class="maincont">
    <header>
        <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
        <div class="head-mid">
            <h1>购物车</h1>
        </div>
    </header>
    <div class="head-top">
        <img src="/static/index/images/head.jpg" />
    </div><!--head-top/-->
    <div class="dingdanlist" onClick="window.location.href='address.html'">
        <table>
            <tr>
                <td class="dingimg" width="75%" colspan="2">新增收货地址</td>
                <td align="right"><img src="/static/index/images/jian-new.png" /></td>
            </tr>
            <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">选择收货时间</td>
                <td align="right"><img src="/static/index/images/jian-new.png" /></td>
            </tr>
            <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">支付方式</td>
                <td align="right"><span class="hui">网上支付</span></td>
            </tr>
            <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">优惠券</td>
                <td align="right"><span class="hui">无</span></td>
            </tr>
            <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">是否需要开发票</td>
                <td align="right"><a href="javascript:;" class="orange">是</a> &nbsp; <a href="javascript:;">否</a></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">发票抬头</td>
                <td align="right"><span class="hui">个人</span></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">发票内容</td>
                <td align="right"><a href="javascript:;" class="hui">请选择发票内容</a></td>
            </tr>
            <tr><td colspan="3" style="height:10px; background:#fff;padding:0;"></td></tr>
            <tr>
                <td class="dingimg" width="75%" colspan="3">商品清单</td>
            </tr>
            @foreach($GoodsInfo as $k=>$v)
            <tr>
                <td class="dingimg" width="15%"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" /></td>
                <td width="50%">
                    <h3>{{$v->goods_name}}</h3>
                    <time>{{date('Y-m-d h:i:s',$time)}}</time>
                </td>
                <td align="right"><span class="qingdan">X {{$v->buy_number}}</span></td>
            </tr>
            <tr>
                <th colspan="3"><strong class="orange">¥{{$v->goods_price*$v->buy_number*0.5}}</strong></th>
            </tr>
            @endforeach
            <tr>
                <td class="dingimg" width="75%" colspan="2">商品金额</td>
                <td align="right"><strong class="orange">¥{{$money}}</strong></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">折扣优惠</td>
                <td align="right"><strong class="green">¥0.00</strong></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">抵扣金额</td>
                <td align="right"><strong class="green">¥0.00</strong></td>
            </tr>
            <tr>
                <td class="dingimg" width="75%" colspan="2">运费</td>
                <td align="right"><strong class="orange">¥ 10</strong></td>
            </tr>
        </table>
    </div><!--dingdanlist/-->


</div><!--content/-->
<div class="height1"></div>
<div class="gwcpiao">
    <table>
        <tr>
            <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
            <td width="50%">总计：<strong class="orange">¥{{$money+10}}</strong></td>
            <td width="40%">
                <a href="javascript:;" class="jiesuan" goods_id="{{$goods_id}}">提交订单</a></td>
        </tr>
    </table>
</div><!--gwcpiao/-->
</div><!--maincont-->
<script src="/jquery.min.js"></script>
<script>
    $(function(){
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $(document).on('click','.jiesuan',function(){
            var goods_id=$(this).attr('goods_id');
            $.ajax({
                url:'/confirmOrder',
                data:{goods_id:goods_id},
                type:'post',
                dataType:'json',
                async:false,
                success:function(res){
                    if(res.code==1){
                        alert(res.font);
                    }
                }
            })
            location.href='/success';
        })
    })
</script>
@endsection
