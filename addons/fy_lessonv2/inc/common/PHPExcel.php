<?php
/**
 * PHPExcel方法
 * ============================================================================
 * 版权所有 2015-2018 微课堂团队，并保留所有权利。
 * 网站地址: https://wx.haoshu888.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！不允许对程序代码以任何形式任何目的的再发布，作者将保留
 * 追究法律责任的权力和最终解释权。
 */

require_once dirname(__FILE__).'/../../library/phpexcel/PHPExcel.php';

class FyLessonv2PHPExcel{
	
	/**
	 * $title	 标题
	 * $arr	     数据
	 * $filename 文件名
	 * $savetype 文件处理方法 0.直接下载 1.保存文件
	 */
	public function exportTable($title, $arr, $filename, $savetype='0'){
		$objPHPExcel = new PHPExcel();

		//设置当前的表格 
		$objPHPExcel->setActiveSheetIndex(0);
		
		// 设置表格第一行显示内容
		$title_arr = $this->setTableTitle($title);
		foreach($title_arr as $value){
			$objPHPExcel->getActiveSheet()->setCellValue($value['letter'], $value['name']);
		}

		$letter_arr = $this->getLetterArr();
		$objPHPExcel->getActiveSheet()->getStyle($letter_arr[0].'1:'.$letter_arr[count($title)-1].'1')->applyFromArray(
            array(
                'font' => array (
                    'bold' => true
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
                )
            )
        );

		/*以下就是对处理Excel里的数据，横着取数据*/
		$key = 1;
		foreach($arr as $key=>$val){
			$key++;
			$i = 0;
			foreach($val as $cv){
				$objPHPExcel->getActiveSheet()->setCellValue($letter_arr[$i].($key+1), $cv);
				$i++;
			}
		}

		//设置当前的表格 
		$objPHPExcel->setActiveSheetIndex(0);
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		
		//设置excel文件临时存储目录
		$dirpath = dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/data/excel/';
		if (!file_exists($dirpath)) {
			mkdir($dirpath, 0777);
		}

		if($savetype){
			$dirname = $dirpath.$filename.'-'.date('Ymd').'.xls';
			$objWriter->save($dirname);
		}else{
			ob_end_clean();
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="'.$filename.'-'.date('Ymd').'.xls"');
			header('Cache-Control: max-age=0');
			header('Content-Type: text/html; charset=utf-8');
			$objWriter->save('php://output');
		}
	}

	/**
	 * 设置表格的第一行标题
	 */
	 private function setTableTitle($title){
		 $letter_arr = $this->getLetterArr();
		 $count = count($title);

		 $title_arr = array();
		 foreach($letter_arr as $key=>$value){
			$title_arr[$key]['letter'] = $letter_arr[$key].'1';
			$title_arr[$key]['name']   = $title[$key];

			if($key < $count-1){
				continue;
			}else{
				break;
			}
		 }

		 return $title_arr;
	 }

	 /**
	  * 获取A~Z数组
 	  */
	 private function getLetterArr(){
		 $letter_arr = array('A','B','C','D','E','F','G','H','I','J','K',
							 'L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		 return $letter_arr;
	 }
}


