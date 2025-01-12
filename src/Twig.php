<?php 
namespace App;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

final class Twig
{
    final public static function render(string $view, array $data = []): void 
    {
        try{
            $twig = new Environment(new FilesystemLoader(__DIR__.'/views'));
            $twig -> addGlobal(
                'session',
                $_SESSION,
                
            );

            echo $twig -> render($view.'.twig', $data);
        }catch(LoaderError|SyntaxError|RuntimeError $e){
            echo $e -> getMessage();
        }
    }
}