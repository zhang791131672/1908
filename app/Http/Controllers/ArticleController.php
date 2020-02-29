<?php

namespace App\Http\Controllers;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Article;
use App\Category;
use Illuminate\Support\Facades\Cache;
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $page=request()->page??1;
        $a_title=request()->a_title??'';
        $c_id=request()->c_id??'';
        $where=[];
        if($a_title){
            $where[]=['a_title','like',"%".$a_title."%"];
        }
        if($c_id){
            $where[]=['category.c_id','=',$c_id];
        }
        //Cache::flush();die;
        $cateInfo=Cache::get('cateInfo');
        if(!$cateInfo){
            //echo "数据库";
            $cateInfo=Category::get();
            Cache::put('cateInfo',$cateInfo,60*5);
        }
        $data=Cache::get('data_'.$page.'_'.$a_title.'_'.$c_id);
        dump($data);
        if(!$data){
            echo '数据库';
            $pagesize=config('app.pagesize');
            $data=Article::leftJoin('category','article.c_id','=','category.c_id')->where($where)->paginate($pagesize);
            Cache::put('data_'.$page.'_'.$a_title.'_'.$c_id,$data,60*5);
        }
        dump(request()->ajax());
        if(request()->ajax()){
            return view('article.ajaxpage',['data'=>$data,'cateInfo'=>$cateInfo,'a_title'=>$a_title,'c_id'=>$c_id]);
        }
        return view('article.index',['data'=>$data,'cateInfo'=>$cateInfo,'a_title'=>$a_title,'c_id'=>$c_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $cateInfo=Category::get();
        return view('article.create',['cateInfo'=>$cateInfo]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'a_title' => 'unique:article|regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u',
                'c_id' => 'required',
                'a_important' => 'required',
                'a_show' => 'required',
            ],[
                'a_title.unique'=>'文章标题已存在',
                'a_title.regex'=>'文章标题可以是中文字母数字下划线组成',
                'c_id.required'=>'文章分类不能为空',
                'a_important.required'=>'文章重要性不能为空',
                'a_show.required'=>'是否显示不能为空',
        ]);
        $data=$request->except('_token');
        if ($request->hasFile('a_photo')) {
            //
            $data['a_photo']=upload('a_photo');
             }
            $res=Article::create($data);
        if($res){
            return redirect('/article/index');
        }
    }

    public function upload($filename){
        if (request()->file($filename)->isValid()) {
            $photo = request()->file($filename);
            $store_result = $photo->store('upload');
            return $store_result;
        }
        exit('未获取到上传文件或上传过程出错');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $cateInfo=Category::get();
        $data=Article::find($id);
        return view('article.edit',['data'=>$data,'cateInfo'=>$cateInfo]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate(
            [
                'a_title' => [
                    'regex:/^[\x{4e00}-\x{9fa5}a-zA-Z0-9_]+$/u',
                     Rule::unique('article')->ignore($id,'a_id'),
                    ],
                'c_id' => 'required',
                'a_important' => 'required',
                'a_show' => 'required',
            ],[
            'a_title.unique'=>'文章标题已存在',
            'a_title.regex'=>'文章标题可以是中文字母数字下划线组成',
            'c_id.required'=>'文章分类不能为空',
            'a_important.required'=>'文章重要性不能为空',
            'a_show.required'=>'是否显示不能为空',
        ]);
        $data=$request->except('_token');
        if ($request->hasFile('a_photo')) {
            //
            $data['a_photo']=$this->upload('a_photo');
        }
        $res=Article::where('a_id',$id)->update($data);
        if($res!==false){
            return redirect('/article/index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
        $id=request()->a_id;
        $res=Article::destroy($id);
        if($res){
            echo json_encode(['code'=>'0000','msg'=>'ok']);
        }
    }
    /**
     *js验证唯一性
     */
    public function checkOnly(){
        $title=request()->title;
        $where=[];
        if($title){
            $where[]=['a_title','=',$title];
        }
        $a_id=request()->a_id;
        if($a_id){
            $where[]=['a_id','!=',$a_id];
        }
        $count=Article::where($where)->count();
        echo json_encode(['code'=>'00000','msg'=>'ok','count'=>$count]);die;
    }
}


