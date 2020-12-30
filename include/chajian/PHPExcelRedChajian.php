<?php
/**
 * PHPExcel类
 */
include_once(ROOT_PATH . '/include/PHPExcel.php');

class PHPExcelRedChajian extends Chajian
{

    private $excelDemoPath = 'include/excelDemo/';//excel的模板文件

    public function initChajian()
    {

        $this->A = explode(',', 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,AA,AB,AC,AD,AE,AF,AG,AH,AI,AJ,AK,AL,AM,AN,AO,AP,AQ,AR,AS,AT,AU,AV,AW,AX,AY,AZ,BA,BB,BC,BD,BE,BF,BG,BH,BI,BJ,BK,BL,BM,BN,BO,BP,BQ,BR,BS,BT,BU,BV,BW,BX,BY,BZ,CA,CB,CC,CD,CE,CF,CG,CH,CI,CJ,CK,CL,CM,CN,CO,CP,CQ,CR,CS,CT,CU,CV,CW,CX,CY,CZ');
        $this->headWidth = array();
        $this->excel = new PHPExcel();
    }

    public function pinFenHuiZonBiao($pici_id)
    {
        $lie = $this->A;//列
        //表初始化参数
        $start_num = '0';//开始列
        $user_num = m('m_pua_relation')->getall('pici_id=' . $pici_id);//专家列
        $count_user_num = count($user_num);

        $max_num = $count_user_num + 3;//最大列
        $zhuanjia_num = $count_user_num;//专家数量

        $objPHPExcel = new PHPExcel();
        // 设置文件的一些属性,在xls文件——>属性——>详细信息里可以看到这些值,xml表
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', "校级重大项目项目库排序工作\n评分汇总表");
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setWrapText(true);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '序号');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '项目名称');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '专家评分');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$zhuanjia_num + 2] . '2', '平均分');
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$zhuanjia_num + 3] . '2', '排名');

        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:' . $lie[$max_num] . '1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:A3');
        $objPHPExcel->getActiveSheet()->mergeCells('B2:B3');
        $objPHPExcel->getActiveSheet()->mergeCells('C2:' . $lie[$zhuanjia_num + 1] . '2');

        //合并最后两项
        $objPHPExcel->getActiveSheet()->mergeCells($lie[$zhuanjia_num + 2] . '2:' . $lie[$zhuanjia_num + 2] . '3');
        $objPHPExcel->getActiveSheet()->mergeCells($lie[$zhuanjia_num + 3] . '2:' . $lie[$zhuanjia_num + 3] . '3');

        //设置居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num - 1] . '2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num - 1] . '2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num] . '2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num] . '2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //行高
        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(50);

        //字体
        $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(16);

        //表头 END
        //写入数据评委名称
        foreach ($user_num as $k => $value) {
            $admin_info = m('admin')->getone('id=' . $value['uid']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$k + 2] . '3', $admin_info['name']);

        }

        //获取项目
        $pxm_info = m('m_pxm_relation')->getall('pici_id=' . $pici_id);
        foreach ($pxm_info as $key => $row) {
            $volume[$key] = $row['xid'];
            $edition[$key] = $row['pingjunfen'];
        }

        array_multisort($edition, SORT_DESC, $pxm_info);

        foreach ($pxm_info as $k => $value) {
            $project_info = $this->db->getone("whole_projects", "id=" . $value['xid'] . " and num='" . $value['mtype'] . "'");

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[0] . ($k + 4), $k + 1);//序号
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[1] . ($k + 4), $project_info['project_name']);//名称

            //平均分
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$max_num - 1] . ($k + 4), $value['pingjunfen']);//平均分
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$max_num] . ($k + 4), $k + 1);//排名

            $objPHPExcel->getActiveSheet()->getColumnDimension($lie[1])->setWidth(25);

            $objPHPExcel->getActiveSheet()->getStyle($lie[0] . ($k + 4), $k + 1)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($lie[0] . ($k + 4), $k + 1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle($lie[1] . ($k + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($lie[1] . ($k + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num] . ($k + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num] . ($k + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num - 1] . ($k + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_num - 1] . ($k + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $count_user_zongfen = '';
            foreach ($user_num as $ki => $vi) {
                $m_pxmdf_info = m('m_pxmdf')->getone('pici_id=' . $pici_id . '  and uid=' . $vi['uid'] . ' and xid=' . $value['xid'] . ' and mtype=\'' . $value['mtype'] . '\' and com_status=1');
                if ($m_pxmdf_info) {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$ki + 2] . ($k + 4), $m_pxmdf_info['user_zongfen']);
                    $count_user_zongfen .= $m_pxmdf_info['user_zongfen'];//专家分数
                } else {
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$ki + 2] . ($k + 4), '未提交');

                }
                $objPHPExcel->getActiveSheet()->getStyle($lie[$ki + 2] . ($k + 4))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($lie[$ki + 2] . ($k + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        $objPHPExcel->getActiveSheet()->getStyle('A2:' . $lie[$max_num] . (count($pxm_info) + 3))->applyFromArray($styleArray);

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $filename = time();
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="{$filename}.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

    }


    public function pinJiDiJianSheXiangMu($pici_id)
    {
        $lie = $this->A;//列
        $count_sql = "select c.deptname,count(*) as 'number' from pl_m_pxm_relation a left join pl_project_sx_apply  b on a.xid=b.id left join pl_admin c on b.uid=c.id where pici_id=" . $pici_id . "  GROUP BY c.deptname ORDER BY c.deptname";
        $count_dept = $this->db->getall($count_sql);
        $data_sql = "select b.id,b.project_name,b.zhuanyemingcheng,b.project_head,b.head_phone,b.project_select,b.project_select,b.nijianshechangsuo,b.yongfangmianji,b.xuexiaozichou,b.zhijintouruqita,a.zongfen,a.rec_ranking,project_yushuan,b.zx_caizhen,b.qiyetouru,c.deptname,a.pici_id,a.pingjunfen from pl_m_pxm_relation a left join pl_project_sx_apply  b on a.xid=b.id left join pl_admin c on b.uid=c.id where pici_id=$pici_id ORDER BY a.pingjunfen desc,c.deptname asc";
        $data_data = $this->db->getall($data_sql);

        $batch_info = m('m_batch')->getone('id=' . $pici_id);
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($this->excelDemoPath . "sobi_template.xls");

        $objActSheet = $objPHPExcel->getActiveSheet();

        $start = 4;//列开始计算数

        $date_reg = date("Y");
        $str = $batch_info['pici_name'] . '基本信息汇总表';

        $objActSheet->setCellValue('A1', $str); //序号
//			 $sum_data_data=count($data_data);
        foreach ($data_data as $k => $value) {
            $objActSheet->setCellValue('B' . ($k + $start), $k + 1); //序号
            $objActSheet->setCellValue('C' . ($k + $start), $value['project_name']); //项目名称
            $objActSheet->setCellValue('D' . ($k + $start), $value['zhuanyemingcheng']); //所属专业
            $objActSheet->setCellValue('E' . ($k + $start), $value['project_head']); //项目负责人
            $objActSheet->setCellValue('F' . ($k + $start), $value['head_phone']); //项目负责人联系电话
            $objActSheet->setCellValue('G' . ($k + $start), $value['project_select']); //基地类型
            $objActSheet->setCellValue('H' . ($k + $start), $value['yongfangmianji']); //建设面积（平米）
            $objActSheet->setCellValue('I' . ($k + $start), $value['nijianshechangsuo']);//拟建场所
            //总额=学校自筹+企业投入+财政专项+其他
            $total = $value['zx_caizhen'] + $value['xuexiaozichou'] + $value['qiyetouru'] + $value['zhijintouruqita'];
            $objActSheet->setCellValue('J' . ($k + $start), $total / 10000); //总额
            $objActSheet->setCellValue('K' . ($k + $start), $value['zx_caizhen'] / 10000); //财政专项资金
            $objActSheet->setCellValue('L' . ($k + $start), $value['xuexiaozichou'] / 10000); //学校自筹
            $objActSheet->setCellValue('M' . ($k + $start), $value['qiyetouru'] / 10000); //企业投入
            $objActSheet->setCellValue('N' . ($k + $start), $value['zhijintouruqita'] / 10000); //其它
            $objActSheet->setCellValue('O' . ($k + $start), $value['pingjunfen']); //网评分数
//				if(!empty($value['zongfen'])){
//					$objActSheet->setCellValue ( 'N' . ($k+$start), $value['zongfen']); //网评分数
//				}else{
//					$objActSheet->setCellValue ( 'N' . ($k+$start), '无专家评分'); //网评分数
//				}

            $paimin = $this->db->getall("select zongfen	from pl_m_pxm_relation  where pici_id=$pici_id GROUP BY zongfen ORDER BY zongfen desc ");

            $sum_for = 0;
            foreach ($paimin as $kic => $vic) {
                if ($value['zongfen'] == $vic['zongfen'] && !empty($value['zongfen'])) {
                    $sum_for = $kic + 1;
                } else if ($value['zongfen'] == '') {
                    $sum_for = '';
                }
            }

            $objActSheet->setCellValue('P' . ($k + $start), $sum_for); //网评排名

            $objPHPExcel->getActiveSheet()->getStyle('B' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('C' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('D' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('E' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('F' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('H' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('I' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('J' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('J' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('K' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('L' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


            $objPHPExcel->getActiveSheet()->getStyle('M' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('M' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('N' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('N' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('O' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('O' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('P' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('P' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $row = $start;
        foreach ($count_dept as $k => $value) {
            $objActSheet->setCellValue('A' . $row, $value['deptname']); //序号
            $objActSheet->mergeCells('A' . $row . ':A' . ($row + $value['number'] - 1));

            $objActSheet->getStyle('A' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objActSheet->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = $row + $value['number'];
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        $countDataNum = count($data_data) + $start - 1;
        $objPHPExcel->getActiveSheet()->getStyle('A2:P2' . $countDataNum)->applyFromArray($styleArray);
        $filename = time();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
        $objWriter->save('php://output');

    }

    //非实训
    public function pinFeiShiXunChuSheng($pici_id)
    {
        $count_sql = "select c.deptname,count(*) as 'number' from pl_m_pxm_relation a left join pl_project_apply  b on a.xid=b.id left join pl_admin c on b.uid=c.id where pici_id=" . $pici_id . "  GROUP BY c.deptname ORDER BY c.deptname";
        $count_dept = $this->db->getall($count_sql);
        $data_sql = "select b.id,b.project_name,b.project_head,b.project_head_phone,b.project_yushuan,a.rec_ranking,b.major,b.project_select,c.deptname,a.pici_id,a.pingjunfen from pl_m_pxm_relation a left join pl_project_apply  b on a.xid=b.id left join pl_admin c on b.uid=c.id where pici_id=$pici_id ORDER BY a.pingjunfen desc";
        $data_data = $this->db->getall($data_sql);
        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($this->excelDemoPath . "mp_template.xls");
        $objActSheet = $objPHPExcel->getActiveSheet();
        $start = 3;
        $str = date("Y") . '年度校内重大项目（非实现类）初审筛选一览表';

        $objActSheet->setCellValue('A1', $str); //表格标题
        foreach ($data_data as $k => $value) {
            $objActSheet->setCellValue('B' . ($k + $start), $k + 1); //序号
            $objActSheet->setCellValue('C' . ($k + $start), $value['project_name']); //项目名称
            $objActSheet->setCellValue('D' . ($k + $start), $value['project_yushuan'] / 10000); //预算
            $objActSheet->setCellValue('E' . ($k + $start), $value['major']); //所属专业
            $objActSheet->setCellValue('F' . ($k + $start), $value['project_head']); //项目负责人
            $objActSheet->setCellValue('G' . ($k + $start), $value['project_head_phone']); //联系电话
            $objActSheet->setCellValue('H' . ($k + $start), $value['project_select']); //项目类别
            $objActSheet->setCellValue('I' . ($k + $start), $value['pingjunfen']); //网评平均分数
            $objActSheet->setCellValue('J' . ($k + $start), $k + 1); //排名

            $objPHPExcel->getActiveSheet()->getStyle('B' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('C' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('D' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('E' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('F' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('H' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('I' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('J' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('J' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        }

        $row = $start;
        foreach ($count_dept as $k => $value) {
            $objActSheet->setCellValue('A' . $row, $value['deptname']); //序号
            $objActSheet->mergeCells('A' . $row . ':A' . ($row + $value['number'] - 1));

            $objActSheet->getStyle('A' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objActSheet->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = $row + $value['number'];
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        $countDataNum = count($data_data) + $start - 1;
        $objPHPExcel->getActiveSheet()->getStyle('A2:J2' . $countDataNum)->applyFromArray($styleArray);
        $filename = time();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
        $objWriter->save('php://output');

    }

    //专家评分
    public function pinFeiShiXunZhuanJia($pici_id)
    {
        $batch_info = m('m_batch')->getone('id=' . $pici_id);//网评基础信息
        //建立指标临时文件夹
        @mkdir(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $batch_info['pici_name']));
        $pua_relation_info = m('m_pua_relation')->getall('pici_id=' . $pici_id);//参与网评专家
        foreach ($pua_relation_info as $k => $v) {
            //绘制表格
            $batch_model = json_decode($batch_info['model']);
            $title_sum = count($batch_model->info);
            $max_sum = $title_sum + 3;//表格最大列数

            $objReader = PHPExcel_IOFactory::createReader('Excel5');
            $objPHPExcel = $objReader->load($this->excelDemoPath . "pszj_template.xls");

            $objActSheet = $objPHPExcel->getActiveSheet();

            $lie = $this->A;//列
            $start = 4;//列开始计算
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $batch_info['pici_name'] . '评审打分表');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', '序号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B3', '项目名称');

            //制作表头部分
            foreach ($batch_model->info as $k_b => $v_b) {
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$k_b + 2] . '3', $v_b->option_msg . "(" . $v_b->option_fenzhi . "分)");
                $objPHPExcel->getActiveSheet()->getStyle($lie[$k_b + 2] . '3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($lie[$k_b + 2] . '3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$max_sum - 1] . '3', '总分');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lie[$max_sum] . '3', '说明');

            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum - 1] . '3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum - 1] . '3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum] . '3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum] . '3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        //循环批次中专家
        foreach ($pua_relation_info as $k => $v) {
            //专家对应项目-》打分信息表
            $pxmdf_info = m('m_pxmdf')->getall('pici_id=' . $pici_id . ' and uid=' . $v['uid']);//打分表
            $sum_pxmdf_info_count = count($pxmdf_info);

            foreach ($pxmdf_info as $ki => $vi) {
                $project_info = m($vi['mtype'])->getone('id=' . $vi['xid']);//项目信息
                $dafen[$ki]['project_name'] = $project_info['project_name'];//存储项目名称

                //用户打分信息
                if ($vi['com_status'] == 1) {
                    $json_model = json_decode($vi['model'], true);
                    foreach ($json_model['info'] as $kii => $vii) {
                        $dafen[$ki]['info'][$kii] = $vii['user_dafen'];
                    }
                } else {
                    $dafen[$ki]['info'][$ki] = '';
                }
                $dafen[$ki]['user_zongfen'] = $vi['user_zongfen'];
                $dafen[$ki]['com_status'] = $vi['com_status'];
            }

            foreach ($dafen as $k_i => $v_i) {
                $objActSheet->setCellValue('A' . ($k_i + $start), $k_i + 1); //序号
                $objActSheet->setCellValue('B' . ($k_i + $start), $v_i['project_name']); //项目名称

                foreach ($v_i['info'] as $k_s => $v_s) {
                    $objActSheet->setCellValue($lie[$k_s + 2] . ($k_i + $start), $v_s); //分数
                    $objPHPExcel->getActiveSheet()->getStyle($lie[$k_s + 2] . ($k_i + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $objPHPExcel->getActiveSheet()->getStyle($lie[$k_s + 2] . ($k_i + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                }

                $objActSheet->setCellValue($lie[$max_sum - 1] . ($k_i + $start), $v_i['user_zongfen']); //用户打分
                if ($v_i['com_status'] == 3) {
                    $objActSheet->setCellValue($lie[$max_sum] . ($k_i + $start), '未提交'); //负责人
                } else {
                    $objActSheet->setCellValue($lie[$max_sum] . ($k_i + $start), ''); //负责人
                }

                $objPHPExcel->getActiveSheet()->getStyle('A' . ($k_i + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('A' . ($k_i + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle('B' . ($k_i + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B' . ($k_i + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum - 1] . ($k_i + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum - 1] . ($k_i + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum] . ($k_i + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($lie[$max_sum] . ($k_i + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            }

            //合并
            $objActSheet->mergeCells('A1:' . $lie[$max_sum] . '1');
            $objActSheet->mergeCells('A2:' . $lie[$max_sum - 2] . '2');

            $objActSheet->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objActSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objActSheet->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objActSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

            $styleArray = array(
                'borders' => array(
                    'allborders' => array(
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                        'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                        //'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );

            $styleArray2 = array(
                'borders' => array(
                    'leftborders' => array(
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                        'style' => '',//细边框
                        //'color' => array('argb' => 'FFFF0000'),
                    ),

                    'rigthborders' => array(
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                        'style' => '',//细边框
                        //'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );

            $styleArray3 = array(
                'borders' => array(
                    'leftborders' => array(
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                        'style' => '',//细边框
                        //'color' => array('argb' => 'FFFF0000'),
                    ),

                    'rigthborders' => array(
                        //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                        'style' => '',//细边框
                        //'color' => array('argb' => 'FFFF0000'),
                    ),
                ),
            );

            $objPHPExcel->getActiveSheet()->getStyle('A1:' . $lie[$max_sum] . ($sum_pxmdf_info_count + 3))->applyFromArray($styleArray);
            $objPHPExcel->getActiveSheet()->getStyle('A2:' . $lie[$max_sum] . '2')->applyFromArray($styleArray2);
            $objPHPExcel->getActiveSheet()->getStyle('A2:' . $lie[$max_sum - 1] . '2')->applyFromArray($styleArray3);
            $user_in = m('admin')->getone('id=' . $v['uid']);
            $filename = $user_in['name'];
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
            //临时目录存放方式：项目根目录/storage/批次名称/文件
            $objWriter->save(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $batch_info['pici_name']) . '/' . iconv('utf-8', 'gbk', $filename) . '.xls');
        }
        //文件夹打包下载
        $this->download(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $batch_info['pici_name']));
    }

    //立项评审小组评审表
    public function projectStartGroupReviewExcel($pici_id)
    {
        $batch_info = m('m_batch')->getone('id=' . $pici_id);//网评基础信息
        //建立临时文件夹
        @mkdir(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $batch_info['pici_name'].'小组立项评审表'));
        $dirname = $batch_info['pici_name'].'小组立项评审表';
        $batch_data = m('m_batch')->getone("id = $pici_id");
        $projectIds = implode(',',unserialize($batch_data['project_ids']));
        //获取登记号,项目名称,职称,成果形式
        $bill_data = m('flow_bill')->getall("id in ($projectIds)", 'sericnum,id');
        $group_data = array();
        $data = array();

        foreach ($bill_data as $v) {
            $rs = m('expert_review')->getone("secrinum = '" . $v['sericnum'] . "'", 'secrinum,name,position,achievement_type,leader ');
            //获取专家评分,平均分,评审意见,
            $rv = m('m_pxmdf')->getall("pici_id = $pici_id and xid =" . $v['id']);
            foreach ($rv as $va) {
                $json_model = json_decode($va['model'], true);
                $zhibiaoScoreArr = array();
                foreach ($json_model['info'] as $v){
                    array_push($zhibiaoScoreArr,$v['user_dafen']);
                }
                $rs['user_zongfen'] = $va['user_zongfen'];
                $rs['review_opinion'] = $va['review_opinion'];
                $rs['zhibiaoScoreArr'] = $zhibiaoScoreArr;
                array_push($data, $rs);
            }
            array_push($group_data,$data);
        }
        //获取指标内容以填充表头
        $model = json_decode($batch_info['model'],true);
        $zhibiao_info = array();
        //模拟多条数据
        //$model['info'][0]['info'][1] = array('id'=>'499','option_msg'=>'A22指标','option_fenzhi'=>'','minscore'=>'16','maxscore'=>'18','sort'=>'2');
        foreach ($model['info'] as $vi ){
            $info_text = '';
            foreach ($vi['info'] as $vii){
                    $info_text .= $vii['option_msg']."(".$vii['minscore']."-".$vii['maxscore'].")分\n";
            }
            array_push($zhibiao_info,$info_text);
        }
        //将数据传入excel表中 $inputFileType = 'Excel5';'Excel2007';'Excel2003XML';'OOCalc';'SYLK';'Gnumeric';'CSV';
        foreach($group_data as $ki=> $gu){
            //判断读取哪种类型
            $inputFileType = PHPExcel_IOFactory::identify($this->excelDemoPath. "lxpsGroup_template.xls");
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($this->excelDemoPath. "lxpsGroup_template.xls");
            $objActSheet = $objPHPExcel->getActiveSheet();
            $lie = $this->A;//列
            $start = 5;//列开始计算
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '珠海市哲学社会科学规划年度立项课题评审小组表（第' . ($ki + 1) . '组）');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', '序号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B4', '登记号');
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C4', '项目名称');
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D4', '职称');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E4', '成果形式');
            $enArr = array('F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            //评分标准
            foreach ($zhibiao_info as $ii => $vp){
                $objPHPExcel->getActiveSheet()->getColumnDimension($enArr[$ii].'')->setWidth(30);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($enArr[$ii].'4', $vp);
            }
            $index = count($zhibiao_info);//取得评分标准后一列的位置
            $objPHPExcel->getActiveSheet()->getColumnDimension($enArr[$index].'')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension($enArr[$index+1].'')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($enArr[$index].'4', '专家评分(总分)');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($enArr[($index+1)].'4', '评审意见');
            //填充背景颜色
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[$index].'4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[$index].'4')->getFill()->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[($index+1)].'4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[($index+1)].'4')->getFill()->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);

            foreach ($data as $i => $v) {
                //填充数据
                $objActSheet->setCellValue('A' . ($i + $start), $i + 1); //序号
                $objActSheet->setCellValue('B' . ($i + $start), $v['secrinum']); //登记号
                $objActSheet->setCellValue('C' . ($i + $start), $v['name']); //名称
                $objActSheet->setCellValue('D' . ($i + $start), $v['position']); //职称
                $objActSheet->setCellValue('E' . ($i + $start), $v['achievement_type']); //成果形式
                foreach ($zhibiao_info as $ii => $vp){
                    $objActSheet->setCellValue($enArr[$ii].''. ($i + $start), $v['zhibiaoScoreArr'][$ii]); //成果形式
                }
                $objActSheet->setCellValue($enArr[$index].''. ($i + $start), $v['user_zongfen']); //总分
                $objActSheet->setCellValue($enArr[$index+1].''. ($i + $start), $v['review_opinion']); //评审意见
             }
            $filename = '立项课题评审小组表第' . ($ki + 1) . '组';
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
            //临时目录存放方式：项目根目录/storage/批次名称/文件
            $objWriter->save(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname) . '/' . iconv('utf-8', 'gbk', $filename) . '.xls');
        }

        //文件夹打包下载
        $this->download(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname));
    }

    //立项评审小组评审结果表
    public function projectStartGroupReviewResultExcel($pici_id)
    {
            $batch_info = m('m_batch')->getone('id=' . $pici_id);//网评基础信息
            //建立临时文件夹
            @mkdir(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $batch_info['pici_name'] . '小组立项评审结果表'));
            $dirname = $batch_info['pici_name'] . '小组立项评审结果表';
            $batch_data = m('m_batch')->getone("id = $pici_id");
            $projectIds = implode(',',unserialize($batch_data['project_ids']));
            //获取登记号,项目名称,职称,成果形式
            $bill_data = m('flow_bill')->getall("id in ($projectIds)", 'sericnum,id');
            $data = array();
            foreach ($bill_data as $v) {
                $rs = m('expert_review')->getone("secrinum = '" . $v['sericnum'] . "'", 'secrinum,name,position,achievement_type,leader ');
                //获取专家评分,平均分,评审意见,
                $rv = m('m_pxmdf')->getall("pici_id = $pici_id and xid =" . $v['id']);
                $personScoreArr = array();
                $total_score = 0;
                $review_opinion = '';
                foreach ($rv as $va) {
                    $json_model = json_decode($va['model'], true);
                    $total_score += $json_model['zongfen'];
                    $review_opinion .= $va['review_opinion'] . ',';
                    array_push($personScoreArr, $json_model['zongfen']);
                }
                $average_score = $total_score / count($rv);
                $rs['personScoreArr'] = $personScoreArr;
                $rs['average_score'] = $average_score;
                $rs['review_opinion'] = $review_opinion;
                array_push($data, $rs);
            }
            //将数据传入excel表中
            foreach ($data as $i => $v) {
                //判断读取哪种类型
                $inputFileType = PHPExcel_IOFactory::identify($this->excelDemoPath. "lxpsGroupResult_template.xls");
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($this->excelDemoPath . "lxpsGroupResult_template.xls");
                $objActSheet = $objPHPExcel->getActiveSheet();
                $lie = $this->A;//列
                $start = 3;//列开始计算
                $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(30);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B1', '珠海市哲学社会科学规划年度立项课题评审小组表（第' . ($i + 1) . '组）');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '序号');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '登记号');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '项目名称');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', '职称');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', '成果形式');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', '专家A评分');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', '专家B评分');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', '专家C评分');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', '平均分');
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', '评审意见');
                //珠海市哲学社会科学规划    年度立项课题评审小组汇总表（第一组）
                //填充数据
                $objActSheet->setCellValue('A' . ($i + $start), $i + 1); //序号
                $objActSheet->setCellValue('B' . ($i + $start), $v['secrinum']); //登记号
                $objActSheet->setCellValue('C' . ($i + $start), $v['name']); //名称
                $objActSheet->setCellValue('D' . ($i + $start), $v['position']); //职称
                $objActSheet->setCellValue('E' . ($i + $start), $v['achievement_type']); //成果形式
                $objActSheet->setCellValue('F' . ($i + $start), $v['personScoreArr'][0]); //专家A评分
                $objActSheet->setCellValue('G' . ($i + $start), $v['personScoreArr'][1]); //专家B评分
                //$objActSheet->setCellValue('H' . ($i + $start), $v['personScoreArr'][2]); //专家C评分
                $objActSheet->setCellValue('I' . ($i + $start), $v['average_score']); //平均分
                $objActSheet->setCellValue('J' . ($i + $start), $v['review_opinion']); //评审意见

                $filename = '立项课题评审小组表第' . ($i + 1) . '组';
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
                //临时目录存放方式：项目根目录/storage/批次名称/文件
                $objWriter->save(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname) . '/' . iconv('utf-8', 'gbk', $filename) . '.xls');
            }

        //文件夹打包下载
        $this->download(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname));
    }

    //结项评审小组评审表
    public function projectEndGroupReviewExcel($pici_id)
    {
        $batch_info = m('m_batch')->getone('id=' . $pici_id);//网评基础信息
        //建立临时文件夹
        @mkdir(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $batch_info['pici_name'].'小组结项评审表'));
        $dirname = $batch_info['pici_name'].'小组结项评审表';
        $batch_data = m('m_batch')->getone("id = $pici_id");
        $projectIds = implode(',',unserialize($batch_data['project_ids']));
        //获取立项编号,项目名称,成果形式,负责人,单位
        $bill_data = m('flow_bill')->getall("id in ($projectIds)", 'sericnum,id');
        $group_data = array();
        $data = array();
        $level = array('1'=>'优秀','2'=>'良好','3'=>'合格','4'=>'不合格');
        foreach ($bill_data as $v) {
            $rs = m('expert_review')->getone("secrinum = '" . $v['sericnum'] . "'", 'projectstart_num,name,position,achievement_type,leader,company ');
            //获取专家评分,评审意见,
            $rv = m('m_pxmdf')->getall("pici_id = $pici_id and xid =" . $v['id']);
            foreach ($rv as $va) {
                $json_model = json_decode($va['model'], true);
                $zhibiaoScoreArr = array();
                foreach ($json_model['info'] as $v){
                    array_push($zhibiaoScoreArr,$v['user_dafen']);
                }
                $rs['review_opinion_end'] = $va['review_opinion_end'];
                $rs['level_suggest'] =$level[$va['level_suggest']];
                $rs['zhibiaoScoreArr'] = $zhibiaoScoreArr;
                array_push($data, $rs);
            }
            array_push($group_data,$data);
        }
        //获取指标内容以填充表头
        $model = json_decode($batch_info['model'],true);
        $zhibiao_info = array();
        foreach ($model['info'] as $vi ){
            $info_text = '';
            foreach ($vi['info'] as $vii){
                $info_text .= $vii['option_msg']."(".$vii['minscore']."-".$vii['maxscore'].")分\n";
            }
            array_push($zhibiao_info,$info_text);
        }
        //将数据传入excel表中 $inputFileType = 'Excel5';'Excel2007';'Excel2003XML';'OOCalc';'SYLK';'Gnumeric';'CSV';
        foreach($group_data as $ki=> $gu){
            //判断读取哪种类型
            $inputFileType = PHPExcel_IOFactory::identify($this->excelDemoPath. "jxpsGroup_template.xls");
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($this->excelDemoPath. "jxpsGroup_template.xls");
            $objActSheet = $objPHPExcel->getActiveSheet();
            $lie = $this->A;//列
            $start = 3;//列开始计算
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '珠海市哲学社会科学规划年度结项课题评审小组表（第' . ($ki + 1) . '组）');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '序号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '立项编号');
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '项目名称');
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', '成果形式');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', '负责人');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', '单位');
            $enArr = array('G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
            //评分标准
            foreach ($zhibiao_info as $ii => $vp){
                $objPHPExcel->getActiveSheet()->getColumnDimension($enArr[$ii].'')->setWidth(30);
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($enArr[$ii].'2', $vp);
            }
            $index = count($zhibiao_info);//取得评分标准后一列的位置
            $objPHPExcel->getActiveSheet()->getColumnDimension($enArr[$index].'')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension($enArr[$index+1].'')->setWidth(30);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($enArr[$index].'2', '等级建议');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($enArr[($index+1)].'2', '鉴定意见');
            //填充背景颜色
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[$index].'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[$index].'2')->getFill()->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[($index+1)].'2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
            $objPHPExcel->getActiveSheet()->getStyle( $enArr[($index+1)].'2')->getFill()->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_YELLOW);

            foreach ($data as $i => $v) {
                //填充数据
                $objActSheet->setCellValue('A' . ($i + $start), $i + 1); //序号
                $objActSheet->setCellValue('B' . ($i + $start), $v['projectstart_num']); //立项编号
                $objActSheet->setCellValue('C' . ($i + $start), $v['name']); //项目名称
                $objActSheet->setCellValue('D' . ($i + $start), $v['achievement_type']); //成果形式
                $objActSheet->setCellValue('E' . ($i + $start), $v['leader']); //负责人
                $objActSheet->setCellValue('F' . ($i + $start), $v['company']); //单位
                foreach ($zhibiao_info as $ii => $vp){
                    $objActSheet->setCellValue($enArr[$ii].''. ($i + $start), $v['zhibiaoScoreArr'][$ii]); //成果形式
                }
                $objActSheet->setCellValue($enArr[$index].''. ($i + $start), $v['level_suggest']); //等级建议
                $objActSheet->setCellValue($enArr[$index+1].''. ($i + $start), $v['review_opinion_end']); //鉴定意见
            }
            $filename = '结项课题评审小组表第' . ($ki + 1) . '组';
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
            //临时目录存放方式：项目根目录/storage/批次名称/文件
            $objWriter->save(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname) . '/' . iconv('utf-8', 'gbk', $filename) . '.xls');
        }

        //文件夹打包下载
        $this->download(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname));
    }

    //结项小组评审结果表
    public function projectEndGroupReviewResultExcel($pici_id)
    {
        $batch_info = m('m_batch')->getone('id=' . $pici_id);//网评基础信息
        //建立临时文件夹
        @mkdir(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $batch_info['pici_name'] . '小组结项评审结果表'));
        $dirname = $batch_info['pici_name'] . '小组结项评审结果表';
        $batch_data = m('m_batch')->getone("id = $pici_id");
        $projectIds = implode(',',unserialize($batch_data['project_ids']));
        //获取登记号,项目名称,职称,成果形式
        $bill_data = m('flow_bill')->getall("id in ($projectIds)", 'sericnum,id');
        $data = array();
        foreach ($bill_data as $v) {
            $rs = m('expert_review')->getone("secrinum = '" . $v['sericnum'] . "'", 'secrinum,name,position,achievement_type,leader,company,projectstart_num ');
            //获取专家评分,最终等级,结项是否通过
            $rv = m('m_pxmdf')->getall("pici_id = $pici_id and xid =" . $v['id']);
            $level_text = array('1' => '优秀', '2' => '良好', '3' => '合格', '4' => '不合格');
            $personScoreArr = array();
            $level_suggest = '';
            $error_leverCount = 0;
            $jiexiangStatus = '';
            foreach ($rv as $va) {
                $json_model = json_decode($va['model'], true);
                $level_suggest .= $level_text[$va['level_suggest']] . ',';
                array_push($personScoreArr, $json_model['zongfen']);
                if ($va['level_suggest'] == 4) {
                    $error_leverCount++;
                }
                if ($error_leverCount >= 2) {
                    $jiexiangStatus = '不通过';
                } else {
                    $jiexiangStatus = '通过';
                }
            }
            $rs['personScoreArr'] = $personScoreArr;
            $rs['level_suggest'] = $level_suggest;
            $rs['jiexiangStatus'] = $jiexiangStatus;
            array_push($data, $rs);
        }
        //将数据传入excel表中
        foreach ($data as $i => $v) {
            //判断读取哪种类型
            $inputFileType = PHPExcel_IOFactory::identify($this->excelDemoPath. "jxpsGroupResult_template.xls");
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($this->excelDemoPath . "jxpsGroupResult_template.xls");
            $objActSheet = $objPHPExcel->getActiveSheet();
            $start = 3;//列开始计算
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '珠海市哲学社会科学规划年度结项课题评审小组表（第' . ($i + 1) . '组）');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '序号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '立项编号');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '项目名称');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D2', '负责人');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E2', '职称');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F2', '单位');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', '成果形式');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H2', '专家A评分');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I2', '专家B评分');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J2', '专家C评分');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K2', '最终等级');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L2', '结项是否通过');
            //填充数据
            $objActSheet->setCellValue('A' . ($i + $start), $i + 1); //序号
            $objActSheet->setCellValue('B' . ($i + $start), $v['projectstart_num']); //立项编号
            $objActSheet->setCellValue('C' . ($i + $start), $v['name']); //项目名称
            $objActSheet->setCellValue('D' . ($i + $start), $v['leader']); //负责人
            $objActSheet->setCellValue('E' . ($i + $start), $v['position']); //职称
            $objActSheet->setCellValue('F' . ($i + $start), $v['company']); //单位
            $objActSheet->setCellValue('G' . ($i + $start), $v['achievement_type']); //成果形式
            $objActSheet->setCellValue('H' . ($i + $start), $v['personScoreArr'][0]); //专家A评分
            //$objActSheet->setCellValue('I' . ($i + $start), $v['personScoreArr'][1]); //专家B评分
            //$objActSheet->setCellValue('J' . ($i + $start), $v['personScoreArr'][2]); //专家C评分
            $objActSheet->setCellValue('K' . ($i + $start), $v['level_suggest']); //最终等级
            $objActSheet->setCellValue('L' . ($i + $start), $v['jiexiangStatus']); //结项是否通过
            $filename = '结项课题评审小组表第' . ($i + 1) . '组';
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
            //临时目录存放方式：项目根目录/storage/批次名称/文件
            $objWriter->save(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname) . '/' . iconv('utf-8', 'gbk', $filename) . '.xls');
        }
        //文件夹打包下载
        $this->download(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname));
    }

    //导出专家信息
    public function exportExpertInfoExcel($pingshenType){
        $batch_info = m('m_batch')->getall("mtype='$pingshenType'",'id,expert_ids,pici_name');//指定类型的网评批次
        //建立临时文件夹
        @mkdir(ROOT_PATH . '/' . iconv('utf-8', 'gbk',  '评审专家信息表'));
        $dirname = '评审专家信息表';
        //判断读取哪种类型
        $inputFileType = PHPExcel_IOFactory::identify($this->excelDemoPath. "pszj_template.xlsx");
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($this->excelDemoPath . "pszj_template.xlsx");
        $objActSheet = $objPHPExcel->getActiveSheet();
        $start = 3;//列开始计算
        $data = array();
        foreach ($batch_info as $value){
            $expertIdStr = implode(',',unserialize($value['expert_ids']));
            $expertInfoArr = m('expert_info')->getall("mid in ($expertIdStr) ","name,position2,graduate_project,research_direction,company,mobile");
            foreach ($expertInfoArr as $i=> $v){
                $v['pici_name'] = $value['pici_name'];
                array_push($data,$v);
            }
        }
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
        //填充数据
        foreach ($data as $i => $vi){
             $objActSheet->setCellValue('A' . ($i + $start), $vi['pici_name']); //批次
               $objActSheet->setCellValue('B' . ($i + $start), $vi['name']); //名字
               $objActSheet->setCellValue('C' . ($i + $start), $vi['position2']); //职称职务
               $objActSheet->setCellValue('D' . ($i + $start), $vi['graduate_project']); //学科
               $objActSheet->setCellValue('E' . ($i + $start), $vi['research_direction']); //研究方向
               $objActSheet->setCellValue('F' . ($i + $start), $vi['company']); //关联单位
               $objActSheet->setCellValue('G' . ($i + $start), $vi['mobile']); //联系电话
               $filename = '评审专家信息表';
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
               //临时目录存放方式：项目根目录/storage/批次名称/文件
               $objWriter->save(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname) . '/' . iconv('utf-8', 'gbk', $filename) . '.xlsx');
        }
        //文件夹打包下载
        $this->download(ROOT_PATH . '/' . iconv('utf-8', 'gbk', $dirname));
    }
    /**
     * 将文件夹打包成zip
     */
    private function addFileToZip($path, $zip)
    {
        $handler = opendir($path); //打开当前文件夹由$path指定。

        while (($filename = readdir($handler)) !== false) {

            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..'，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    addFileToZip($path . "/" . $filename, $zip);
                } else { //将文件加入zip对象
                    $zip->addFile($path . "/" . $filename);
                }
            }
        }
        @closedir($path);
    }

    /**
     * 下载
     */
    private function download($path)
    {
        $filename = $this->retrieve($path) . '.zip';
        $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
        if ($zip->open($filename, ZIPARCHIVE::CREATE) !== TRUE) {
            exit('无法打开文件，或者文件创建失败');
        } else {
            $this->addFileToZip($this->retrieve($path) . '/', $zip); //调用方法，对要打包的根目录进行操作，并将ZipArchive的对象传递给方法
        }
        $zip->close();//关闭
        //清空（擦除）缓冲区并关闭输出缓冲
        ob_end_clean();
        //下载建好的.zip压缩包
        header("Content-Type: application/force-download");//告诉浏览器强制下载
        header("Content-Transfer-Encoding: binary");//声明一个下载的文件
        header('Content-Type: application/zip');//设置文件内容类型为zip
        header('Content-Disposition: attachment; filename=' . $filename);//声明文件名
        header('Content-Length: ' . filesize($filename));//声明文件大小
        error_reporting(0);
        //将欲下载的zip文件写入到输出缓冲
        readfile($filename);
        //将缓冲区的内容立即发送到浏览器，输出
        flush();
        $this->delDirAndFile($path);
        unlink($filename);//删除压缩包
    }


    //截取路径中的文件名
    function retrieve($path)
    {
        $dirname = dirname($path);
        $str = str_replace($dirname . '/', '', $path);
        return $str;
    }

    //循环删除目录和文件函数
    private function delDirAndFile($dirName)
    {
        if ($handle = opendir("$dirName")) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir("$dirName/$item")) {
                        delDirAndFile("$dirName/$item");
                    } else {
                        unlink("$dirName/$item");
                    }
                }
            }
            closedir($handle);
            rmdir($dirName);
        }
    }

    //不含网评
    public function pinFeiHuZxM()
    {
        $lie = $this->A;//列
        $count_sql = "select b.deptname,count(*) AS 'number' FROM pl_project_sx_apply a LEFT JOIN pl_admin b ON a.uid = b.id where a.project_ku='预备库' AND a.is_wp=0 GROUP BY b.deptname ORDER BY b.deptname";
        $count_dept = $this->db->getall($count_sql);

        $data_sql = "select a.id,a.project_name,a.zhuanyemingcheng,a.project_head,a.head_phone,a.project_select,a.project_select,a.nijianshechangsuo,a.yongfangmianji,a.xuexiaozichou,a.project_yushuan,a.zx_caizhen,a.qiyetouru,b.deptname FROM pl_project_sx_apply a LEFT JOIN pl_admin b ON a.uid = b.id where a.project_ku='预备库' AND a.is_wp=0";
        $data_data = $this->db->getall($data_sql);

        $objReader = PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($this->excelDemoPath . "wc_template.xls");

        $objActSheet = $objPHPExcel->getActiveSheet();

        $start = 4;//列开始计算数
        $date_reg = date("Y");
        $str = $date_reg . '年度实训基地建设立项项目申报基本信息汇总表';

        $objActSheet->setCellValue('A1', $str); //序号
//			 $sum_data_data=count($data_data);
        foreach ($data_data as $k => $value) {

            $objActSheet->setCellValue('B' . ($k + $start), $k + 1); //序号
            $objActSheet->setCellValue('C' . ($k + $start), $value['project_name']); //项目名称
            $objActSheet->setCellValue('D' . ($k + $start), $value['zhuanyemingcheng']); //所属专业
            $objActSheet->setCellValue('E' . ($k + $start), $value['project_head']); //项目负责人
            $objActSheet->setCellValue('F' . ($k + $start), $value['head_phone']); //项目负责人联系电话
            $objActSheet->setCellValue('G' . ($k + $start), $value['project_select']); //基地类型
            $objActSheet->setCellValue('H' . ($k + $start), $value['yongfangmianji']); //建设面积（平米）
            $objActSheet->setCellValue('I' . ($k + $start), $value['nijianshechangsuo']);//拟建场所
            $objActSheet->setCellValue('J' . ($k + $start), $value['project_yushuan'] / 10000); //总额
            $objActSheet->setCellValue('K' . ($k + $start), $value['zx_caizhen'] / 10000); //财政专项资金
            $objActSheet->setCellValue('L' . ($k + $start), $value['xuexiaozichou'] / 10000); //学校自筹
            $objActSheet->setCellValue('M' . ($k + $start), $value['qiyetouru'] / 10000); //企业投入

            $objPHPExcel->getActiveSheet()->getStyle('B' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('C' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('D' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('E' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('F' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('H' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('H' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('I' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('I' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('J' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('J' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('K' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('K' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('L' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('L' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('M' . ($k + $start))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('M' . ($k + $start))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        $row = $start;
        foreach ($count_dept as $k => $value) {
            $objActSheet->setCellValue('A' . $row, $value['deptname']); //单位名称
            $objActSheet->mergeCells('A' . $row . ':A' . ($row + $value['number'] - 1));

            $objActSheet->getStyle('A' . $row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objActSheet->getStyle('A' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            $row = $row + $value['number'];
        }

        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
                    'style' => PHPExcel_Style_Border::BORDER_THIN,//细边框
                    //'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        $countDataNum = count($data_data) + $start - 1;

        $objPHPExcel->getActiveSheet()->getStyle('A2:M2' . $countDataNum)->applyFromArray($styleArray);
        $filename = time();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); //在内存中准备一个excel2003文
        $objWriter->save('php://output');
    }

}
