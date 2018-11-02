<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 08:54
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\TopicModel;
use think\File;
use think\Request;
class Topic extends Common{
    /*
     *题库列表
     * */
    public function index(){
        $where=[];
        $this->parameter['name']=input('name');
        if($this->parameter['name']){
            $where['name']=['like','%'.$this->parameter['name'].'%'];
        }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('School')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $topic=new TopicModel();
        $dataList=$topic->getTopicList($where,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        if(input('get.page'))
        {
            return json($dataList);
        }
        return $this->fetch();
    }

    /*
     * 添加考题
     * */
    public function add(){
        if(request()->isPost() && request()->isAjax()){
            $items=input('post.items/a',[]);
            $param['name']=input('post.name','');
            $param['items']=$this->encode_json($items);
            $param['value']=strtoupper(input('post.value'));
            $topic=new TopicModel();
            $msg=$topic->addTopic($param);
            return json($msg);
        }else{
            return view();
        }
    }

   public function edit(){
       $topic=new TopicModel();
       if(request()->isPost() && request()->isAjax()){
           $items=input('post.items/a',[]);
           $param['name']=input('post.name','');
           $param['items']=json_encode($items,JSON_UNESCAPED_UNICODE);
           $param['value']=strtoupper(input('post.value'));
           $param['id']=input('post.id');

           $msg=$topic->editTopic($param);
           return json($msg);
       }else{
           $id=input('id');
           if(!$id){
               json(['code'=>0,'msg'=>'参数错误']);
           }
           $dataInfo=$topic->getOne($id);
           $dataInfo['items']=json_decode($dataInfo['items'],true);
           $this->assign('dataInfo',$dataInfo);
           return view();
       }
   }

    public function encode_json($str){
        $code = json_encode($str);
        $code=preg_replace("#\\\u([a-f]+)#ie", "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))", $code);
        $code=str_replace("\\/","/",$code);
        return $code;
    }
    /*
  * 删除题库
  * */
    public function del(){
        if(request()->isGet()){
            $id=input('param.id');
            if(!$id){
                return json(['code'=>0,'msg'=>'参数错误']);
            }
            $topic=new TopicModel();
            $msg=$topic->delTopic($id);
            return json($msg);
        }
    }

    /*
 * 批量删除
 * */
    public function batchDel(){
        $ids=input('param.ids');
        if(request()->isGet()){
            $ids=rtrim($ids,',');
            $topic=new TopicModel();
            $msg=$topic->batchDelTopic($ids);
            return json(['code'=>$msg['code'],'msg'=>$msg['msg'],'url'=>url('Topic/index')]);
        }
    }

    public function daoru(){
        header("Content-type:text/html;charset=utf-8");
        if(request()->isPost()){
            $path='';
            if (!empty($_FILES['name']['name'])){

                $file_types = explode ( ".", $_FILES ['name'] ['name'] );
                $file_type = $file_types [count ( $file_types ) - 1];
                if (strtolower ( $file_type ) != "xls" && strtolower ( $file_type ) != "xlsx"){
                    $this->error ( '不是Excel文件，重新上传' );
                }
                $file = request()->file('name');
                $info = $file->move(ROOT_PATH . 'public/uploads/images');

                if($info){
                    $path= ROOT_PATH . 'public/uploads/images/'. $info->getSaveName();
                }
            }
            if($path==''){
                $this->error('上传出错');
            }

            Vendor("Excel.PHPExcel");
            $excel=new \PHPExcel();
          //  $excel = \PHPExcel_IOFactory::createReader('Excel5');
            if (strtolower ( $file_type ) =='xlsx') {
                $excel = new \PHPExcel_Reader_Excel2007();
            } else if (strtolower ( $file_type ) =='xls') {
                $excel = new \PHPExcel_Reader_Excel5();
            }
            $excel->setReadDataOnly(true);
            $objPHPExcel = $excel->load($path);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $highestColumn = $objWorksheet->getHighestColumn();
            $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);
            $excelData = array();
            for ($row = 1; $row <= $highestRow; $row++) {
                for ($col = 0; $col < $highestColumnIndex; $col++) {
                    $excelData[$row][] =(string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                }
            }
            /**取得一共有多少行*/
            $allRow = $objWorksheet->getHighestRow();

            $str="A B C D E F G H I J K L M N O P Q R S T U V W X Y Z";
            $arr_abc=explode(" ",$str);

            for($i=0;$i<count($arr_abc);$i++){
                $name=trim($objWorksheet->getCell($arr_abc[$i]."1")->getValue());
                if($name=="标题"){
                    $json["name"]["field_name"]="name";
                    $json["name"]["title"]=$name;
                    $json["name"]["cell"]=$arr_abc[$i];
                }
                if($name=="正确答案"){
                    $json["value"]["field_name"]="value";
                    $json["value"]["title"]=$name;
                    $json["value"]["cell"]=$arr_abc[$i];
                }

                if($name=="选项1"){
                    $json["items"]["options"][0]["cell"]=$arr_abc[$i];
                    $json["items"]["field_name"]="items";
                    $json["items"]["title"]=$name;
                }
                if($name=="选项2"){
                    $json["items"]["options"][1]["cell"]=$arr_abc[$i];
                    $json["items"]["field_name"]="items";
                    $json["items"]["title"]=$name;
                }
                if($name=="选项3"){
                    $json["items"]["options"][2]["cell"]=$arr_abc[$i];
                    $json["items"]["field_name"]="items";
                    $json["items"]["title"]=$name;
                }
                if($name=="选项4"){
                    $json["items"]["options"][3]["cell"]=$arr_abc[$i];
                    $json["items"]["field_name"]="items";
                    $json["items"]["title"]=$name;
                }
            }
            for($currentRow =2;$currentRow<=$allRow;$currentRow++){ //获取excel文件数据到数组
                foreach($json as $key=>$val){
                    $cell=$val["cell"];
                    if(isset($val["cell"])){
                        $data[$val["field_name"]]=trim($objWorksheet->getCell($cell.$currentRow)->getValue());
                    }else{
                        if(isset($val["options"])){
                            $ite_str="";
                            $index=0;
                            foreach($val["options"] as $k=>$v){
                                $option=trim($objWorksheet->getCell($v["cell"].$currentRow)->getValue());
                                if($option!=""){
                                    $title=$option;
                                    $lable=$arr_abc[$index];
                                    $ite='{"value":"'.$lable.'","title":"'.$title.'"}';
                                    if($ite_str!=""){
                                        $ite_str.=",".$ite;
                                    }else{
                                        $ite_str=$ite;
                                    }
                                    $index++;
                                }
                            }
                            if($ite!=""){
                                $ite_str="[".$ite_str."]";
                            }
                            $data[$val["field_name"]] = $ite_str;
                        }
                    }
                }
               // unset($data);
               $lists[]=array(
                   'name' => $data['name'],
                   'value'=>$data['value'],
                   'items'=>$data['items'],
                   'create_time'=> time(),
               );
            }
            if($lists){
                $topic=new TopicModel();
                $dataList=$topic->saveAll($lists);
                $count=count($dataList);
                return json(['code'=>1,'msg'=>"导入".$count.'条数据成功','url'=>url('Topic/index')]);
            }else{
                return json(['code'=>0,'msg'=>'数据有问题']);
            }
        }
        return $this->fetch();
    }

}