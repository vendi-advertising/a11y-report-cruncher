<?php

declare(strict_types=1);

namespace App\Demo;

final class DemoCommand
{
    public $command_string;

    public $title;

    public $question;

    public function __construct(string $command_string, string $title, string $question = null)
    {
        if(!$question){
            $question = sprintf('Would you like to create a new %1$s?', mb_strtolower($title));
        }

        $this->command_string = $command_string;
        $this->title = $title;
        $this->question = $question;
    }
}
