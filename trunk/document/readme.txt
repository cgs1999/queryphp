ʹ�÷���
����һ�����ݿ��ģ��
$beian=M('beian');

�Զ����aaa bbb�ֶ� $_POST��ҲҪ���������ֶ�
//$beian->autoField(array("aaa","bbbb"));
$data�����
$beian->autoField($data,array("aaa","bbbb"));
ȡ��������ֵ������Ϊ����
//print_r($beian->get(53,54,'asc'));

��ֵ���ֶΡ�
$beian->userid=2;
$beian->language=1;


��ӡ�Ѿ���ֵ�ֶ�
//print_r($beian->data);

���棬����ʾ�ղŲ����ID
//echo $beian->save()->pkid();

��������Ȼ��ɾ��
//echo($beian->pkid(69)->delete());

ȡ�ñ������
//echo $beian->Totalnum();

select��ʾ�����ֶΣ�ArraylistΪ����
//print_r($beian->getAll("userid,language")->record); //��Ϊrecord��

��ѯ����userid��languageΪ1��5��fetchΪȡֵ
print_r($beian->whereUseridAndLanguage('1','5')->fetch()->record);

ȡ��������������ʾ�����ֶΣ�����
//print_r($beian->get('confid,userid,language',53,54,'asc')->record);

�������ֵ
//echo $beian->confid;

����ĳ������ֶ��ۼ�1
$beian->colupdate('tplid');

������modelĿ¼�£�*****Model.class.php�ļ�������ӷ�����

����ģ�ͺ�������Ͽ���ʹ��
print_r($booktype=M("booktype")->getAll());