��apache��windows��htdoc��������Ŀ¼���ӣ����ΪĿ¼����  
 <IfModule alias_module> 
               #������¼��У����ɽ���document_root�µĳ������ԴĿ¼���뵽document_root��������ͨ��url���ʡ�
     Alias /testlink "D:/testlink/"
    <Directory "D:/testlink">
       Require all granted      #��������ȫ������
    </Directory>
  </IfModule>
