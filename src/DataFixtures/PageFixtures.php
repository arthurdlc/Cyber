<?php

namespace App\DataFixtures;

use App\Entity\Page;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageFixtures extends Fixture
{
    private $translator;
    public function __construct(TranslatorInterface $translator) {
        $this->translator=$translator;
    }
    public function load(ObjectManager $manager): void
    {
        $home = new Page();
        $home->setNumPage(1);
        $home->setTitle($this->translator->trans("Home"));
        $home->setText("<h1>" . $this->translator->trans("Home") . "</h1>");
        $manager->persist($home);

        $formationcenter = new Page();
        $formationcenter->setNumPage(2);
        $formationcenter->setTitle($this->translator->trans("Formation Center"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Formation Center") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setTitle($this->translator->trans("Formation Center"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Formation Center") . "</h1>");
        $manager->persist($formationcenter);

        $manager->flush();
    }
}
