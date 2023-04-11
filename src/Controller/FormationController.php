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

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/pdf/{id}', name:'app_formation_pdf', methods:['GET'])]
function pdf(Formation $formation): Response
    {
    $pdf = new \TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('SIO1 TEAM');
    $pdf->SetTitle($formation->getId());
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->AddPage();
   
    $pdf->setCellPaddings(1, 1, 1, 1);
    $pdf->setCellMargins(0, 0, 1, 1);
    $pdf->Image('img/fcpro3.jpg', 10, 12, 30,26, 'JPG', '', '', true, 150, '', false, false, 0, false, false, false);
    $pdf->SetFont('helvetica', 'b', 18);
    $pdf->SetFillColor(160, 222, 255);
    $pdf->setTextColor(0,63,144);
    $pdf->setXY(41,10);
    $pdf->MultiCell(159, 20, "Programme de Formation", 1, 'C', 1, 1, '', '', true, 0, false, true, 0, 'M');
    $pdf->setTextColor(0,0,0);
    $pdf->SetFont('helvetica', 'b', 16);
    $pdf->setXY(41,31);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->MultiCell(159, 10, $formation->getName(), 0, 'C', 1, 1, '', '', true, 0, false, true, 10, 'M');

    return $pdf->Output('fc_pro' . $formation->getId() . ".pdf");
}
#[Route('/catalog', name:'app_formation_catalog', methods:['GET'])]
function catalog(FormationRepository $formationRepository): Response
    {
    return $this->render('formation/catalog.html.twig', [
        'formations' => $formationRepository->findAllInTheFutur(),
    ]);
}

#[Route('/{futur}', name:'app_formation_futur', methods:['GET'])]
function futur(FormationRepository $formationRepository): Response
    {
    return $this->render('formation/futur.html.twig', [
        'formations' => $formationRepository->findAllInTheFutur(),
    ]);
}

#[Route('/', name:'app_formation_index', methods:['GET'])]
function index(FormationRepository $formationRepository): Response
    {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    return $this->render('formation/index.html.twig', [
        'formations' => $formationRepository->findAll(),
    ]);
}

#[Route('/new', name:'app_formation_new', methods:['GET', 'POST'])]
function new (Request $request, FormationRepository $formationRepository): Response {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    $formation = new Formation();
    $formation->setCreateBy($this->getUser());
    $formation->setCreatedAt(new DateTimeImmutable());
    $form = $this->createForm(Formation1Type::class, $formation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
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
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

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
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->request->get('_token'))) {
        $formationRepository->remove($formation, true);
    }

    return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
}
}
