<?php
 /************************************************************
*Create: UUQ(Huang Ziquan)
*Date:   2006-11-13
*ͼ���������ˮӡ��
* upload(); 1.Ϊָ����С;2Ϊ���ɲ�����,��һ�ߵ��ڣ�75*75Сͼ. Ĭ��Ϊ�Զ���С 3Ϊ���ɹ̶���С
*	    $up=new img("File",$uploadpath,"640","480");
		$up->setBasename($newfile);
		$up->setIcopath($uploadpath."_ico/");
		if(!$up->upload(5))
		{
		  header("HTTP/1.0 500 Internal Server Error");
 		}else{
		  echo "success!";
		}
************************************************************/
class img {
    var $upfile;   //�ϴ��ļ�����
	var $icopic;   //���ų�СͼƬ������;
	var $imgpic;   //��ͼ����
	var $uppath;   //�ϴ�ͼƬ��ŵ�·��
	var $basename; //�������ֲ�������չ��
	var $extfile;  //��չ��
    var $isup;     //�Ƿ��ϴ��ɹ�
	var $upsize;
	var $icowidth;
	var $icoheight; //
	var $imgwidth;
	var $imgheight; //
	var $shuiyin;  //ˮӡ
	var $im;      //ԭͼim
	var $newim;   //��ʱim
	var $type;    //mime���� 
	var $attr;    //ֱ����ʾ�ߺͿ�
	function __construct()
	{
	  $this->isup=false;
	  $this->shuiyin=false;
	  $this->icopath=''; //Сͼ����·��
	}
	function setImg($upfile,$upimages,$width,$height,$nzsize=0,$upsize=300000) {

	   
       $this->upfile=$upfile;

	   $this->icowidth=$width;
	   $this->icoheight=$height;
	   $this->nzsize=($nzsize==0)?$width:$nzsize;
	   $this->upsize=$upsize;
	   
	   $this->uppath=$upimages;  //ͼƬ����·��	   
	   $this->init();
	   Return $this;
	}
	/*
	*�ļ������ˣ�������תΪƴ��ɾ���Ƿ��ַ�
	*/
  static public function safeName($filename) {         
		$filename=C("zh2pinyin")->T($filename,true);
		$filename=preg_replace("/[^a-zA-Z0-9._=-]+/","",$filename);
	  Return $filename;
	}
	/*
	*������������ͼ��С
	*/
	function setIco($width,$height) {
	   $this->icowidth=$width;
	   $this->icoheight=$height;
	   Return $this;
	}
	/*
	*��������ˮӡ
	*/
	function setWater($mask=false) {
		$this->shuiyin=$mask;
		Return $this;
	}
	function setIcopath($path)
	{
	  if($path!="")
	  {
	    $this->icopath=$path;
	  }
	  Return $this;
	}
	/*
	*���ɹ̶����ߴ�С��Сͼ
	*/
	function setImgfang($size=75) {
		$this->fangsize=$size;
		Return $this;
	}
	function setIconame($name)
	{
	  if($name!="")
	  {
	    $this->icopic=$name;
	  }
	  Return $this;
	}
	function setBasename($name)
	{
	  if($name!="")
	  {
	    $this->basename=$name;
	  }
	  Return $this;
	}
	function init() {
	    $this->basename=date("Ymdhis").rand(10,99);
		$this->icopic=$this->basename."_ico";
			 //�趨����
       switch($_FILES[$this->upfile]['type'])
	   {
	     case 'image/gif':
               $this->extfile=".gif";
		       $this->isup=true;
		       break;
	     case 'image/png':
               $this->extfile=".png";
		       $this->isup=true;
		       break;
	     case 'image/pjpeg':
         case 'image/jpeg':
			  $this->extfile=".jpg";
              $this->isup=true;
		   break;
		 default:
			 $upfile=pathinfo($_FILES[$this->upfile]["name"]);
		     $ext=array("jpg","gif","png");	
	         $extup=strtolower($upfile['extension']);
	         if(in_array($extup,$ext))
		     {
			  $this->extfile=".".$extup;
              $this->isup=true;
			 }else{
			  $this->isup=false;
		      $this->message="images type error!";
			 }
	   }
	   if($_FILES[$this->upfile]['size']>$this->upsize)
	   {
	     $this->isup=false;
		 $this->message="up images too size !";
	   }
	   if(!$this->isup)
	   {
	      Return false;
	   }	 
	}
	/*
	*
	*�ϴ��м�����ʽ
	* 1 �ǹ̶�һ��
	* 2 ȫ���̶�Сͼ
	* 3 ���ɹ̶���С��ͼ�ĳ���;
	* 4 ���ݱ�������ͼƬ��С;
	* ����5 �ǲ���������ͼ
	* ȱʡ���Զ���С��������Сͼ��С
	* ���ָ�����ļ����������滻��ԭ�����ļ�
	*/
	function upload($up=1,$updatename="") {
	  if(!$this->isup)
	   {
	      Return false;
	   }
	   if($updatename!="")
	   {
	     $this->basename=$updatename;
		 $this->icopic=$updatename."_ico";
	   }
	  if(!move_uploaded_file($_FILES[$this->upfile]['tmp_name'],$this->uppath.$this->basename.$this->extfile))
	   {
	     $this->isup=false;
		 $this->message="uploaded error!";
		 Return $this->isup;
	   }
	   
	   $this->imgpic=$this->uppath.$this->basename.$this->extfile;
	   if($up>4) Return true;
	   if(file_exists($this->imgpic))
	   {
		   list($this->imgwidth, $this->imgheight,$this->type,$this->attr) = getimagesize($this->imgpic);
 		   switch ($this->type) {
				 case 1:
					$this->im = imagecreatefromgif($this->imgpic); 
				    break;
				 case 2:
					$this->im = imagecreatefromjpeg($this->imgpic); 
					break;
				 case 3:
					$this->im = imagecreatefrompng($this->imgpic); 
					break;
			  }
            switch($up)
		    {
			  case '1': //�̶�һ�ߴ�С
			  	$this->Resizenumm();
			    $this->Resizecut();
			  	break;
			  case '2':
				$this->Resizeauto();
			  	break;
			  case '3':
				$this->ResizeIco();
			  	break;
			  case '0':
			  case '4':
				$this->ResizeImage();
			    break;
			}
			/*
			*������С75*75ͼ
			*/
			if(!empty($this->fangsize))
		    {
				$this->Resizefang($this->fangsize);
		        $this->icopic.=".jpg";
			}
			ImageDestroy($this->im);
			$this->isup=true;	
			
	   }else
	   {
	     $this->isup=false;
		 $this->message="file_exists error!";
	   }
       Return $this->isup;
	}
	/*
	�Զ�����ͼƬ��С
	*/
function ResizeImage(){ 
        if(($this->icowidth && $this->imgwidth > $this->icowidth) || ($this->icoheight && $this->imgheight >$this->icoheight)){ 
        if($this->icowidth && $this->imgwidth > $this->icowidth){ 
            $widthratio = $this->icowidth/$this->imgwidth; 
            $RESIZEWIDTH=true; 
        } 
        if($this->icoheight && $this->imgheight > $this->icoheight){ 
            $heightratio = $this->icoheight/$this->imgheight; 
            $RESIZEHEIGHT=true; 
        } 
        if($RESIZEWIDTH && $RESIZEHEIGHT)
         { 
            if($widthratio < $heightratio)
              { 
                      $ratio = $widthratio; 
              }
          else{ 
               $ratio = $heightratio; 
              } 
        }
      elseif($RESIZEWIDTH)
     { 
         $ratio = $widthratio; 
     }elseif($RESIZEHEIGHT)
     { 
        $ratio = $heightratio; 
     } 
    $newwidth = $this->imgwidth * $ratio; 
    $newheight = $this->imgheight * $ratio; 
	 $newim = imagecreatetruecolor($newwidth, $newheight); //�������ɫͼƬ
	 imagecopyresampled($newim, $this->im, 0, 0, 0, 0, $newwidth, $newheight, $this->imgwidth,$this->imgheight); 
	 if($this->shuiyin) $this->shuiyin();
	 ImageJpeg ($newim,$this->uppath.$this->icopic.".jpg",100); 
	 ImageDestroy ($newim); 
	}else{ 
	 ImageJpeg ($this->im,$this->uppath.$this->icopic.".jpg",100); 
	} 
} 
/*
* ����640*640 75*75 
*
*�������ŵ��̶�һ�ߴ�
*/
 function Resizenumm()
 {
   if($this->imgwidth>=$this->imgheight)
   {
     $this->icoheight=ceil(($this->nzsize/$this->imgwidth)*$this->imgheight);
	 $this->icowidth=$this->nzsize;
   }else
   {
     $this->icowidth=ceil(($this->nzsize/$this->imgheight)*$this->imgwidth);
	 $this->icoheight=$this->nzsize;
   }
 }
 /*
 *
 * ���ɹ̶���С��ͼƬ
 *
 */
function Resizeauto() {
	$tempsize=75;
	$ridao=1;
	if($this->icowidht>=$this->icoheight)
	{
	   $tempsize=$this->icowidht;
	}else
	{
	   $tempsize=$this->icoheight;
	}
	if($this->imgwidth>=$this->imgheight)
	{
	  $ridao=$this->imgheight/$tempsize;
	}else
	{
	  $ridao=$this->imgwidth/$tempsize;
	}
	$x1=floor(($this->imgwidth-$this->icowidth*$ridao)/2);
	$y1=floor(($this->imgheight-$this->icoheight*$ridao)/2);

	$x2=floor($this->icowidth*$ridao);
	$y2=floor($this->icoheight*$ridao);

    $this->newim = imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($this->newim,$this->im, 0, 0, $x1, $y1,$this->icowidth,$this->icoheight,$x2,$y2); 
     if($this->shuiyin) $this->shuiyin();
     ImageJpeg($this->newim,$this->uppath.$this->icopic.".jpg",100); 
	 imagedestroy($this->newim);
}

	/**
	*
	*ˮӡ�������png��ʽ������͸��
	*
	*$this->shuiyinPngurl �����ں������������
	*C("waterimg")��ˮӡ��;
	* û������ˮӡ��������ڱ����������½�
	*/
	function shuiyin() { 	
			$simage1 =imagecreatefrompng(C("waterimg")->getWaterFile());
			$tempw=$this->icowidth-150;
			if($tempw<0)
			{
			  $tempw=0;
			}
			$temph=$this->icoheight-20;
			if($temph<0)
			{
			  $temph=0;
			}
			imagecopy($this->newim,$simage1,$tempw,$temph,0,0,150,20);
			imagedestroy($simage1);
	 }
/*
* ����640*640 75*75 
*
*/
 function Resizecut($unim=true){
	$this->newim= imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($this->newim, $this->im, 0, 0, 0, 0,$this->icowidth,$this->icoheight,$this->imgwidth,$this->imgheight); 
    if($this->shuiyin) $this->shuiyin();
    ImageJpeg($this->newim,$this->uppath.$this->icopic.".jpg",100); 
	if($unim) {
		imagedestroy($this->newim);
	}
  } 
/*
* ����75*75��������Сͼ 
*�Ѵ���75��ͼ�δ��м��г�75*75��С
*/
 function Resizefang($size=75){
  if($this->imgwidth>=$this->imgheight)
  {
    $this->nzsize=$this->imgheight;
  }else
  {
    $this->nzsize=$this->imgwidth;
  }
  if($this->imgwidth>=$this->imgheight)
  {
	 if($this->imgheight>=$this->nzsize)
	 {
	    $x1=floor(($this->imgwidth-$this->nzsize)/2);
		$y1=0;
		$x2=$this->nzsize;
		$y2=$this->nzsize;
	 }else{
	    if($this->imgwidth>=$this->nzsize)
		{
		   $x1=floor(($this->imgwidth-$this->nzsize)/2);
		   $y1=0;
		   $x2=$this->nzsize;
		   $y2=$this->imgheight;
		}else
		{
		   $x1=0;
		   $y1=0;
		   $x2=$this->imgwidth;
		   $y2=$this->imgheight; 
		}
	 }
  }else
  {
     if($this->imgwidth>=$this->nzsize)
	 {
	    $x1=0;
		$y1=floor(($this->imgheight-$this->nzsize)/2);
		$x2=$this->nzsize;
		$y2=$y1+$this->nzsize;
	 }else
	 {
	   if($this->imgheight>=$this->nzsize)
		{
		   $x1=0;
		   $y1=floor(($this->imgwidth-$this->nzsize)/2);
		   $x2=$this->imgwidth;
		   $y2=$y1+$this->nzsize;
		}else
		{
		   $x1=0;
		   $y1=0;
		   $x2=$this->imgwidth;
		   $y2=$this->imgheight; 
		}
	 }
  }
	$newim = imagecreatetruecolor($size,$size);
	imagecopyresampled($newim, $this->im, 0, 0, $x1,$y1,$size,$size,$x2,$y2);

	ImageJpeg($newim,$this->icopath.$this->basename."_".$size.".jpg",100); 
	ImageDestroy($newim); 
 }
/*
*���ɹ̶���С��ͼ�ĳ���;
*/ 
 function ResizeIco($x=121,$y=97){
  if($x!=121)
  {
    $this->icowidth=$x;
  }
  if($y!=97)
  {
    $this->icoheight=$y;
  }
  $a=$this->imgwidth/$this->icowidth;
  $b=$this->imgheight/$this->icoheight;
  if($a>=$b)
  {
	$kx=floor($this->icowidth*$b);
    $ky=$this->imgheight;
  }else{
    $kx=$this->imgwidth;
	$ky=floor($this->icoheight*$a);
  }
  $x1=floor(($this->imgwidth-$kx)/2);
  $y1=floor(($this->imgheight-$ky)/2);
  $x2=$kx;
  $y2=$ky;
	$newim = imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($newim,$this->im, 0, 0, $x1,$y1,$this->icowidth,$this->icoheight,$x2,$y2);
	ImageJpeg($newim,$this->icopath.$this->basename."_".$this->icowidth."_".$this->icoheight.".jpg",100); 
	ImageDestroy($newim); 
 } 
}
?>