<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DbCliCommand extends Command
{
    protected static $defaultName = 'db:cli';

    protected function configure()
    {
        $this
            ->setDescription('Database CLI')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $url_parts = parse_url(getenv('DATABASE_URL'));
        $username = $url_parts['user'];
        $password = $url_parts['pass'];
        $host = $url_parts['host'];
        $database = ltrim($url_parts['path'], '/');

        /*
        This code taken from WP CLI:
        https://github.com/wp-cli/db-command/blob/master/src/DB_Command.php
        https://github.com/wp-cli/wp-cli/blob/master/php/utils.php
        */

        //Backup the old password if it exists
        $old_pass = getenv('MYSQL_PWD');

        //Temporarily put the current password into the global variable
        putenv('MYSQL_PWD=' . $password);

        //Our command
        $cmd = sprintf('/usr/bin/env mysql --user=%1$s --database=%2$s --host=%3$s', $username, $database, $host);

        //Proc/pips
        $descriptors = [ STDIN, STDOUT, STDERR ];
        $proc = proc_open($cmd, $descriptors, $pipes);
        if (! $proc) {
            exit(1);
        }
        $r = proc_close($proc);

        //Reset password if it existed
        putenv('MYSQL_PWD=' . $old_pass);
        if ($r) {
            exit($r);
        }
    }
}
