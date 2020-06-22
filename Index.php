<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Index extends Controller
{
    public function index()
    {
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';

    }
    public function login(){
        //return $this->fetch('holle',['name'=>'thinkphp']);
        return view();
    }

    public function register(){
        /*$info = array(
            'name'=>'张三',
            'age' => 14
        );
        $this->assign('info', $info);*/
        return view();
    }
    public function admin(){
        $id ="";
        if(isset($_GET['id']))
	    {
		$id = $_GET['id'];
        } 
        $uname=trim(input('username'));
        $pasw=trim(input('password'));
        $bool = self::login_check($uname, $pasw);
        if($id==null){
          if($bool){
            $this->assign('info',$uname);
            return view();
          }else{
            $this->error('登陆失败！');
        }
       }else{
        $this->assign('info',$id);
        return view();
    }
    }
    public function test3(){
        $list=Db::query("select * from register ");
        $this->assign('info',$list);
        return view();
    }
    //删除事件
    public function delete(){
        if(isset($_GET['id']))
	    {
		$id = $_GET['id'];
	    } 
        $dele=Db::execute("delete  from register where Id=? ",[$id]);
        if($dele){
            $this->error('删除成功!');
            return view();
        }else {
            $this->error('删除失败!');
            return view();
        }
    }
    //admin删除事件
    public function admin_delete(){
		$id = $_GET['id'];
        $de=Db::execute("delete  from register where Id=? ",[$id]);
        if($de){
            return true;
        }else{
            return false;
        }
    
    }
    //admin登陆判断事件
    public function login_check($uname, $passw){
        $sql = Db::query("select * from register where Name=? and Password=? ",[$uname,$passw]);
        if($sql){
            return true;
        }else {
            return false;
        }
        
    }
    //admin 修改
    public function admin_update(){
        $id="";
        $a="";
        $this->assign('close',$a);
        if(isset($_GET['id']))
	    {
		$id = $_GET['id'];
        } 
        $pasw=trim(input('pasw'));
        $ID=substr($id,0,strpos($id, ','));
        $b=substr($id,strpos($id, ',')+1);
        $Name=substr($b,0,strpos($b, ','));
        $passw=trim(strrchr($id, ','),',');
        $this->assign('info1',$Name);
        $this->assign('info2',$passw);
        $this->assign('info3',$id); 
        if($pasw==null){
             return view();
         }else{
            $bool = self::update($ID, $pasw);
            if($bool){
                $passw=$pasw;
                echo '<script>alert("修改成功！！")</script>';
                $this->assign('info2',$passw);
                return view();
            } else{
                $this->error('修改失败');
            }
         }
    }  
    //admin 修改事件
    public function update($ID,$pasw){
            $res = Db::execute('update register set Password=? where Id=?',[$pasw,$ID]);
            if($res){
                return   true;
            }else{
                return  false;
            }
            
    }
    //admin 注册事件
    public function register_check(){
        $Name=trim(input('id'));
        $passw=trim(input('pasw'));
        $alarm=trim(input('alarm'));
        $sql=Db::query('select * from register where Name=?',[$Name]);
        if($sql){
            $this->error('该账户已被注册','register');
        }else{
        
            $sql1 =Db::execute('insert into register (`Name`,`Password`,`Alarm`) values (?,?,?);',[$Name,$passw,$alarm]);
            $this->error('注册成功','login');

        }
        
    }
    
    
    //admin获取数据表单
    public function admin_list(){
        //connect(数据库)->name(表名)->where(条件)->field(参数)->limit(条件)->
        //$data=Db::query("select * from register ");   
        $post = $_REQUEST;
        // 定义数组和总数
        $arr = array();
        // 分页条件
        $page = $post['page'] ? $post['page'] : 1;
        $pageSize = $post['limit'] ? $post['limit'] : 10;

        $data =db('register')
            ->where('1=1')
            ->field('Id,Name,Password,Alarm,Time')
            ->paginate(array('list_rows'=>$pageSize,'page'=>$page))
            ->toArray();
        echo json_encode(array('code'=>0, 'msg'=>'', 'count'=>$data['total'], 'data'=>$data['data']));

    }
    public function admin_form(){
        $id="";
        if(isset($_GET['id']))
	    {
		$id = $_GET['id'];
        } 
        if($id!=""){
        $this->assign('info',$id);
        return view();
    }else{
        $this->error('请通过正常渠道进入！','login');
    }
    }
    public function admin_time(){
        $id="";
        if(isset($_GET['id']))
	    {
		$id = $_GET['id'];
        } 
        if($id!=""){
        $this->assign('info',$id);
        return view();
    }else{
        $this->error('请通过正常渠道进入！','login');
    }
    }
    public function admin_test(){
        $post = input('post.');
        print_r($post);exit;
        $info=array(
        '$test1'=>$_POST['title']
       ,'$test2'=>$_POST['username']
       ,'$test3'=>$_POST['phone']
       ,'$test4'=>$_POST['email']
       ,'$test5'=>$_POST['number']
       ,'$test6'=>$_POST['number']
       ,'$test7'=>$_POST['date']
       ,'$test8'=>$_POST['url']
       ,'$test9'=>$_POST['identity']
       ,'$test10'=>$_POST['password']
       ,'$test11'=>$_POST['price_min']
       ,'$test12'=>$_POST['price_max']
       ,'$test13'=>$_POST['quiz']
       ,'$test14'=>$_POST['modules']
       ,'$test15'=>$_POST['quiz1']
       ,'$test16'=>$_POST['quiz2']
       ,'$test17'=>$_POST['quiz3']
       ,'$test18'=>isset($_POST['close'])?$_POST['close']:''
       ,'$test19'=>$_POST['open']
       ,'$test20'=>$_POST['sex']
       ,'$test21'=>$_POST['text1']
       ,'$test22'=>$_POST['like']
    );
        dump($info);
        return view();
    }
}
