<?php

namespace QurbanAli\PescheckWrapper\Resources;

class ChecksResource extends AbstractResource
{
    /**
     * Get all available checks.
     *
     * @return array
     */
    public function all(): array
    {
        $response = $this->getHttpClient()->get('/api/v1/checks/');

        return $response->getData();
    }
}
