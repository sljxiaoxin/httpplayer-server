【apache】windows下htdoc引入其他目录链接，或称为目录别名  
 <IfModule alias_module> 
               #添加如下几行，即可将非document_root下的程序或资源目录引入到document_root，并且能通过url访问。
     Alias /testlink "D:/testlink/"
    <Directory "D:/testlink">
       Require all granted      #必须允许全部请求
    </Directory>
  </IfModule>
