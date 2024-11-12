<?php

namespace App\Observers;

class SupplyObserver
{
    public function created($supply)
    {
        $supply->inventory()->increment('quantity', $supply->quantity);
    }

    public function deleted($supply)
    {
        $supply->inventory()->decrement('quantity', $supply->quantity);
    }

    public function restored($supply)
    {
        $this->created($supply);
    }
}
