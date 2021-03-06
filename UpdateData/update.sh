#!/bin/sh
SEM=`cat CURRENT_SEMESTER`
mv data/${SEM}.txt data/old.txt
./fetchdata.pl
sed "s/{CURRENT_SEMESTER}/${SEM}/g" update-template.sql > update.sql
mysql -u ntucourseupdate -p ntucourse --local-infile < update.sql

./diff.pl > ../diffs/`date "+%Y-%m-%d"`.out

rm update.sql

