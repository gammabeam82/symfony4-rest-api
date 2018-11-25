<?php

namespace App\Command;

use App\Repository\UserRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveInactiveUsersCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $repo;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * RemoveInactiveUsersCommand constructor.
     *
     * @param UserRepository $repo
     * @param UserManagerInterface $userManager
     */
    public function __construct(UserRepository $repo, UserManagerInterface $userManager)
    {
        parent::__construct();

        $this->repo = $repo;
        $this->userManager = $userManager;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('app:remove-inactive-users');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = $this->repo->findInactiveUsers();

        if (0 === count($users)) {
            $output->writeln("Nothing to delete.");

            return;
        }

        foreach ($users as $user) {
            $this->userManager->deleteUser($user);
        }

        $output->writeln(sprintf("Deleted: %s", count($users)));
    }
}
