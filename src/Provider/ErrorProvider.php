<?php

namespace Spin\Provider;

use Spin\Exception;
use Spin\Http\Response;
use Spin\Provider;

class ErrorProvider extends Provider
{
    /**
     * @param callable $resolve
     */
    public function bind(callable $resolve)
    {
        $template = $resolve("template");

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
                    $response->headers(404, ["content-type" => "text/html"]);
                    $response->render($template->render("error/missing"));
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
                    $response->headers(405, ["content-type" => "text/html"]);
                    $response->render($template->render("error/method"));
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
                    $response->headers(500, ["content-type" => "text/html"]);

                    if (getenv("application.debug")) {
                        $response->render(
                            $template->render("error/server/advanced", ["exception" => $exception])
                        );
                    } else {
                        $response->render(
                            $markup = $template->render("error/server/basic")
                        );
                    }
                };
            }
        );
    }
}
