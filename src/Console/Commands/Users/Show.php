<?php
namespace Festiv\Publ\Console\Commands\Users;

use Festiv\Console\Command;
use Festiv\Publ\Repositories\UserRepository;

class Show extends Command
{
    /** @var string */
    protected $namespace = 'Festiv\Publ\Console\Commands';

    public function __construct(UserRepository $users)
    {
        parent::__construct();
        $this->users = $users;
    }

    public function handle()
    {
        foreach ($this->users->getAllItems() as $user) {
            $this->output->writeln(implode('   ', $user->toArray()));
        }
    }
}
