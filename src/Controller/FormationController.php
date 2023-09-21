<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\Formation1Type;
use App\Repository\FormationRepository;
use App\Services\ImageUploaderHelper;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/pdf/{id}', name:'app_formation_pdf', methods:['GET'])]
function pdf(Formation $formation): Response
        {
    $pdf = new \TCPDF ();

    $pdf->SetAuthor('Alexia Hebert');
    $pdf->SetTitle($formation->getName());
    $pdf->SetFont('times', '', 14);
    $pdf->setCellPaddings(1, 1, 1, 1);
    $pdf->setCellMargins(1, 1, 1, 1);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetFillColor(160, 222, 255);
    $pdf->SetTextColor(0, 63, 144);
    $pdf->Image('images/fcpro.jpg', 10, 10, 37, 35, 'JPG', 'https://fcpro-bdolhin.bts.sio-ndlp.fr/', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->MultiCell(187, 20, "PROGRAMME DE FORMATION", 0, 'C', 1, 1, '', '', true, 0, false, true, 20, 'M');

    $pdf->SetFont('helvetica', 'B', 17);
    $pdf->SetFillColor(225, 225, 230);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(187, 10, $formation->getName(), 0, 'C', 1, 1, '', '', true);

    $pdf->setCellPaddings(3, 3, 3, 3);
    $textg = '
        <style> .blue { color: rgb(0, 63,144); } .link { color: rgb(100,0,0); }</style>
        <br>
        <p class="blue">
<b>Tarifs :</b></p><p>
' . $formation->getPrice() . ' €
        </p><br>
        <p class="blue">
<b>Modalités :</b>
        </p><p>
Formation individuelle<br>
2 journées de formation en présentiel<br>
14 heures (2x7 heures)
        </p><br>
        <p class="blue">
<b>Contact :</b>
        </p><p>
<b>Alexia HEBERT, responsable de FCPRO</b><br>
Service de Formation Professionnelle
Continue de l’OGEC Notre Dame de la Providence<br>
<br>
9, rue chanoine Bérenger BP 340, 50300 AVRANCHES.<br>
Tel 02 33 58 02 22<br>
mail : <span class="link">fcpro@ndlpavranches.fr</span><br>
<br>
N° activité 25500040250<br>
OF certifié QUALIOPI pour les actions de formations<br>
<br>
Site Web : <span class="link">https://ndlpavranches.fr/fc-pro/</span><br>
        </p>';

    $pdf->SetFont('helvetica', '', 11);
    $pdf->SetFillColor(225, 225, 230);
    $pdf->writeHTMLCell(65, 230, "", "", $textg, 0, 0, 1, true, '', true);

    $textd = '
        <style>hr { color: rgb(0, 63,144); }</style>
        <p><b>Objectif de la formation</b>
        <hr>
        <ul><li>Objectif...</li><li>Objectif...</li><li>Objectif...</li></ul>
        <b>Prérequis necessaire / public visé</b>
        <hr>
        <ul><li>Prérequis...</li><li>Prérequis...</li></ul>
        <b>Modalités d\'accès et d\'inscription</b>
        <hr><br>
        <div>
<u>Dates</u> : ' . $formation->getStartDateTime()->format('d/m/Y') . ' à ' . $formation->getEndDateTime()->format('d/m/Y') . '<br>
<u>Lieu</u> : ..
<br><br>
Nombre de stagiaires minimal : 0 – Nombre de stagiaires maximal : ' . $formation->getCapacity() . '<br>
<i>Si le minimum requis de participants n’est pas atteint la session de formation
ne pourra avoir lieu.</i>
<br><br>

<b>Le chef d’établissement doit inscrire ses personnels auprès de FC PRO
(contact par mail ou par téléphone) au plus tard 7 jours avant le début de
la formation et faire la demande de prise en charge (sur OPCABOX pour
le personnel OGEC, auprès de FORMIRIS pour le personnel enseignant)
au plus tard 15 jours avant la date de début de la formation. L’inscription
des personnels enseignants sur FORMIRIS devra se faire 7 jours avant
la date de début de formation.</b></div><br>
<b>Moyens pédagogiques et techniques</b>
        <hr><br>
        <div>Supports visuels (power-point), apports théoriques et mises en situation.</div><br>
        <b>Modalité d\'évaluation</b>
        <hr><br>
        <div>Questionnaire de positionnement en début de formation + recueil des attentes
des participants.
Questionnaire d’évaluation des connaissances acquises en fin de formation.
Evaluation de satisfaction de la formation par les stagiaires.</div>
        </p>';

    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetFillColor(255, 255, 255);
    $pdf->writeHTMLCell(120, 230, "", "", $textd, 0, 0, 1, true, '', true);

    return $pdf->Output('fcpro-formation-' . $formation->getId() . '.pdf', 'I');
}

#[Route('/{id}/duplicate', name:'app_formation_duplicate', methods:['GET', 'POST'])]
function duplicate(Request $request, FormationRepository $formationRepository, TranslatorInterface $translator, Formation $formation): Response
    {
    $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');

    $formation2 = new Formation();
    $formation2->setCreatedAt($formation->getCreatedAt());
    $formation2->setCreateBy($formation->getCreateBy());
    $formation2->setContent($formation->getContent());

    $formation2->setCapacity($formation->getCapacity());
    $formation2->setStartDateTime($formation->getStartDateTime());
    $formation2->setEndDateTime($formation->getEndDateTime());
    $formation2->setImageFileName($formation->getImageFileName());
    $formation2->setName($formation->getName());
    $formation2->setPrice($formation->getPrice());

    $formationRepository->save($formation2, true);
    $this->addFlash('success', $translator->trans('The formation is copied'));

    return $this->redirectToRoute('app_formation_index');
}

#[Route('/catalog', name:'app_formation_catalog', methods:['GET'])]
function catalog(FormationRepository $formationRepository): Response
    {
    return $this->render('formation/catalog.html.twig', [
        'formations' => $formationRepository->findAllInTheFutur(),
    ]);
}

#[Route('/futur', name:'app_formation_futur', methods:['GET'])]
function futur(FormationRepository $formationRepository): Response
    {
    $formationsPerThree = array();

    $formations = $formationRepository->findAllInTheFutur();

    $i = 1;
    $j = 0;
    foreach ($formations as $formation) {
        $i++;
        if ($i > 3) {
            $j++;
            $i = 1;
        }
        $formationsPerThree[$j][$i] = $formation;
    }
    dump($formations);
    dump($formationsPerThree);

    return $this->render('formation/futur.html.twig', ['formationsPerThree' => $formationsPerThree]);
}

#[Route('/', name:'app_formation_index', methods:['GET'])]
function index(FormationRepository $formationRepository): Response
    {

    return $this->render('formation/index.html.twig', [
        'formations' => $formationRepository->findAll(),
    ]);
}

#[Route('/new', name:'app_formation_new', methods:['GET', 'POST'])]
function new (Request $request, FormationRepository $formationRepository): Response{

    $formation = new Formation();
    $formation->setCreateBy($this->getUser());
    $formation->setCreatedAt(new DateTimeImmutable());
    $form = $this->createForm(Formation1Type::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {



        if (empty($formation->getImageFileName())) {
            $formation->setImageFileName("fcpro.png");
        }


        $formationRepository->save($formation, true);

        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('formation/new.html.twig', [
        'formation' => $formation,
        'form' => $form,
    ]);
}

#[Route('/{id}', name:'app_formation_show', methods:['GET'])]
function show(Formation $formation): Response
    {
    return $this->render('formation/show.html.twig', [
        'formation' => $formation,
    ]);
}

#[Route('/{id}/edit', name:'app_formation_edit', methods:['GET', 'POST'])]
function edit(Request $request, Formation $formation, ImageUploaderHelper $imageUploaderHelper, FormationRepository $formationRepository, SluggerInterface $slugger): Response
    {

    $form = $this->createForm(Formation1Type::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $errorMessage = $imageUploaderHelper->uploadImage($form, $formation);
        if (!empty($errorMessage)) {
            $this->addFlash('danger', $translator->trans('An error is append: ') . $errorMessage);

        }
        $formationRepository->save($formation, true);
        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('formation/edit.html.twig', [
        'formation' => $formation,
        'form' => $form,
    ]);
}

#[Route('/{id}', name:'app_formation_delete', methods:['POST'])]
function delete(Request $request, Formation $formation, FormationRepository $formationRepository): Response
    {

    if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->request->get('_token'))) {
        $formationRepository->remove($formation, true);
    }

    return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
}
}
