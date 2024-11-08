@servers(['schoki' => 'schoki@schokoladen-mitte.de'])

@setup
	$npm = './npm.sh';
@endsetup

@task('test', ['on' => 'schoki'])
echo "testing...";
cd ./web/schokoladen-mitte.de/test.schokoladen-mitte.de/repo
pwd
git config pull.rebase false
git pull origin frontend
{{ $npm }} run prod
@endtask

@task('prod', ['on' => 'schoki'])
echo "prod...";
cd ./web/schokoladen-mitte.de/www.schokoladen-mitte.de
pwd
git config pull.rebase false
git pull origin master
{{ $npm }} run prod
@endtask
