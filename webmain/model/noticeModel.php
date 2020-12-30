<?php
class noticeClassModel extends Model
{
    public $type_zh = array(
        1 => '课题申报立项通知书',
        2 => '课题申报结项通知书',
        3 => '普及月申报入选通知书',
        4 => '常态化申报入选通知书',
        5 => '研究基地立项通知书',
        6=>'课题成果编制要求',
        7=>'后期认定结项通知书'
    );

    public $type_project = array(
        1 => '课题申报',
        2 => '课题申报',
        3 => '普及月申报',
        4 => '常态化申报',
        5 => '研究基地',
        6=>'课题申报',
        7=>'后期认定课题申报'
    );

    public $type_num = array(
        'kt_lx' => 1,
        'kt_jx' => 2,
        'pjy_rx' => 3,
        'cth_rx' => 4,
        'yjjd_lx' => 5,
        'kt_bzyq'=>6,
        'hqrd_jx'=>7
    );

    public function initModel()
    {
        $this->settable('notice');
    }

    //单据数据
    public function format($rows)
    {
        //获取key value形式的admin列表
        $admin = m('admin')->getKeyValueList();

        $srows = array();
        foreach ($rows as $k => $v) {
            $rows[$k]['type'] = $this->type_zh[$v['type']];
            $rows[$k]['opt']  = $admin[$v['opt']];
            $rows[$k]['caoz'] = '';
            $rows[$k]['fabu_status'] = '';
            if ($rows[$k]['com_status'] == 0){
                $rows[$k]['fabu_status'] = '草稿';
                $rows[$k]['caoz'] .= '<a onclick="edit_draft('. $v['id'] . ')">编辑</a>';
                $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                $rows[$k]['caoz'] .= '<a>删除</a>';
            }else if($rows[$k]['com_status'] == 1){
                $rows[$k]['fabu_status'] = '已发布';
                $rows[$k]['caoz'] .= '<a onclick="read('. $v['id'] . ',' . $v['type'] . ')">查看</a>';
                $rows[$k]['caoz'] .= '<span style="padding:5px;">|</span>';
                $rows[$k]['caoz'] .= '<a>下载</a>';
            }
            $srows[] = $rows[$k];
        }
        return $srows;
    }
}
