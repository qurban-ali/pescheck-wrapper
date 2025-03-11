<?php

namespace QurbanAli\PESCheck\Resources;

class ChecksResource extends AbstractResource
{
    /**
     * Get all available checks.
     *
     * @return array
     */
    public function all(): array
    {
        $response = $this->getHttpClient()->get('/v1/checks/');

        return $response->getData();
    }
}
