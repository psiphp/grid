<?php

declare(strict_types=1);

namespace Psi\Component\Grid;

final class ActionResponse implements ActionResponseInterface
{
    private $redirect;
    private $redirectParams;
    private $affected;
    private $errors;

    private function __construct()
    {
    }

    public static function create(array $options): ActionResponse
    {
        $defaults = [
            'redirect' => null,
            'redirectParams' => [],
            'errors' => [],
            'affected' => 0,
        ];

        if ($diff = array_diff(array_keys($options), array_keys($defaults))) {
            throw new \InvalidArgumentException(sprintf(
                'Unexpected keys for action response: "%s", valid keys: "%s"',
                implode('", "', $diff), implode('", "', array_keys($defaults))
            ));
        }

        $options = array_merge($defaults, $options);

        if ($options['affected'] < 0) {
            throw new \InvalidArgumentException(sprintf(
                'Number of affected records cannot be negative (got "%s")',
                $options['affected']
            ));
        }

        $response = new self();
        $response->redirect = $options['redirect'];
        $response->redirectParams = $options['redirectParams'];
        $response->errors = $options['errors'];
        $response->affected = $options['affected'];

        return $response;
    }

    public function hasRedirect(): bool
    {
        return isset($this->redirect);
    }

    public function getRedirect(): string
    {
        return $this->redirect;
    }

    public function getRedirectParams(): array
    {
        return $this->redirectParams;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return false === empty($this->errors);
    }

    public function getAffectedRecordCount(): int
    {
        return $this->affected;
    }

    public function hasAffectedRecords(): bool
    {
        return $this->affected > 0;
    }
}
