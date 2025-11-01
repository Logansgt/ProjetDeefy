<?php
declare(strict_types = 1);
namespace iutnc\deefy\render;

abstract class AudioTrackRenderer implements Renderer{

    function render(int $selector): String{
        switch($selector){
            case Renderer::COMPACT:
                return $this->renderCompact();
            case Renderer::LONG:
                return $this->renderLong();
            default:
                return "<p>Mode d'affichage inconnu</p>";
        }
    }

    protected abstract function renderCompact(): String;
    protected abstract function renderLong(): String;

}

