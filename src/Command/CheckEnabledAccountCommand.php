<?php

namespace App\Command;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class CheckEnabledAccountCommand extends Command
{
    private $em;
    private $mailer;
    protected static $defaultName = 'app:check-enabled-account';


    /**
     *
     * @param EntityManagerInterface $em
     * @param MailerInterface $mailer
     * @param string|null $name
     */
    public function __construct(EntityManagerInterface $em, MailerInterface $mailer, string $name = null)
    {
        $this->em = $em;
        $this->mailer = $mailer;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Check enabled account',
            '========================',
            '',
        ]);

        $repository = $this->em->getRepository('App:User\User');

        /** @var User $users */
        $users = $repository->findBy(['enabled' => 0 ]);
        $output->writeln('<fg=green>Checking disabled users...</>');
        if ($users) {
            $output->writeln('<fg=yellow>Found disabled users!</>');
            foreach ($users as $user) {
                $email = (new TemplatedEmail())
                    ->from(new Address('alkatras4321@gmail.com', 'Jobeet'))
                    ->to($user->getEmail())
                    ->subject('Your account is disabled')
                    ->htmlTemplate('disable_user/disable_user_email.html.twig');
                $this->mailer->send($email);
            }
            $output->writeln('<fg=green>Emails sent!</>');
        } else {
            $output->writeln('<fg=green>++++All users enabled++++');
        }
    }
}
