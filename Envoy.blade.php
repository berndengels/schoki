@servers(['schokitest' => 'schoki@schokoladen-mitte.de','prod' => 'schoki@schokoladen-mitte.de','test' => 'goldenacker@goldenacker.de'])

@task('test', ['on' => 'test'])
echo "testing...";
cd ./schoki.goldenacker.de
pwd
git pull origin frontend
./clearme
npm run prod
@endtask

@task('schokitest', ['on' => 'schokitest'])
echo "schokitest...";
cd ./web/schokoladen-mitte.de/test.schokoladen-mitte.de/repo
pwd
PATH=$PATH:/home/schoki/.nvm/versions/node/v14.15.2/bin
git config pull.rebase false
git pull origin frontend
./clearme
npm run prod
@endtask

@task('prod', ['on' => 'prod'])
echo "prod...";
cd ./web/schokoladen-mitte.de/www.schokoladen-mitte.de
pwd
git config pull.rebase false
git pull origin master
./clearme
/home/schoki/.nvm/versions/node/v14.15.2/bin/npm run prod
@endtask
