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
                <td width="50%">总计：<strong class="orange" id="money">¥69.88</strong></td>
                <td width="40%"><a href="pay.html" class="jiesuan">去结算</a></td>
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
            if(buy_number>=goods_num){  //如果购买数量大于等于库存
                _this.prev("input").val(goods_num);   //把购买数量变成库存
                return false;
            }else{
                buy_number=buy_number+1;       //否则的话把购买数量+1
                _this.prev("input").val(buy_number);   //然后放回文本框里
            }
            var goods_id=_this.parents("tr").attr("goods_id");
            changeNumber(goods_id,buy_number);
            //重新获取小计(传一个商品id和_this,不能直接在前台用单价*数量,因为别人可以用F12更改,所以传到控制器进行处理)
            getTotal(goods_id,_this);
            //当前复选框状态变为选中
            trChecked(_this);
            //重新获取总价
            getMoney();
        });
        $(document).on("click",'.less',function(){
            var _this=$(this);   //当前点击的-号
            var buy_number=parseInt(_this.next("input").val()); //获取购买数量的值
            if(buy_number<=1){       //如果小于等于1
                _this.next("input").val(1);  //把购买数量变成1
                return false;            //程序终止
            }else{
                buy_number=buy_number-1;     //否则正常-1
                _this.next("input").val(buy_number); //把-1后的值放回去
            }
            //修改数据库的购买数量
            //获取商品id
            var goods_id=_this.parents("tr").attr("goods_id");
            changeNumber(goods_id,buy_number);
            //重新获取小计
            getTotal(goods_id,_this);
            //当前复选框状态变为选中
            trChecked(_this);
            //重新获取总价
            getMoney();
        });
        $(document).on("blur",'.buy_number',function(){
            var _this=$(this);    //当前失去焦点的文本框
            var buy_number=_this.val(); //获取购买数量的值
            var goods_num=parseInt(_this.parents("tr").attr("goods_num")); //获取到库存的值
            var reg=/^\d+$/;            //正则验证是不是数字
            if(buy_number==''||!reg.test(buy_number)||parseInt(buy_number)<1){     //如果是空,不是数字,小于1的话
                _this.val(1);                                                       //把值变为1
            }else if(parseInt(buy_number)>=goods_num) {                             //如果大于等于库存
                _this.val(goods_num);                                               //值变为库存
            }else{
                _this.val(parseInt(buy_number));                                    //如果用户输入0几,就把它转化一下放回去
            }
            buy_number=_this.val();
            var goods_id=_this.parents("tr").attr("goods_id");  //获取商品id
            //修改数据库的购买数量(不管是加还是减,直接改数据库的值,传过去一个商品id和购买数量)
            changeNumber(goods_id,buy_number);
            //重新获取小计(传一个商品id和_this,不能直接在前台用单价*数量,因为别人可以用F12更改,所以传到控制器进行处理)
            getTotal(goods_id,_this);
            //当前复选框状态变为选中
            trChecked(_this);
            //重新获取总价
            getMoney();
        });
        function trChecked(_this){
            _this.parents("tr").find(".box").prop("checked",true);
        }
        function changeNumber(goods_id,buy_number){
            $.ajax({
                url:'/cart/changeNumber',
                data:{goods_id:goods_id,buy_number:buy_number},
                type:'post',
                dataType:'json',
                async:false,  //这里用同步,必须先把购买数量改完才能进行下一步的计算小计
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
                //$("#money").text("￥0");
                return false;           //如果没有选择,程序终止
            }
            var goods_id = '';            //设置空串
            box.each(function (index) {   //each循环,得到每一个商品id
                goods_id += $(this).parents("tr").attr("goods_id") + ',';//拼接商品id
            });
            goods_id = goods_id.substr(0, goods_id.length - 1);//把最后一个逗号去掉
            $.ajax({
                url: "/cart/getMoney",
                data: {goods_id: goods_id},//ajax传值,传过去的可能是一个值,也可能是多个
                type: 'post',
                success: function (res) {
                    $("#money").text(res);//用返回值替换总价
                }
            })
        }
    })
</script>
    @endsection