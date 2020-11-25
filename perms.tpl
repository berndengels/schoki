#!/bin/sh
group=_www

sudo chgrp -R $group storage/logs storage/framework storage/app bootstrap/cache database/snapshots
sudo chmod -R ugo+rwx storage/logs storage/framework storage/app bootstrap/cache database/snapshots
sudo chgrp -R staff .
sudo chmod -R g+rwX .
find . -type d -exec chmod g+s '{}' +

#insufficient permission for adding an object to repository database .git/objects
# "bootstrap-datepicker": "github:eternicode/bootstrap-datepicker",
