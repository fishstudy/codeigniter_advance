#bin/bash
read -p "enter the table name: " table
for temp in controllers logics models daos
do
/usr/local/php/bin/php /data/release/webserver/third/creater/admin.php admin $temp $table 
done
