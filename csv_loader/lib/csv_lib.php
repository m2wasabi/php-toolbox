<?php

/**
 * csv_lib.php
 * CSVファイルを分割・読み出しする
 * 
 * @version 1.0
 * @author m2wasabi
 * @license    MIT License http://www.opensource.org/licenses/mit-license.php
 * @copyright  2010 - 2013 m2wasabi
 */

class CSVlib
{
	/**
	 * 2つの単語で挟まれた語句を抜き出し
	 * 
	 * @param string $haystack 処理対象文字列
	 * @param string $_preword 前区切り文字
	 * @param string $_postword 後区切り文字
	 * @return string 抜き出された文字列
	 */
	public static function substrstr($haystack , $_preword = NULL , $_postword = NULL)
	{
			$_pos1 = strpos($haystack,$_preword) + strlen($_preword) ;
			$_subword = substr($haystack, $_pos1 );
			return substr($haystack, $_pos1 , ( strpos($_subword , $_postword)  ) ) ;
	}

	/**
	 * ファイルから1行抜き出す(エスケープ文字対応)
	 * 
	 * @param resource $handle ファイルハンドラ
	 * @param integer $length 一度に読み込む長さ
	 * @param string $e エスケープ文字
	 * @return string エスケープを考慮した1行分の文字列
	 */
	public static function long_fgets(&$handle, $length = null, $e = '"')
	{
		$_line = "";
		$eof = false;
		while ($eof != true and !feof($handle))
		{
			$_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
			$itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
			if ($itemcnt % 2 == 0) $eof = true;
		}
		return empty($_line) ? false : $_line;
	}

	/**
	 * 1行をCSVデータとして処理
	 * 
	 * @param string $_line 1行分のテキスト
	 * @param string $d デリミタ文字
	 * @param string $e エスケープ文字
	 * @return array 分割された文字配列
	 */
	public static function csv_split($_line = "", $d = ',', $e = '"')
	{
		$d = preg_quote($d);	
		$e = preg_quote($e);
		$_csv_line = preg_replace('/(?:\r\n|[\r\n])?$/', $d, trim($_line));     // 改行の除去・記法の統一(末尾に$d)
		
		// CSVデータの分割
		$_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
		preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
		$_csv_data = $_csv_matches[1];
		
		// 抽出したデータの掃除
		for($_csv_i=0;$_csv_i<count($_csv_data);$_csv_i++)
		{
			$_csv_data[$_csv_i]=preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);    // 頭・末尾をエスケープ文字で囲まれた場合、取り除く
			$_csv_data[$_csv_i]=str_replace($e.$e, $e, $_csv_data[$_csv_i]);    // エスケープされたエスケープ文字をもとに戻す
		}
		return empty($_line) ? false : $_csv_data;
	}

	/**
	 * ファイルから1行抜き出し、CSVファイルとして分割
	 * 
	 * @param resource $handle ファイルハンドラ
	 * @param integer $length 一度に読み込む長さ
	 * @param string $d デリミタ文字
	 * @param string $e エスケープ文字
	 * @return array 分割された文字配列
	 */
	public static function fgetcsv_reg(&$handle, $length = null, $d = ',', $e = '"')
	{
		$_line = self::long_fgets($handle, $length, $e = '"');
		return self::csv_split($_line, $d , $e );
	}
}
?>