<?php

namespace App\services;

use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class uploadPicture
{

    public function upload(UploadedFile $file, $directory)
    {
        // Obtenez l'extension du fichier
        $ext = $file->getClientOriginalExtension();
        $randomFileName = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

        // Obtenez les dimensions de l'image
        list($largeur, $hauteur) = getimagesize($file->getPathname());

        // Définissez le répertoire de téléchargement
        $uploadDirectory = 'images/'.$directory.'/';

        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Définissez le nom du fichier d'upload
        $uploadFileName = 'img-'.$randomFileName.'.webp';

        $url = $uploadDirectory. $uploadFileName;

        // Chemin complet pour l'enregistrement de l'image
        $uploadPath = $uploadDirectory.$uploadFileName;

        // Créez une ressource d'image à partir du fichier uploadé
        $source = null;

        switch ($ext) {
            case 'jpeg':
            case 'jpg':
                $source = imagecreatefromjpeg($file->getPathname());
                break;
            case 'png':
                $source = imagecreatefrompng($file->getPathname());
                break;
            // Ajoutez d'autres formats d'image si nécessaire
            default:
                // Gérez d'autres formats d'image ou affichez une erreur
                break;
        }

        if ($source) {
            // Créez une nouvelle ressource d'image pour le redimensionnement
            $destination = imagecreatetruecolor($largeur, $hauteur);

            // Redimensionnez l'image (vous pouvez utiliser d'autres algorithmes de redimensionnement si nécessaire)
            imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur, $hauteur, $largeur, $hauteur);

            // Enregistrez l'image redimensionnée au format WebP
            imagewebp($destination, $uploadPath, 100);

            // Libérez les ressources
            imagedestroy($source);
            imagedestroy($destination);
        }
        return $url;
    }


}