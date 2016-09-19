<?php
/** @var \App\Console\Kernel $this */


$this->command('assets:compile:css', function() {
    /** @var \Illuminate\Foundation\Console\ClosureCommand $this */

    $this->comment($this->getDescription());
    system('cd ' . base_path() . ' && gulp styles');
    $this->getOutput()->writeln('');

})->describe('Start sass compiler');




$this->command('assets:compile:js', function() {
    /** @var \Illuminate\Foundation\Console\ClosureCommand $this */

    $this->comment($this->getDescription());
    system('cd ' . base_path() . ' && gulp scripts');
    $this->getOutput()->writeln('');

})->describe('Start babel compiler');




$this->command('assets:compile',  function() {
    /** @var \Illuminate\Foundation\Console\ClosureCommand $this */

    $this->comment($this->getDescription());
    $this->call('assets:compile:css');
    $this->call('assets:compile:js');

})->describe('Build all assets');




$this->command('assets:install',  function() {
    /** @var \Illuminate\Foundation\Console\ClosureCommand $this */

    $this->comment($this->getDescription());
    system('cd ' . base_path() . ' && npm install');

})->describe('Install frontend libraries');