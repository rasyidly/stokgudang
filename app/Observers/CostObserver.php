<?php

namespace App\Observers;

class CostObserver
{
    public function created($supply)
    {
        $supply->inventory()->decrement('quantity', $supply->quantity);
    }

    public function deleted($supply)
    {
        $supply->inventory()->increment('quantity', $supply->quantity);
    }

    public function restored($supply)
    {
        $this->created($supply);
    }
}
