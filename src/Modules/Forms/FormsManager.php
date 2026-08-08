<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms;

use WPAIOS\Modules\Forms\Repositories\FormRepository;
use WPAIOS\Modules\Forms\Services\FormDiscovery;
use WPAIOS\Modules\Forms\Services\FormValidator;

/**
 * Class FormsManager
 * Central facade for the Forms module.
 */
class FormsManager
{
    public function __construct(
        private FormDiscovery $discovery,
        private FormRepository $repository,
        private FormValidator $validator
    ) {
    }

    public function getDiscovery(): FormDiscovery
    {
        return $this->discovery;
    }

    public function getRepository(): FormRepository
    {
        return $this->repository;
    }

    public function getValidator(): FormValidator
    {
        return $this->validator;
    }
}
