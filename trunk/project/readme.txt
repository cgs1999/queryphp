testproject����ĿĿ¼
��Ŀ¼��www��
ÿ����Ŀ�����Լ��� 
 config �����ļ�Ŀ¼ precore.ini.php  aftercore.ini.php
 model  ����Ŀģ���ļ�
 router ����Ŀ·���ļ�
 view   ��ͼ�ļ����԰�·��Ŀ¼���
 class  ����Ŀʹ�õ���ͨ���ļ�
 lib    �������ļ�����
��Ŀ¼

ormtest.php�Ƕ���ʹ��ORM���ݿ������Ƶ�
���԰ѱ�ORM�������������Ŀ��ʹ��
ORMʹ�÷�ʽ���ĵ�

ȥ��index.php����
http://www.app.com/queryphp/project/index.php/default/index
���ú�ϣ�����Ա��������
http://www.app.com/queryphp/project/default/index.html
�ǵ���inc.ini.php�ļ�����Ӷ�һ��
 $config['html']='.html'; ���ǰ���Ǹ�//ȥ���������Ϳ�����
�����������ò��ԣ�AllowOverride FileInfo ����ʹ��.htaccess����
<VirtualHost *:80>
    <Directory "D:/work">
        Order allow,deny
        Allow from all
	AllowOverride FileInfo 
    </Directory> 
  DocumentRoot "D:/work"
  ServerName "www.app.com"
</VirtualHost>


.htaccess�ļ� ����projectĿ¼���� ����ÿ����ĿĿ¼���棬�����ͻ����ͬ��Ŀ¼
index.php�ļ�

  RewriteEngine On

  # uncomment the following line, if you are having trouble
  # getting no_script_name to work
  #RewriteBase /

  # we skip all files with .something
  #RewriteCond %{REQUEST_URI} \..+$
  #RewriteCond %{REQUEST_URI} !\.html$
  #RewriteRule .* - [L]

  # we check if the .html version is here (caching)
  RewriteRule ^$ index.html [QSA]
  RewriteRule ^([^.]+)$ $1.html [QSA]
  RewriteCond %{REQUEST_FILENAME} !-f

  # no, so we redirect to our front web controller
  RewriteRule ^(.*)$ index.php [QSA,L]