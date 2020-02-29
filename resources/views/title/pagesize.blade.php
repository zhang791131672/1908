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