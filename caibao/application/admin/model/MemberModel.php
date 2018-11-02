<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 09:56
 */
namespace app\admin\model;
use think\exception\ErrorException;
use think\exception\PDOException;
use think\Model;
use think\Db;
class MemberModel extends Model{
    protected $name="member";
    /*
     * 获取会员列表
     * */
    public function getMemberlist($where,$Nowpage,$limit,$order){
        $field="a.*,b.name,c.title";
        $dataList=$this->alias('a')->field($field)->join('cc_school b','b.id=a.sid','LEFT')
                                        ->join('cc_school_class c','c.id=a.cid','LEFT')
                                        ->where($where)
                                        ->order($order)
                                        ->page($Nowpage,$limit)
                                        ->select();
        return $dataList;
    }

    /*
     * 统计各校答题结果
     * */
    public function countShoolOutcome($where='abs_fenshu DESC',$Nowpage,$limit){
        $np=$Nowpage-1;
        $sql="SELECT t.id,t.`name`,t.people,t.zongfen,FORMAT(t.abs_fenshu,2) abs_fenshu  FROM(select id,`name`,
              (SELECT count(*) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id) as people,
              (SELECT sum(an.fenshu) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id) as zongfen,
              ((SELECT sum(an.fenshu) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id)/
              (SELECT count(*) FROM cc_answer an LEFT JOIN cc_member me ON an.uid=me.id WHERE me.sid=cc_school.id)) as abs_fenshu from cc_school ORDER BY $where) t LIMIT $np,$limit";

        $dataList=\think\Db::query($sql);
        return $dataList;
    }
}