<?php

namespace App\Command;

use App\Repository\ChatRepository;
use App\Repository\UserRepository;
use App\Sockets\Chat;
use Doctrine\ORM\EntityManagerInterface;
use Ratchet\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SocketCommand
 * @package App\Command
 */
class SocketCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ChatRepository
     */
    private $chatRepository;

    /**
     * @param EntityManagerInterface $em
     * @param UserRepository $userRepository
     * @param ChatRepository $chatRepository
     */
    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository,
        ChatRepository $chatRepository
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->chatRepository = $chatRepository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('sockets:start-chat')
            // the short description shown while running "php bin/console list"
            ->setHelp("Starts the chat socket demo")
            // the full command description shown when running the command with
            ->setDescription('Starts the chat socket demo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Chat socket',// A line
            '============',// Another line
            'Starting chat, open your browser.',// Empty line
        ]);

        // The domain of your app as first parameter

        // Note : if you got problems during the initialization, add as third parameter '0.0.0.0'
        // to prevent any error related to localhost :
        // $app = new \Ratchet\App('sandbox', 8080,'0.0.0.0');
        // Domain as first parameter
        $app = new App('localhost', 8080, '127.0.0.1');
        // Add route to chat with the handler as second parameter
        $app->route('{_locale<en|ru>}/company/{id}/chat', new Chat(
            $this->em,
            $this->userRepository,
            $this->chatRepository
        ));

        $app->run();
    }
}
