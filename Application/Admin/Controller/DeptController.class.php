<?php
namespace Admin\Controller;
use Think\Controller;
class DeptController extends CommonController{
    //实例化DeptModel 普通的实例化 不推荐
    public function shilihua()
    {
        $model=new \Admin\Model\DeptModel();
        dump($model);
    }

    //thinkphp快速实例化模型的方法M和D方法
    public function shilihua2(){
        $model=D('Dept');//指定模型名
//        $model=D();//未指定模型名，实例化父类的模型model
//        $model=M('Dept');//指定表名,关联表名,实例化父类
//        $model=M();//不指定表名，实例化父类，用于执行原生SQL语句
        dump($model);
    }

    //添加数据
    public function tianjia(){
        //实例化模型
        $model=M('Dept');
        $data=array(
            'name'=>'财务部',
            'pid'=>'0',
            'sort'=>'2',
            'remark'=>'这是财务部门'
        );
        $result=$model->add($data);//添加一条记录

        $data=array(
            array(
                'name'=>'公关部',
                'pid'=>'0',
                'sort'=>'3',
                'remark'=>'这是公关部门'
            ),
            array(
                'name'=>'总裁办',
                'pid'=>'0',
                'sort'=>'4',
                'remark'=>'这是总裁办部门'
            ),
            array(
                'name'=>'技术部',
                'pid'=>'0',
                'sort'=>'5',
                'remark'=>'这是技术部门'
            )


        );
        $result=$model->addAll($data);//添加多条记录
        dump($result);
    }

    //save方法的使用
    public function xiugai(){
        //实例化模型
        $model=M('Dept');
        $data=array(
            'id'=>'2',
            'sort'=>'22',
            'remark'=>'负责发工资'
        );
        $result=$model->save($data);
        dump($result);
    }

    //修改方法的使用
    public function chaxun()
    {
        //实例化模型
        $model = M('Dept');
//        $data=$model->select();//查询全部
//        $data=$model->select(2,4);//查询id in(2,4)
//        $data=$model->find();//查询第一条记录
        $data=$model->find(1);//查询id=3记录
        dump($data);
    }

    //删除
    public function shanchu(){
        $model=M('Dept');
//        $result=$model->delete(5);//删除id=5的
        $result=$model->delete('3,4');//删除id=3,4的
        dump($result);
    }

    //add方法
    public function add(){
        //判断请求类型
            /**
             * 常用常量 IS_POST,IS_GET,ISAJAX,IS_CGI,IS_PUT
             */
            if (IS_POST){
                //实例化模型
                $model = D('Dept');
                /**
                //获取数据值(方法1)
                $post=I('post.');//想要接受全部数据可以用get.或者post.不知道具体变量名称,相当$_POST
                $result=$model->add($post);
                 */
                /**
                 * 获取数据对象方法2--推荐
                 */
                $data=$model->create();//可以传参可以不传,不传就使用对象的值
                if(!$data){
                    //输出错误提示
//                dump($model->getError());die;
                    $this->error($model->getError());exit;
                }
//                dump($data);die;
            $result=$model->add();
            //判断是否添加成功
            if ($result){
                //成功
                $this->success('添加成功',U('showList'),1);
            }else{
                //失败
                $this->error('添加失败','',1);
            }
        }else{
            //查询出所有顶级部门
            $model=M('Dept');
            $data=$model->where('pid=0')->select();//取出顶级部门
            //给模板添加变量
            $this->assign('data',$data);
            //显示模板
            $this->display();
        }

    }

    //showList方法
    public function showList(){
        //实例化模型
        $model=M('Dept');
        $data=$model->order('sort asc')->select();
//        dump($data);die();
        //二次查询遍历
        foreach ($data as $key =>$value){
            if ($value['pid']>0){
                //查询pid对应的部门名称
                $info=$model->find($value['pid']);
                //将取出的值放在data中
                $data[$key]['deptName']=$info['name'];
            }
        }
        //通过load()加载tree.php文件,在这里因为有bug，所以不用
//        load('@/tree');
//        $data=createTree($data);
//        dump($data);die();
        //分配变量
        $this->assign('data',$data);
//        dump($data);die();
        //显示模板
        $this->display();
    }

    //职能部门edit()方法
    public function edit(){
        /**
         * 常用常量 IS_POST,IS_GET,ISAJAX,IS_CGI,IS_PUT
         */
        if (IS_POST) {
            //实例化模型
            $model=M('Dept');
            /**
             * 获取数据对象方法2--推荐
             */
            $data = $model->create();//可以传参可以不传,不传就使用对象的值
            if (!$data){
                //输出错误提示
                //dump($model->getError());die;
                $this->error($model->getError());exit;}
            //实例化映射记录
            $result=$model->save();
            if ($result!==false){
                $this->success('修改成功',U('showList'),1);exit;
            }else {
                $this->error('修改失败', U('showList'), 1);exit;
            }
        }else{
            //实例化模型
            $model=M('Dept');
            //获取id
            $id=$_REQUEST['id'];
            $data=$model->find($id);
            $info=$model->where('id!='.$id)->select();
            //分配变量
            $this->assign('data',$data);
            $this->assign('info',$info);
//        dump($data);die();
            //展示模板
            $this->display();
        }
    }

    //删除功能
    public function del(){
        //获取参数id
        $id=$_REQUEST['id'];
        echo $id;
        //实例化模型
        $model=M('Dept');
        $result=$model->delete($id);
        if ($result){
            $this->success('删除成功','',1);
        }else{
            $this->error('删除失败','',1);
        }
    }
}
