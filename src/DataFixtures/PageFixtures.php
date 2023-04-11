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
        $formationcenter->setNumPage(3);
        $formationcenter->setTitle($this->translator->trans("Catalog"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Catalog") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setNumPage(4);
        $formationcenter->setTitle($this->translator->trans("Rate a formation"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Rate a formation") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setNumPage(5);
        $formationcenter->setTitle($this->translator->trans("Tutos"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Tutos") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setNumPage(6);
        $formationcenter->setTitle($this->translator->trans("Qualiopi's certifcation"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Qualiopi's certifcation") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setNumPage(7);
        $formationcenter->setTitle($this->translator->trans("Contact"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Contact") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setNumPage(8);
        $formationcenter->setTitle($this->translator->trans("legal informations"));
        $formationcenter->setText("<h1>" . $this->translator->trans("legal informations") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setNumPage(9);
        $formationcenter->setTitle($this->translator->trans("Privacy policy"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Privacy policy") . "</h1>");
        $manager->persist($formationcenter);

        $formationcenter = new Page();
        $formationcenter->setNumPage(10);
        $formationcenter->setTitle($this->translator->trans("Reserved"));
        $formationcenter->setText("<h1>" . $this->translator->trans("Reserved") . "</h1>");
        $manager->persist($formationcenter);

        $manager->flush();
    }
}
