<?php

namespace App\Exceptions;

use Exception;

class InsufficientStockException extends Exception
{
    protected $itemName;
    protected $requestedQuantity;
    protected $availableStock;

    public function __construct(string $itemName, int $requestedQuantity, int $availableStock)
    {
        $this->itemName = $itemName;
        $this->requestedQuantity = $requestedQuantity;
        $this->availableStock = $availableStock;
        
        $message = "Stok tidak mencukupi untuk {$itemName}. Diminta: {$requestedQuantity}, Tersedia: {$availableStock}";
        parent::__construct($message, 422);
    }

    public function render($request)
    {
        return back()
            ->withErrors([
                'quantity' => $this->getMessage()
            ])
            ->withInput();
    }

    public function getItemName(): string
    {
        return $this->itemName;
    }

    public function getRequestedQuantity(): int
    {
        return $this->requestedQuantity;
    }

    public function getAvailableStock(): int
    {
        return $this->availableStock;
    }
}
