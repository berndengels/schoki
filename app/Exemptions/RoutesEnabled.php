<?php

namespace App\Exemption;

use MisterPhilip\MaintenanceMode\Exemptions\MaintenanceModeExemption;

class RoutesEnabled extends MaintenanceModeExemption
{

    private $exemptRoutes = [
        '/login',
        '/logout',
        '/admin/*',
        '/contact/formBands',
        '/static/map',
        '/page/*',
        '/events',
        '/events/category/*',
    ];

    /**
     * @inheritDoc
     */
    public function isExempt()
    {
        $routeExemptions = collect($this->exemptRoutes);
        $currentRoute = $this->app['request']->getPathInfo();

        if($routeExemptions->count() > 0) {
            foreach($routeExemptions as $exemption) {
                if( preg_match("#$exemption#", $currentRoute)) {
                    return true;
                }
            }
        }
        return false;
    }
}
