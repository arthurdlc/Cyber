<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ImageUploaderHelper
{
    private $slugger;
    public function __construct(SluggerInterface $slugger, TranslatorInterface $translator)
    {
        $this->slugger = $slugger;
        $this->translator = $translator;
    }
    public function uploadImage($form, $formation): String
    {
        $errorMessage = "";
        $imageFile = $form->get('image')->getData();

        // this condition is needed because the 'image' field is not required
        // so the PDF file must be processed only when a file is uploaded
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                $errorMessage = $e->getMessage();
            }
            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $formation->setImageFilename($newFilename);
        }
        return $errorMessage;
    }
}
