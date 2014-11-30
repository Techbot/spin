<?php

namespace Spin\Interfaces;

interface ApplicationAware
{
    /**
     * @param Application $app
     *
     * @return $this
     */
    public function setApplication(Application $app);
}
