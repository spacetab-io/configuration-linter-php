<?php

namespace Spacetab\Linter\OutputPresenter;

class ArrayToConsolePresenter implements PresenterInterface
{
    public function present(array $result)
    {
        foreach ($result as $string){
            echo $string;
        }
    }
}