<?php
namespace App\Service\RabbitMQ;

use App\Entity\Job;
use App\Entity\Resume;
use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class SendEmailConsumer implements ConsumerInterface
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SendEmailConsumer constructor.
     * @param MailerInterface $mailer
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    /**
     * @param AMQPMessage $msg
     * @return mixed|void
     * @throws TransportExceptionInterface
     */
    public function execute(AMQPMessage $msg)
    {
        $parameters = json_decode($msg->body, true);

        /** @var Resume $resume */
        $resume = $this->entityManager->getRepository(Resume::class)->find($parameters['resume']);

        /** @var Job $job */
        $job = $this->entityManager->getRepository(Job::class)->find($parameters['job']);

        $email = (new TemplatedEmail())
            ->from(new Address($parameters['address'], $parameters['name']))
            ->to($parameters['to'])
            ->subject($parameters['subject'])
            ->htmlTemplate($parameters['htmlTemplate'])
            ->context([
                'resume' => $resume,
                'job'    => $job,
            ]);
        $this->mailer->send($email);
    }
}