#!/usr/bin/perl
$sem = "93_1";

system("wget -r -N -nH -nd -A .XLS,.xls ftp://ftp.ntu.edu.tw/NTU/course/");

rename($_, uc) while(glob("COU*.*[Ss]"));
@type = qw(01 02 03 04 05 06 07 08 09 10 0B 11 12 13 14 15 16 17 18 19 20);

use CSV;
$dquote = "\"";

open(WD, "> $sem.txt");

foreach (@type) {
	my $formal = $_;
	$formal =~ s/^0//;
	system("/usr/X11R6/bin/ssconvert -T Gnumeric_stf:stf_csv COURSE$_.XLS $_.txt 2>/dev/null");
	open(FD, "/usr/local/bin/iconv -f utf-8 -t iso8859-1 $_.txt | tail -n +2 |");

	$s = "";
	while (<FD>) {
		$_ =~ s/＠/@/g;
		$_ =~ s/\r\n//g;
		$s .= $_;
	}

	@tmp = split("\n", $s);
	for(@tmp) {
		@fields = CSVsplit($_);
		print WD join("\t", $formal, @fields);
		print WD "\n";
	}
	close(FD);
}
close(WD);
unlink($_) while(glob("??.txt"));
