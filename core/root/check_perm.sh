#!/bin/bash
# Check for electronics programs who need root inside /var/www
chmod -R 755 /var/www
chown -R www-data:www-data /var/www
unset a i
while IFS= read -r -d $'\0' file; do
	file=$(echo $file |sed 's/.cpp//')
	echo "$file"
	chown root:www-data $file
	chmod +sx $file
done < <(find /var/www -name *.cpp -type f -print0)

while IFS= read -r -d $'\0' file; do
	file=$(echo $file |sed 's/.cpp//')
	echo "$file"
	chown root:www-data $file
	chmod +sx $file
done < <(find /var/www -name *.c -type f -print0)