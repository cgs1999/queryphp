<?php
class mylog extends Exception {
    // �ض��幹����ʹ message ��Ϊ���뱻ָ��������
    public function __construct($message, $code = 0) {
        // �Զ���Ĵ���
        // ȷ�����б���������ȷ��ֵ
        parent::__construct($message, $code);
		ob_start();
		print_r($GLOBALS); 
		$str="\n-----------------------------------".$this->getCode()."----------------------------------------\n";
		file_put_contents(P("frameworkpath")."log/".date("Y_m_d").".txt", $str.ob_get_clean().$this->__toString().$str, FILE_APPEND);
    }
  public function __toString() {
    return "\n---------------- '".$this->getMessage()."' File: ".$this->getFile()." Line:".$this->getLine()."\nStack trace:\n".$this->getTraceAsString();
  }
}

?>