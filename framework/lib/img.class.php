<?php
 /************************************************************
*Create: UUQ(Huang Ziquan)
*Date:   2006-11-13
*ͼ���������ˮӡ��
* upload(); 1.Ϊָ����С;2Ϊ���ɲ�����,��һ�ߵ��ڣ�75*75Сͼ. Ĭ��Ϊ�Զ���С 3Ϊ���ɹ̶���С
	$img=C("img");
	$img->setInfo(
		  array("files"=>"upload",
		        "uploadpath"=>$GLOBALS['config']['webprojectpath']."upimages/",
		        "icopath"=>$GLOBALS['config']['webprojectpath']."upimages/",		        
		        "icowidth"=>"128",
		        "icoheight"=>"98",
		        "fangpath"=>$GLOBALS['config']['webprojectpath']."upimages/_ico/",
		        "fangsize"=>"75",
		        "nzsize"=>"180",
		        "uploadsize"=>320000
	            )
	      )->setBasename($_FILES['upload']['name'],true)->init();
	if($img->upload(1))
	{
	  echo("�ϴ��ɹ�");
	}else{
	  echo("�ϴ�ʧ��");
	  echo $img->message;
	}
	'fill_size' �ᶨΪСͼ��С
	size_ico    �ṩָ����С
	auto_ico    �Զ����Ų�����ico
	fix_ico     �ᶨ��С
	fix_side    �̶�һ�ߵ�nzsize��С

************************************************************/
class img {
    var $upfile;   //�ϴ��ļ�����
	var $icopic;   //���ų�СͼƬ������;
	var $imgpic;   //��ͼ����
	var $uploadpath;   //�ϴ�ͼƬ��ŵ�·��
	var $basename; //�������ֲ�������չ��
	var $extfile;  //��չ��
    var $isup;     //�Ƿ��ϴ��ɹ�
	var $uploadsize;
	var $icowidth;
	var $icoheight; //
	var $imgwidth;
	var $imgheight; //
	var $shuiyin;  //ˮӡ
	var $im;      //ԭͼim
	var $newim;   //��ʱim
	var $type;    //mime���� 
	var $attr;    //ֱ����ʾ�ߺͿ�
	var $info=array();
	function __construct()
	{
	  $this->isup=false;
	  $this->shuiyin=false;
	  $this->icopath=''; //Сͼ����·��
	}
	function setFiles($upfile){
		$this->info['name']=$this->safeName($_FILES[$upfile]['name']);
		$this->info['tmp_name']=$_FILES[$upfile]['tmp_name'];
		$this->info['type']=$_FILES[$upfile]['type'];
		$this->info['size']=$_FILES[$upfile]['size'];
		$this->info['error']=$_FILES[$upfile]['error'];
		Return $this;
	}
	/*
	*����ͼƬ�ϴ���Ϣ
	*icowidth Сͼ��Ϣ
	*icoheight 

	*fangpath ����ͼ��Ϣ
	*fangsize  

	*uploadpath �ϴ�Ŀ¼
	*files       �ϴ�input��
	*/
	function setInfo($info=array()) {
	  if(isset($info['uploadpath']))
	  $this->uploadpath=$info['uploadpath'];
	  if(isset($info['icowidth']))
	  $this->icowidth=$info['icowidth'];
	  if(isset($info['icoheight']))
	  $this->icoheight=$info['icoheight'];

	  $this->icopath=isset($info['icopath'])?$info['icopath']:$this->uploadpath;
	  $this->fangpath=isset($info['fangpath'])?$info['fangpath']:$this->uploadpath;
	  $this->fangsize=isset($info['fangsize'])?$info['fangsize']:75;



	  if(isset($info['files']))
	  {
	   	$this->setFiles($info['files']);

	  }else{
	    	$this->isup=false;
		   $this->message="no upimages from !";
	  }
	  if(isset($info['uploadsize']))
	  {
	   	$this->uploadsize=$info['uploadsize'];
		if($this->info['size']>$this->uploadsize)
		{
		   $this->isup=false;
		   $this->message="up images too size !";
		}

	  }
	   	$this->nzsize=isset($info['nzsize'])?$info['nzsize']:$this->icowidth;
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
	function setFangpath($path)
	{
	  if($path!="")
	  {
	    $this->fangpath=$path;
	  }
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
	function setBasename($name,$fix=false)
	{
	  if($name!="")
	  {
		if($fix)
		{
			$upfile=pathinfo($this->safeName($name));
			$name=basename($name,".".$upfile["extension"]);
		}
	    $this->basename=$name;
		$this->icopic=$name."_ico";
	  }
	  Return $this;
	}
	function init() {
	    if(empty($this->basename))
		{
		 $this->basename=date("Ymdhis").rand(10,99);
		 $this->icopic=$this->basename."_ico";
	    }
			 //�趨����
       switch($this->info['type'])
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
			  $this->isup=false;
		      $this->message="images type error!";
	   }
	   if($this->isup&&$this->info['size']>$this->uploadsize)
	   {
	     $this->isup=false;
		 $this->message="up images too size !";
	   }
	   Return $this->isup;

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
	function upload($up=array(),$updatename="") {
	  if(!$this->isup)
	   { 
		  echo('aa');
	      Return false;
	   }
	   if(empty($this->extfile)||in_array($this->extfile,array('gif','jpg','png')))
	   {
	     $this->init();
	   }
	   if($updatename!="")
	   {
	     $this->basename=$updatename;
		 $this->icopic=$updatename."_ico";
	   }
	  if(!move_uploaded_file($this->info['tmp_name'],$this->uploadpath.$this->basename.$this->extfile))
	   {
	     $this->isup=false;
		 $this->message="uploaded error!";
		 Return $this->isup;
	   }
	   
	   $this->imgpic=$this->uploadpath.$this->basename.$this->extfile;
	   if(empty($up)) Return true;
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
			//���������ˮӡ����ô��С�ͷŴ���ˮӡ
			if(isset($up['water'])) $this->shuiyin=true;
			foreach($up as $cutimg)
		    {			
				switch($cutimg)
				{
				  case 'fix_side': //�̶�һ�ߴ�С
					$this->Resizenumm();					
					break;
				  case 'size_ico':
					$this->Resizeauto();
					break;
				  case 'fix_ico':
					$this->ResizeIco();
					break;
				  case 'fill_size':
					  $this->Resizecut();
				      break;
				  case 'auto_ico':
					$this->ResizeImage();
					break;
				 }
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
	 ImageJpeg ($newim,$this->uploadpath.$this->icopic.".jpg",100); 
	 ImageDestroy ($newim); 
	}else{ 
	 ImageJpeg ($this->im,$this->uploadpath.$this->icopic.".jpg",100); 
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
    $this->newim = imagecreatetruecolor($this->icowidth,$this->icoheight);
	imagecopyresampled($this->newim,$this->im, 0, 0, 0,0,$this->icowidth,$this->icoheight,$this->imgwidth,$this->imgheight); 
     if($this->shuiyin) $this->shuiyin();
     ImageJpeg($this->newim,$this->uploadpath.$this->icopic.".jpg",100); 
	 imagedestroy($this->newim);
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
     ImageJpeg($this->newim,$this->uploadpath.$this->icopic.".jpg",100); 
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
    ImageJpeg($this->newim,$this->uploadpath.$this->icopic.".jpg",100); 
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

	ImageJpeg($newim,$this->fangpath.$this->basename."_".$size.".jpg",100); 
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