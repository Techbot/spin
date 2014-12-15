<?php

namespace Spin\Provider;

use League\Plates\Engine;
use Spin\Provider;

class TemplateProvider extends Provider
{
    /**
     * @return void
     */
    public function bind()
    {
        $this->container->bindShared("template", function () {
            $templates = $_SERVER["PWD"]."/resources/templates";

            if (getenv("app.paths.templates")) {
                $templates = getenv("app.paths.templates");
            }

            return new Engine($templates);
        });
    }
}
