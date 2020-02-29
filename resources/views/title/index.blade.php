<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
<form>
    <input type="text" name="a_title" value="{{$query['a_title']??''}}" placeholder="请输入文章标题">
    <select name="c_id">
        <option value="">--请选择--</option>
        @php $c_id=$query['c_id']??''; @endphp
        @foreach($Category as $v)
        <option value="{{$v->c_id}}"  {{$v->c_id==$c_id?'selected':''}}>{{$v->c_name}}</option>
         @endforeach
    </select>
    <input type="submit" value="搜索">
</form>
<table border="1px">
    <thead>
    <tr>
        <td>文章id</td>
        <td>文章标题</td>
        <td>文章分类</td>
        <td>文章重要性</td>
        <td>文章内容</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $v)
    <tr>
        <td>{{$v->a_id}}</td>
        <td>{{$v->a_title}}</td>
        <td>{{$v->c_name}}</td>
        <td>{{$v->a_important==1?'重要':'不重要'}}</td>
        <td>{{$v->a_detail}}</td>
        <td><a href="#">删除</a>
            <a href="#">修改</a>
        </td>
    </tr>
    @endforeach
        <tr>
            <td colspan="6">{{$data->appends($query)->links()}}</td>
        </tr>
    </tbody>
</table>
</body>
</html>
<script src="/jquery.min.js"></script>
<script>
    $(function(){
        $(document).on('click','.pagination a',function(){
            var url=$(this).prop('href');
            $.get(
                 url,
                 function(res){
                     $('tbody').html(res);
                 }
            )
            return false;
        })
    })
</script>