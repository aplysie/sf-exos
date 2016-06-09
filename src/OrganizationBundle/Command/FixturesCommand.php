<?php

namespace OrganizationBundle\Command;

use OrganizationBundle\Entity\Organization;
use OrganizationBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixturesCommand
 */
class FixturesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('organization:fixtures:load')
        ;
    }
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->loadDataFromJsonFile(
            $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/fixtures/companies.json'
        );
        $this->loadFixtures($data);
    }

    /**
     * @param string $file
     * @return array
     */
    protected function loadDataFromJsonFile($file)
    {
        $jsonData = file_get_contents($file);
        return json_decode($jsonData, true);
    }

    /**
     * @param array $data
     */
    protected function loadFixtures($data)
    {
        $om = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($data as $item) {
            $organization = new Organization();
            $organization->setName($item['name']);
            $om->persist($organization);

            foreach ($item['users'] as $userData) {
                $user = new User();
                $user->setFirstName($userData['firstName']);
                $user->setLastName($userData['lastName']);
                $user->setBirthDate(new \DateTime($userData['birthDate']));
                $organization->addUser($user);
                $om->persist($user);
            }
        }

        $om->flush();
    }
}
