<?php
namespace app\home\controller;
use app\home\model\Answer;
use app\home\model\Record;
use app\home\model\Topic;
use think\Db;
class Activity extends Common
{
    public function _initialize()
    {
        parent::_initialize();

    }

    /*
     * 活动首页
     * */
    public function index(){
       return $this->fetch();
    }

    /*
     * 答题
     * */
    public function topic(){
        $this->checkLogin();
        $where=[];
        $topic=new Topic();
        $dataList=$topic->getTopicList($where,1,50);
        $this->assign('dataList',json_encode($dataList));
        return $this->fetch();
    }

    /*
     * 获取数据
     * */
    public function getList(){
        $user=$this->getUser();
        if($user['opp']==0){
           return json(['code'=>0,'msg'=>'您的答题机会已经用完!']);
        }
        $where=[];
        $topic=new Topic();
        $dataList=$topic->getTopicList($where,1,50);
        foreach ($dataList as &$value) {
            $value['items']=json_decode($value['items']);
        }
        return json(['code'=>1,'dataList'=>$dataList]);
    }

    /*
     * 文章获取
     * */
    public function explain(){
        $pid=input('param.pid');
        if(!$pid){
            $this->error('参数错误 ');
        }
        $dataInfo=Db::name('Article')->where('pid',$pid)->order('id desc')->find();
        $this->assign('dataInfo',$dataInfo);
        return $this->fetch();
    }

    /*
     * 提交答案
     * */

    public function subAnswer(){
        $this->checkLogin();
        $topic=new Topic();
        if(request()->isPost()){
            $Result=input('post.result/a',[]);
            $ids=i_array_column($Result,'id');
            //查询提交的题目答案和分数
            $res=$topic->getTopicRes($ids);
            //这里开始计算分数
            $number = 0;
            $Record=[];
            foreach ($res as $value){
                $indexOf = array_search($value['id'],$ids);
                if($indexOf !== false){
                    //处理是否正确答案
                    if($value['value'] == $Result[$indexOf]['val']){
                        $number+=$value['fenshu'];
                        //剩下的计算分数公式 请自己处理
                    }else{
                        $Record[]=array(
                            'tid'=>$value['id'],
                            'uid'=>$this->uid,
                            'evalue'=>$Result[$indexOf]['val'],
                            'create_time'=>time()
                        );
                    }
                }
            }

            $uid=$this->uid;
            if(!$uid){
                return json(['code'=>0,'msg'=>'答题时间太久登录失效!']);
            }
            $rec=new Record();
            if(!empty($Record)){
                $rec->saveAll($Record);
            }
            $answer=new Answer();
            $aid=Db::name('Answer')->where('uid',$uid)->value('id');
            $param['uid']=$uid;
            $param['fenshu']=$number;
            if($aid){
                $param['id']=$aid;
                $msg=$answer->editAns($param);
                return json($msg);
            }else{
                $msg=$answer->addAns($param);
                return json($msg);
            }
        }
    }

    /*
     * 提交成功
     * */
    public function sub_succ(){
        $this->checkLogin();
        $answer=new Answer();
        $dataInfo=$answer->getRanking($this->uid);
        $dataInfo['user']=$this->getUser();
        $dataInfo['shuomin']=Db::name('Article')->where('pid',2)->value('content');
        $this->assign('dataInfo',$dataInfo);
        Vendor("weixinshare.jssdk");
        $jssdk = new \JSSDK("wxdd0512fd40517786", "c108f088dea7e1c27c28ac14475c0b9a");
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
        return $this->fetch();
    }

    /*
     * take
     * */
    public function take(){
        $this->checkLogin();
        if(request()->isPost()){
            $uid=$this->uid;
            $prize['prize']=1;
            $res=Db::name('Member')->where('id',$uid)->update($prize);
            if(false===$res){
                return json(['code'=>0,'msg'=>'领取失败']);
            }else{
                return json(['code'=>1,'msg'=>'领取成功']);
            }
        }
    }

    //分享记录
    public function share(){
        if(request()->isPost()){
            $uid=session('MemberId');
            $data['uid']=$this->uid;
            $data['create_time']=time();
            $data['status']=input('post.status',0);
            $res=Db::name('ShareLog')->insert($data);
            if(false===$res){
                return json(['code'=>0,'msg'=>'分享失败']);
            }else{
                Db::name('Member')->where('id',$uid)->update(array('opp'=>1,'share_time'=>date('Ymd')));
                return json(['code'=>1,'msg'=>'分享成功']);
            }
        }
    }

    public function ranking(){
        $answer=new Answer();
        $dataList=$answer->getShoolRank();
        $this->assign('dataList',$dataList);
        return $this->fetch();
    }
}
