<?php
/**
	pdf文档相关类库
*/

class pdfChajian extends Chajian{
	
	
	public function initChajian()
	{
		
	}
		
	public function pdfRender($html, $title = '合同文本',$fileName)
    {

      //  require_once(dirname(__FILE__).'/tcpdf_autoconfig.php');
        require_once('D:/phpStudy/WWW/xiangmukuv0.2/include/tcpdf_min_6_2_13/tcpdf.php');
      //实例化 
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); 
 
// 设置文档信息 
$pdf->SetCreator('Helloweba'); 
$pdf->SetAuthor('yueguangguang'); 
$pdf->SetTitle('Welcome to helloweba.com!'); 
$pdf->SetSubject('TCPDF Tutorial'); 
$pdf->SetKeywords('TCPDF, PDF, PHP'); 
 
// 设置页眉和页脚信息 
//$pdf->SetHeaderData('logo.png', 30, 'Helloweba.com', '致力于WEB前端技术在中国的应用',  
//    array(0,64,255), array(0,64,128)); 
//$pdf->setFooterData(array(0,64,0), array(0,64,128)); 
 
// 设置页眉和页脚字体 
//$pdf->setHeaderFont(Array('stsongstdlight', '', '10')); 
//$pdf->setFooterFont(Array('helvetica', '', '8')); 
// 
// 设置默认等宽字体 
$pdf->SetDefaultMonospacedFont('courier'); 
 
// 设置间距 
$pdf->SetMargins(27, 16, 27); 
$pdf->SetHeaderMargin(5); 
$pdf->SetFooterMargin(10); 
 
// 设置分页 
$pdf->SetAutoPageBreak(TRUE, 10); 
 
// set image scale factor 
$pdf->setImageScale(1.25); 
// 
//// set default font subsetting mode 
$pdf->setFontSubsetting(true); 
 
//设置字体 
//$pdf->SetFont('stsongstdlight', '', 14); 
// 
$pdf->AddPage(); 
 
//$str1 = '欢迎来到Helloweba.com'; 
// 
//$pdf->Write(0,$str1,'', 0, 'L', true, 0, false, false, 0); 
 		//基础信息	

 		
 		
 	   $pdf->SetFont('STSongStdLight', '', 15);
    
	   	$html= '<table width="100%" border="1">
  <tr>
    <td height="30" colspan="5" align="right">编号：X2017CG058</td>
  </tr>
  <tr>
    <td height="40">&nbsp;</td>
    <td height="40">&nbsp;</td>
    <td height="40">&nbsp;</td>
    <td height="40">&nbsp;</td>
    <td height="40">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">  <p class="center"><strong><span style="font-size:30px;">广东科学技术职业学院</span><u> </u></strong></p>
      <p class="center"><br />
        <strong><span style="font-size:30px;">项目库项目申报书</span></strong></p>
      <p class="center"><span style="font-size:30px;"><br />
        <strong> 申报书</strong></span></p></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
</table>';
				
		$html.='<table width="553" border="1">
  <tr>
    <td width="130" height="200">&nbsp;</td>
    <td width="403" height="200">&nbsp;</td>
    <td width="20" height="200">&nbsp;</td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td width="403" height="60"><span style="font-size:19px;">项目名称:</span><span style="font-size:19px;"><u>测试测试测试测试测试测试测试测&nbsp;</u></span></td>
    <td width="20">&nbsp;</td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td width="403" height="60"><span style="font-size:19px;">项目申报单位:</span><span style="font-size:19px;"><u>系统管理员&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></span></td>
    <td width="20">&nbsp;</td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td width="403" height="60"><span style="font-size:19px;"> 业务主管部门:</span><span style="font-size:19px;"><u>业务主管部门&nbsp;&nbsp;&nbsp;&nbsp;</u></span></td>
    <td width="20">&nbsp;</td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td width="403" height="60"><span style="font-size:19px;"> 项目实施年度:</span><span style="font-size:19px;"><u>2017&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></span></td>
    <td width="20">&nbsp;</td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td width="403" height="60"><span style="font-size:19px;"> 项目申报时间:</span><span style="font-size:19px;"><u> 2017</u>年<u>05</u>月<u>02</u>日</span></td>
    <td width="20">&nbsp;</td>
  </tr>
  <tr>
    <td width="130">&nbsp;</td>
    <td width="403" height="60">&nbsp;</td>
    <td width="20">&nbsp;</td>
  </tr>
</table>';	
				
				
	
       $pdf->writeHTML($html,true, false, true, false, '');
//     $pdf->writeHTMLCell();
//输出PDF 

$pdf->addpage();

		$html2="<style>
.center{
	
	    text-align: center;
	 
	  
}
.bg-success {
    background-color: #dff0d8;
}
p {
    margin: 0 0 10px;
}
.a{
	position: absolute;
    top: 500px;
}
.title{
	 padding-top:100px;
	}
.m_left{margin-left: 8px;}
.ply{text-indent:24pt;margin:5px 5px}	
.ply_left_top{margin-left:35px}	
.table_all{
	border: 1px #000000 solid;
	
	}
.table_all_p_l{
	border: 1px #000000 solid;
	margin-left: 8px;
	}
.table_top{
	border-top: 1px #000000 solid;
	
	}

.table_left{
	border-left: 1px #000000 solid;
	}
.table_left_right{
	border-left: 1px #000000 solid;
	border-right: 1px #000000 solid;
	}
.table_right{
	border-right: 1px #000000 solid;
	}	
.table_right_bottom{
	border-right: 1px #000000 solid;
	border-bottom: 1px #000000 solid;
	}	

.table_no_top{
	border-left: 1px #000000 solid;
	border-right: 1px #000000 solid;
	border-bottom: 1px #000000 solid;
	}	
.table_no_bottom{
	border-left: 1px #000000 solid;
	border-right: 1px #000000 solid;
	border-top: 1px #000000 solid;
	}
.table_top_right{
	border-top: 1px #000000 solid;
	border-right: 1px #000000 solid;
	}
.table_top_left{
	border-top: 1px #000000 solid;

	border-left: 1px #000000 solid;
	}

.no_all{
	border: 1px #000000 solid;
	padding:0px;
}
.no_left{
	border-left: 1px #000000 solid;
	padding:0px;
}

.no_left_right{
	border-left: 1px #000000 solid;
	border-right: 1px #000000 solid;
	padding:0px;
}

.stitle{padding:5px;border-bottom:1px #dddddd solid;font-size:14px;}
.ydullist{display:inline-block;width:100%;}
.ydullist li{float:left;width:10%;text-align:center;padding:5px 0px;font-size:12px;display:block;line-height:25px;padding-top:10px}
.ydullist li:active{ background-color:#eeeeee}
.ydullist li img,.faces{height:30px;width:30px;border-radius:15px}
.ydullist li span{font-size:12px;color:#888888;}

.faces{margin-right:10px}
.ptitle{ text-align:center;font-size:20px;padding-top:15px;padding-bottom:10px}
.tabled2 td{padding:5px;border:1px #e5e5e5 solid;text-align:center}
td.tdys1{border:1px #e5e5e5 solid;text-align:center;padding:0px 5px}
.createtable{width:90%}
.menulls{position:absolute;left:1px;top:10px}
.menulls{position:absolute;}
.menullss{position:absolute;left:1px;top:32px; background-color:white; border:1px #cccccc solid;border-bottom:0px;display:none}
.menullss li{padding:5px 10px;border-bottom:1px #dddddd solid;cursor:pointer}
.menullss li:hover{ background-color:#f1f1f1}
.pcont{line-height:27px;}
/*.pcont p{text-indent:24pt;margin:10px 10px}*/
.status{position: absolute;right:20px;top:10px;display:}

.ke-zeroborder{border-spacing: 0;border-collapse: collapse;}
.ys0{border:1px #888888 solid}
.ys1{padding:5px 5px; border:1px #888888 solid;color:#5a5a5a;}
.ys2{padding:5px 5px; border:1px #888888 solid;color:#5a5a5a;}
.ys3{padding:5px 5px; border:1px #E5E5E5 solid;color:#5a5a5a;}
.datesss{background:url(mode/icons/date.png) no-repeat right;cursor:pointer;width:50%}
.inputs{width:95%}

</style>";

		$html2.='<table width="553" border="1">
  <tr>
    <td height="38" colspan="4" align="center" valign="middle"><span style="font-size:7px;" class="title"><br /></span><span style="font-size:19px;margin-top:10px;">一、项目申报单位情况</span></td>
  </tr>
  <tr>
    <td width="124" height="38"><span class="m_left">项目名称</span></td>
    <td height="38" width="429" colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td width="124" height="38"><span class="m_left">项目申报单位</span></td>
    <td height="38" colspan="3" width="429">&nbsp;</td>
  </tr>
  <tr>
    <td width="124" height="38"><span class="m_left">资金来源</span></td>
    <td width="119" height="38"><span class="m_left">学校</span></td>
    <td width="191" height="38" align="center">预算</td>
    <td width="119" height="38">0</td>
  </tr>
  <tr>
    <td width="124" height="38"><span class="m_left">项目负责人</span></td>
    <td width="119" height="38"><span class="m_left">梁锦宇</span></td>
    <td width="191" height="38" align="center">项目负责人联系电话</td>
    <td width="119" height="38"><span class="m_left">13160668791</span></td>
  </tr>
</table>';
		
		$html2.='<table width="100%" bordercolor="#000000" border="0" class="table_left_right ke-zeroborder" cellspacing="0">
	<tbody>
		<tr>
		  <td width="8%" align="center"><span style="font-size:18px;" class="m_left"><br />项<br />目<br />组<br />成<br />员<br /></span> </td>
			<td width="92%" align="center" valign="top" class="no_left"><table width="100.5%"  class="createrows" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;" ><tr><td height="28" style="padding:3px;border:1px #000000 solid;border-top:none;border-left:none"  align="center">名称</td><td height="28" style="padding:3px;border:1px #000000 solid;border-top:none"  align="center">单位</td><td height="28" style="padding:3px;border:1px #000000 solid;border-top:none"  align="center">职务/职称</td><td height="28" style="padding:3px;border:1px #000000 solid;border-top:none"  align="center">工作分工</td><td height="28" style="padding:3px;border:1px #000000 solid;border-top:none;border-right:none"  align="center">联系方式</td></tr><tr><td height="28" style="padding:3px;border:1px #000000 solid;border-left:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-right:none" align="center" ></td></tr><tr><td height="28" style="padding:3px;border:1px #000000 solid;border-left:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-right:none" align="center" ></td></tr><tr><td height="28" style="padding:3px;border:1px #000000 solid;border-left:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-right:none" align="center" ></td></tr><tr><td height="28" style="padding:3px;border:1px #000000 solid;border-left:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-right:none" align="center" ></td></tr><tr><td height="28" style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center" ></td><td height="28" style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center" ></td></tr></table></td>
		</tr>
	</tbody>
</table>
<table width="100%" bordercolor="#000000" border="0" class="ke-zeroborder">
	<tbody>
		<tr>
			<td height="32" colspan="6" align="center" class="table_all"><span style="font-size:19px;">二、项目基本情况 </span> </td>
		</tr>
		<tr>
			<td align="center" class="table_all"><span style="font-size:19px;"><br />项<br />目<br />申<br />请<br />理<br />由<br />及<br />主<br />要<br />内<br />容 </span> </td>
			<td height="480" colspan="5" align="left" valign="top" class="table_all">
				<p class="ply"><span style="font-size:14px;">一、项目申请理由（重点说明项目申报依据及必要性及紧迫性、规划时限等主要情况、主要经济技术指标、项目进度安排等）</span> </p>
				<p class="ply"></p>
			</td>
		</tr>
		<tr>
			<td width="8%" align="center" class="table_all"></td>
			<td height="915" colspan="5" align="left" valign="top" class="table_all">
				<p class="ply"><span style="font-size:14px;">二、项目具体建设目标、意义、范围、内容、可行性（环境、人员、技术等条件）、及相关技术材料。要特别说明现有条件（人员、技术、设备、环境、用房）和要学校支持的条件。</span> </p>
				<p class="ply"><span> 测试</span> </p>
			</td>
		</tr>
		<tr>
			<td width="8%" rowspan="2" align="center" class="table_all"></td>
			<td height="800" colspan="5" align="left" valign="top" class="table_no_bottom">
				<p class="ply"><span style="font-size:16px;">三、分项列出主要建设内容，项目投资概预算及资金构成、资金筹措方案。</span> </p>
				<p class="ply"><span> 测试</span><span><span><br /></span></span>
				</p>
			</td>
		</tr>
		<tr>
			<td height="100" colspan="5" align="left" valign="bottom" class="table_right_bottom">
				<p class="ply_left_top"><span></span> </p>
			</td>
		</tr>
		<tr>
			<td height="31" colspan="6" align="center" class="table_all"><span style="font-size:19px;">三、单位初审意见 </span> </td>
		</tr>
		<tr>
			<td height="250" colspan="6" align="left" class="table_left_right" id="dept_comm">
				<p class="ply"></p>
			</td>
		</tr>
		<tr>
			<td height="31" colspan="5" align="right" class="table_left"><span style="font-size:19px;">单位负责人: </span> </td>
			<td width="22%" height="31" align="right" class="table_right"></td>
		</tr>
		<tr>
			<td height="34" colspan="6" align="right" class="table_no_top"><span style="font-size:19px;">年 月 日 </span> </td>
		</tr>
		<tr>
			<td height="34" colspan="6" align="center" class="table_all"><span style="font-size:19px;">四、项目库管理办公室初审处理意见 </span> </td>
		</tr>
		<tr>
			<td height="0" colspan="6" align="left" valign="top" class="table_left_right">
				<p><span class="jianju" style="font-size:19px;">□内容缺项或格式有误，退回修改，意见如下：</span> </p>
			</td>
		</tr>
		<tr>
			<td height="195" colspan="6" align="left" valign="top" class="table_left_right" id="x_tuihui">
				<p class="ply"></p>
				<p class="ply"></p>
			</td>
		</tr>
		<tr>
			<td colspan="6" align="left" valign="top" class="table_left_right">
				<p><span class="jianju" style="font-size:19px;">□形式审查合格，送相关业务部门筛选。</span> </p>
			</td>
		</tr>
		<tr>
			<td height="195" colspan="6" align="left" valign="top" class="table_left_right" id="x_tongyi">
				<p class="ply"></p>
				<p class="ply"></p>
			</td>
		</tr>
		<tr>
			<td height="34" colspan="5" align="right" class="table_left">
				<p><span style="font-size:19px;">负责人：<span id="xmb_head"></span> </span>
				</p>
			</td>
			<td height="34" align="right" class="table_right"></td>
		</tr>
		<tr>
			<td height="34" colspan="6" align="right" class="table_no_top"><span style="font-size:19px;">年 月 日 </span> </td>
		</tr>
		<tr>
			<td height="34" colspan="6" align="center" class="table_all"><span style="font-size:19px;">五、校级专家组论证意见</span> </td>
		</tr>
		<tr>
			<td height="470" colspan="6" align="left" valign="top" class="table_left_right">
				<p class="ply"></p>
			</td>
		</tr>
	</tbody>
</table>
<table width="100%" bordercolor="#000000" border="0" class="table_all ke-zeroborder" cellspacing="0">
	<tbody>
		<tr>
			<td width="8%" align="center" class="no"><span style="font-size:18px;"><br />专<br />家<br />组<br />成<br />员</span> </td>
		  <td width="92%" align="center" valign="top" class="no_left"><table width="100%" class="createrows" border="0" cellspacing="0" cellpadding="0" align="center" style="border-collapse:collapse;">
					<tbody>
				    <tr>
				      <td width="11%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none;border-left:none">序号</td>
				      <td width="15%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">名称</td>
				      <td width="21%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">单位</td>
				      <td width="17%"  align="center" style="padding:3px;border:1px #000000 solid;border-top:none">职务/职称</td>
				      <td width="18%" align="center" style="padding:3px;border:1px #000000 solid;border-top:none;border-right:none">备注</td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">1</td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">2</td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none" align="center">3</td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">4</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">5</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">6</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">7</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				      <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">8</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				    <tr>
				      <td style="padding:3px;border:1px #000000 solid;border-left:none;border-bottom:none" align="center">9</td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-bottom:none" align="center"></td>
				      <td style="padding:3px;border:1px #000000 solid;border-right:none;border-bottom:none" align="center"></td>
				    </tr>
				  </tbody>
				</table></td>
		</tr>
		<tr>
			<td width="8%" height="105" align="center" class="table_top"><span><span style="font-size:18px;">相<br />关<br />文<br />档<br /></span></span>
			</td>
			<td width="92%" align="left" valign="top" class="table_top_left">
			  <p class="ply_left_top"></p>
			</td>
	  </tr>
		
  </tbody>
</table>';
	    $pdf->writeHTML($html2,true, false, true, false, '');

            $pdf->Output($fileName.".pdf", "I");

    }

			
	
	
}
