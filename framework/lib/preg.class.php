<?php

class preg {
  /*
  *������ʽ��ȡ���tr��
  *
  */
  static function match_tr($content) {
  	Return preg_match_all('/<tr.*?>[\r\n]{0,2}(<td.*?>.*?<\/td>[\r\n]{0,2})*<td.*?>.*?hidden.*?<\/td>[\r\n]{0,2}(<td.*?>.*?<\/td>[\r\n]{0,2})*<\/tr>/i',$content,$matchs)?$matchs:null;
  }	
  /*
  *������ȡͼƬ
  *
  *
  */
  static function match_images($content)
        {
        //��ȡ������ͼƬ
        //ȡ�õ�һ��ƥ���ͼƬ·��
        $retimg="";
        $matches=null;
         //��׼��src="xxxxx"����src='xxxxx'д��
        preg_match("/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i", $content, $matches);
        if(isset($matches[2])){
                $retimg=$matches[2];
                unset($matches);
                return $retimg;
        }
        //�Ǳ�׼��src=xxxxx д��
        unset($matches);
        $matches=null;
        preg_match("/<\s*img\s+[^>]*?src\s*=\s*(.*?)[\s\"\'>][^>]*?\/?\s*>/i", $content, $matches);
        if(isset($matches[1])){
                $retimg=$matches[1];
        }
        unset($matches);
        return $retimg;
 }
}
?>