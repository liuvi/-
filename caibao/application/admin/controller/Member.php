<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/29 0029
 * Time: 08:54
 */
namespace app\admin\controller;
use think\Db;
use app\admin\model\MemberModel;
class Member extends Common{
    /*
     * 用户列表
     * */
    public function index(){
        $where=[];
        $this->parameter['username']=input('username');
        $this->parameter['tel']=input('tel');
        $this->parameter['sid']=input('sid');
        $this->parameter['fenshu']=input('fenshu');
        if($this->parameter['sid']){
            $where['a.sid']=$this->parameter['sid'];
        }
        if($this->parameter['tel']){
            $where['a.tel']=['like','%'.$this->parameter['tel'].'%'];
        }
        if($this->parameter['username']){
            $where['a.username']=['like','%'.$this->parameter['username'].'%'];
        }
        $order='a.create_time';
        if($this->parameter['fenshu']==1){
            $order='a.fract asc';
        }
        if($this->parameter['fenshu']==2){
            $order='a.fract desc';
        }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Member')->alias('a')->where($where)->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $member=new MemberModel();
        $dataList=$member->getMemberlist($where,$Nowpage,$limits,$order);
        if(input('daochu')==1){
            $this->excel($dataList);
        }
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);
        $this->getScholl();
        if(input('get.page'))
        {
            return json($dataList);
        }
        return $this->fetch();
    }

    /*
     * 学校列表
     * */
    public function getScholl(){
        $school=new \app\admin\model\SchoolModel();
        $schoolList=$school->getAllSchoolList();
        $this->assign('schoolList',$schoolList);
    }


//    操作发放奖品

    public function setTopStatus(){
        if(request()->isGet()){
            $id     =input('param.id');
            $status = Db::name('Member')->where('id',$id)->value('prize');//得到字段值判断当前状态
            if($status==1)
            {
                $msg = Db::name('Member')->where('id',$id)->setField(['prize'=>2]);
                return json(['code' => 1, 'msg' => '已发放']);
            }
        }
    }

    //结果统计
    public function outcome(){
        $order='abs_fenshu DESC';
        $this->parameter['fenshu']=input('fenshu');
        if($this->parameter['fenshu']==1){
            $order='abs_fenshu ASC';
        }
        if($this->parameter['fenshu']==2){
            $order='abs_fenshu DESC';
        }
        $member=new MemberModel();
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = config('pagelimit');// 获取总条数
        $count = Db::name('Answer')->count();//计算总页面
        $allpage = intval(ceil($count / $limits));
        $dataList=$member->countShoolOutcome($order,$Nowpage,$limits);
        $this->listAssign['Nowpage']=$Nowpage; //当前页
        $this->listAssign['allpage']=$allpage; //总页数
        $this->listAssign['parameter']=$this->parameter;
        $this->assign($this->listAssign);

        if(input('get.page'))
        {
            foreach ($dataList as & $value){
                $value['abs_fenshu']=$value['abs_fenshu']?$value['abs_fenshu']:0;
            }
            return json($dataList);
        }
        return $this->fetch();
    }

    public function excel($data) {
        Vendor("Excel.PHPExcel");
        $objPHPExcel = new \PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        // set width
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);

        $objPHPExcel->getActiveSheet(0)->getStyle('A')->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER);

        // 设置行高度
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(22);

        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(20);


        // 字体和样式
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2:F2')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);


        // 设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //  合并
        $objPHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $title="学生信息表".date('Y-m-d',time());

        // 表头
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1',$title)
            ->setCellValue('A2','姓名')
            ->setCellValue('B2', '学校')
            ->setCellValue('C2', '班级')
            ->setCellValue('D2', '电话')
            ->setCellValue('E2', '注册时间')
            ->setCellValue('F2', '分数');


        // 内容

        for ($i = 0, $len = count($data); $i < $len; $i++) {
            $objPHPExcel->getActiveSheet(0)->setCellValue('A' . ($i + 3), $data[$i]['username']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('B' . ($i + 3), $data[$i]['name']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('C' . ($i + 3), $data[$i]['title']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('D' . ($i + 3), $data[$i]['tel']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('E' . ($i + 3), $data[$i]['create_time']);
            $objPHPExcel->getActiveSheet(0)->setCellValue('F' . ($i + 3), $data[$i]['fract']);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':K' . ($i + 3))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A' . ($i + 3) . ':K' . ($i + 3))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
            $objPHPExcel->getActiveSheet()->getRowDimension($i + 3)->setRowHeight(16);
        }


        // Rename sheet
        // $objPHPExcel->getActiveSheet()->setTitle($data['examTitle']);

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        // 输出
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' .$title . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }



}