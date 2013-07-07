<?php

/**
 * ==================================================
 *
 *  test_csv.php
 * ==================================================
 *  処理内容          主処理（CSVファイル読み込み）
 *  作成日            16:26 2012/08/02
 *  備考
 * --------------------------------------------------
 */
/**
 * --------------------------------------------------
 * 更新履歴
 * (更新者)  (yyyy/mm/dd)   (項目)       (内容説明)
 * --------------------------------------------------
*/

	// include_path を追加
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR .dirname(__FILE__) . DIRECTORY_SEPARATOR . 'lib');

	// ライブラリ読み込み
	require_once('csv_lib.php');
	
	if(isset($argv[1]) && file_exists($argv[1]) ){		// コマンド引数の有無、ファイルの存在を確認
		$filename_csv = $argv[1];
	}else{
		$filename_csv = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'sample.csv';
	}
	
	// CSVファイルを読み込む
	$csvData = array();
	$hfile_csv = @fopen($filename_csv, "r");
	if(!$hfile_csv){
		echo "File read Failed!\n";
	}else{
		while($_line = CSVlib::long_fgets($hfile_csv)){           // csvファイルを1行読み込む
			$_data = CSVlib::csv_split($_line);               // csvを分割
			$csvData[] = $_data;
		}
		fclose($hfile_csv);
	}
	// 表示
	print_r($csvData);
