<?php

namespace Spin\Provider;

use Spin\Exception;
use Spin\Http\Response;
use Spin\Provider;

class ErrorProvider extends Provider
{
    public function bind()
    {
        $template = $this->container->resolve("template");

        $this->bindMissingHandler($template);
        $this->bindMethodError($template);
        $this->bindServerError($template);
    }

    /**
     * @param $template
     */
    public function bindMissingHandler($template)
    {
        $this->container->bindShared(
            "error.missing",
            function () use ($template) {
                return function (Response $response) use ($template) {
                    $response->writeHead(404, ["content-type" => "text/html"]);
                    $response->end($template->render("error/missing"));
                };
            }
        );
    }

    /**
     * @param $template
     */
    public function bindMethodError($template)
    {
        $this->container->bindShared(
            "error.method",
            function () use ($template) {
                return function (Response $response) use ($template) {
                    $response->writeHead(405, ["content-type" => "text/html"]);
                    $response->end($template->render("error/method"));
                };
            }
        );
    }

    /**
     * @param $template
     */
    public function bindServerError($template)
    {
        $this->container->bindShared(
            "error.server",
            function () use ($template) {
                return function (Response $response, Exception $exception) use ($template) {
                    $response->writeHead(500, ["content-type" => "text/html"]);

                    if (getenv("application.debug")) {
                        $response->end(
                            $template->render("error/server/advanced", ["exception" => $exception])
                        );
                    } else {
                        $response->end(
                            $markup = $template->render("error/server/basic")
                        );
                    }
                };
            }
        );
    }
}
