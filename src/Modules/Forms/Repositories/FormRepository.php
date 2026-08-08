<?php

declare(strict_types=1);

namespace WPAIOS\Modules\Forms\Repositories;

use WPAIOS\Modules\Forms\Contracts\FormRepositoryInterface;
use WPAIOS\Modules\Forms\Models\FormModel;
use WPAIOS\Modules\Forms\Services\FormDiscovery;

/**
 * Class FormRepository
 * Central data access repository delegating to active provider adapters.
 */
class FormRepository implements FormRepositoryInterface
{
    public function __construct(private FormDiscovery $discovery)
    {
    }

    public function findAll(?string $provider = null): array
    {
        if ($provider) {
            $adapter = $this->discovery->getAdapter($provider);
            return $adapter ? $adapter->getForms() : [];
        }

        $all = [];
        foreach ($this->discovery->getActiveAdapters() as $adapter) {
            $all = array_merge($all, $adapter->getForms());
        }
        return $all;
    }

    public function findById(string|int $id, ?string $provider = null): ?FormModel
    {
        if ($provider) {
            $adapter = $this->discovery->getAdapter($provider);
            return $adapter ? $adapter->getForm($id) : null;
        }

        foreach ($this->discovery->getActiveAdapters() as $adapter) {
            $form = $adapter->getForm($id);
            if ($form) {
                return $form;
            }
        }

        return null;
    }

    public function save(FormModel $form, ?string $provider = null): FormModel
    {
        $targetSlug = $provider ?? $form->getProviderSlug();
        $adapter = $this->discovery->getAdapter($targetSlug) ?? $this->discovery->getPrimaryAdapter();

        if (!$adapter) {
            return $form;
        }

        $existing = $adapter->getForm($form->getId());
        if ($existing) {
            $adapter->updateForm($form->getId(), $form->toArray());
            return $form;
        }

        return $adapter->createForm($form->toArray());
    }

    public function delete(string|int $id, ?string $provider = null): bool
    {
        if ($provider) {
            $adapter = $this->discovery->getAdapter($provider);
            return $adapter ? $adapter->deleteForm($id) : false;
        }

        foreach ($this->discovery->getActiveAdapters() as $adapter) {
            if ($adapter->deleteForm($id)) {
                return true;
            }
        }

        return false;
    }

    public function findSubmissions(string|int $formId, int $limit = 20, int $offset = 0, array $filters = [], ?string $provider = null): array
    {
        if ($provider) {
            $adapter = $this->discovery->getAdapter($provider);
            return $adapter ? $adapter->getSubmissions($formId, $limit, $offset, $filters) : [];
        }

        $primary = $this->discovery->getPrimaryAdapter();
        return $primary ? $primary->getSubmissions($formId, $limit, $offset, $filters) : [];
    }
}
