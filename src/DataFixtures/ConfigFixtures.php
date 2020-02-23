<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $map = [
            'mail.recipients_dev' => 'E-mail разработчиков',
            'mail.recipients'     => 'Остальные получатели писем',
            'mail.fromEmail'      => 'Email отправителя писем',
            'mail.fromName'       => 'Имя отправителя писем',
        ];
        foreach ($map as $system_name => $title) {
            $config = new Config();
            $config->setName($system_name);
            $config->setTitle($title);
            $config->setValue('');
            $manager->persist($config);
        }
        
        $manager->flush();
    }
}
