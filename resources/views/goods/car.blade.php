@extends('layouts.shop')
@section('title','商品结算页')
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
    <table class="shoucangtab">
        <tr>
            <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
            <td width="25%" align="center" style="background:#fff url(/static/index/images/xian.jpg) left center no-repeat;">
                <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
            </td>
        </tr>
    </table>
    @foreach($CartInfo as $v)
    <div class="dingdanlist">
        <table>
            <tr>
                <td width="100%" colspan="4"><a href="javascript:;"><input type="checkbox" name="1" /> 全选</a></td>
            </tr>
            <tr goods_num="{{$v->goods_num}}" goods_id="{{$v->goods_id}}">
                <td width="4%"><input type="checkbox" name="1" class="box"/></td>
                <td class="dingimg" width="15%"><img src="{{env('UPLOAD_URL')}}{{$v->goods_img}}" /></td>
                <td width="50%">
                    <h3>{{$v->goods_name}}</h3>
                    <time>下单时间:{{date('Y-m-d h:i:s',$v->add_time)}}</time>
                </td>
                <td align="right">
                        <button class="less">-</button>
                        <input type="text" value="{{$v->buy_number}}" class="buy_number" size="3">
                        <button class="add">+</button>
                    {{--<input type="text" value="1111111" class="spinnerExample"/>--}}
                </td>
            </tr>
            <tr>
                <th colspan="4"><strong class="orange"><font class="total">{{$v->goods_price*0.5*$v->buy_number}}</font></strong></th>
            </tr>
        </table>
    </div><!--dingdanlist/-->
    @endforeach
    <div class="height1"></div>
    <div class="gwcpiao">
        <table>
            <tr>
                <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
                <td width="50%">总计：<strong class="orange" id="money">¥0</strong></td>
                <td width="40%"><a href="javascript:;" class="jiesuan">去结算</a></td>
            </tr>
        </table>
    </div><!--gwcpiao/-->
</div><!--maincont-->
<script src="/jquery.min.js"></script>
<script>
    $(function(){
        $.ajaxSetup({ headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        $(document).on('click','.add',function(){
           var _this=$(this);
           var buy_number=parseInt(_this.prev("input").val());
            var goods_num=parseInt(_this.parents("tr").attr("goods_num"));
            if(buy_number>=goods_num){
                _this.prev("input").val(goods_num);
                return false;
            }else{
                buy_number=buy_number+1;
                _this.prev("input").val(buy_number);
            }
            var goods_id=_this.parents("tr").attr("goods_id");
            changeNumber(goods_id,buy_number);
            getTotal(goods_id,_this);
            trChecked(_this);
            getMoney();
        });
        $(document).on("click",'.less',function(){
            var _this=$(this);
            var buy_number=parseInt(_this.next("input").val());
            if(buy_number<=1){
                _this.next("input").val(1);
                return false;
            }else{
                buy_number=buy_number-1;
                _this.next("input").val(buy_number);
            }
            var goods_id=_this.parents("tr").attr("goods_id");
            changeNumber(goods_id,buy_number);
            getTotal(goods_id,_this);
            trChecked(_this);
            getMoney();
        });
        $(document).on("blur",'.buy_number',function(){
            var _this=$(this);
            var buy_number=_this.val();
            var goods_num=parseInt(_this.parents("tr").attr("goods_num"));
            var reg=/^\d+$/;
            if(buy_number==''||!reg.test(buy_number)||parseInt(buy_number)<1){
                _this.val(1);
            }else if(parseInt(buy_number)>=goods_num) {
                _this.val(goods_num);
            }else{
                _this.val(parseInt(buy_number));
            }
            buy_number=_this.val();
            var goods_id=_this.parents("tr").attr("goods_id");
            changeNumber(goods_id,buy_number);
            getTotal(goods_id,_this);
            trChecked(_this);
            getMoney();
        });
        $(document).on('click','.box',function(){
            var box=$('.box').parents('tr').attr('goods_id');
            getMoney();
        })
        function trChecked(_this){
            _this.parents("tr").find(".box").prop("checked",true);
        }
        function changeNumber(goods_id,buy_number){
            $.ajax({
                url:'/cart/changeNumber',
                data:{goods_id:goods_id,buy_number:buy_number},
                type:'post',
                dataType:'json',
                async:false,
                success:function(res){
                    if(res.code==2){
                        alert(res.font);
                    }
                }
            })
        }
        function getTotal(goods_id,_this){
            $.ajax({
                url:"/cart/getTotal",
                data:{goods_id:goods_id},
                type:'post',
                dataType:'json',
                success:function(res){
                    if(res.code==1){
                     _this.parents('tr').next('tr').find('font').text(res.font); //font是json返回来的那个数字
                    }else{
                        alert(res.font);
                    }
                }
            })
        }
        function getMoney() {
            var box = $(".box:checked");
            if (box.length < 1) {
                $("#money").text("￥0");
                return false;
            }
            var goods_id = '';
            box.each(function (index) {
                goods_id += $(this).parents("tr").attr("goods_id") + ',';
            });
            goods_id = goods_id.substr(0, goods_id.length - 1);
            $.ajax({
                url: "/cart/getMoney",
                data: {goods_id: goods_id},
                type: 'post',
                success: function (res) {
                    $("#money").text('¥'+res);
                }
            })
        }
        $(document).on('click','.jiesuan',function(){
            var box = $(".box:checked");
            if(box.length<1){
                alert('请选择商品');
                return false;
            }
            var goods_id = '';
            box.each(function (index) {
                goods_id += $(this).parents("tr").attr("goods_id") + ',';
            });
            goods_id = goods_id.substr(0, goods_id.length - 1);
            location.href='/pay/'+goods_id;
        })
    })
</script>
    @endsection