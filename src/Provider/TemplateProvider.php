<?php

namespace Spin\Provider;

use League\Plates\Engine;
use Spin\Provider;

class TemplateProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $this->container->bindShared(
            "template",
            function () use ($resolve) {
                $templates = $_SERVER["PWD"] . "/resources/templates";

                if (getenv("app.paths.templates")) {
                    $templates = getenv("app.paths.templates");
                }

                return new Engine($templates);
            }
        );
    }
}
