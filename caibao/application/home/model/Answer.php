<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 09:56
 */
namespace app\home\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class Answer extends Model{
    protected $resultSetType = 'collection';
    /*
     * 添加记录
     * */
    public function addAns($param){
        try{
            $param['create_time']=time();
            $result = $this->save($param);
            if(false === $result){
                return ['code' => 0, 'data' => '', 'msg' => $this->getError(),'url'=>''];
            }else{
                $data['fract']=$param['fenshu'];
                $data['opp']=0;
                Db::name('Member')->where('id',$param['uid'])->update($data);
                return ['code' => 1, 'data' => '', 'msg' => '提交成功','url'=>url('Activity/sub_succ')];
            }
        }catch (PDOException $e){
            return ['code' => 2, 'data' => '', 'msg' => $e->getMessage(),'url'=>''];
        }
    }

    /*
 * 修改记录
 * */

    public function editAns($param){
        try{
            $result=$this->save($param,['id'=>$param['id']]);
            if(false===$result){
                return ['code'=>0,'data'=>'','msg'=>$this->getError(),'url'=>''];
            }else{
                $data['fract']=$param['fenshu'];
                $data['opp']=0;
                Db::name('Member')->where('id',$param['uid'])->update($data);
                return ['code'=>1,'data'=>'','msg'=>'提交成功','url'=>url('Activity/sub_succ')];
            }
        }catch (PDOException $e){
            return ['code'=>2,'data'=>'','msg'=>$e->getMessage(),'url'=>''];
        }
    }

    /*
     *获取分数排名
     **/
    public function getRanking($uid){
        $fenshu=$this->where('uid',$uid)->value('fenshu');
        $data=$this->order('fenshu desc,create_time asc')->select();
        $rank=0;
        if(!empty($data)){
            $lists=$data->toArray();
            $ids=i_array_column($lists,'uid');
            $rank=array_search($uid,$ids);//当前排名
        }

        return array('fenshu'=>$fenshu,'rank'=>$rank+1);
    }

    /*
     * 按学校平均分排名
     * */
    public function getShoolRank(){
       // $sql="select `name`,(SELECT count(*) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id) as people,(SELECT sum(an.fenshu) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id) as zongfen from cc_school ORDER BY (zongfen/people) desc";
        $sql="SELECT t.id,t.`name`,t.people,t.zongfen,FORMAT(t.abs_fenshu,2) abs_fenshu  FROM(select id,`name`,
              (SELECT count(*) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id) as people,
              (SELECT sum(an.fenshu) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id) as zongfen,
              ((SELECT sum(an.fenshu) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id)/
              (SELECT count(*) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id)) as abs_fenshu from cc_school ORDER BY abs_fenshu DESC) t LIMIT 0,100";
        $dataList=$this->query($sql);
        return $dataList;
    }
}