<?php
namespace TypeRocket\Elements\Fields;

use TypeRocket\Elements\Traits\ImageFeaturesTrait;
use TypeRocket\Html\Html;

class Image extends Field implements ScriptField
{
    use ImageFeaturesTrait;

    /**
     * Run on construction
     */
    protected function init()
    {
        $this->setType( 'image' );
    }

    /**
     * Define debug function
     *
     * @return string
     */
    public function getDebugHelperFunctionModifier()
    {
        return ":img:full:";
    }

    /**
     * Get the scripts
     */
    public function enqueueScripts() {
        wp_enqueue_media();
    }

    /**
     * Covert Image to HTML string
     */
    public function getString()
    {
        $name = $this->getNameAttributeString();
        $this->attrClass( 'image-picker' );
        $value = $this->getValue();
        $this->setAttribute('data-tr-field', $this->getContextId());

        if(is_array($value) && is_numeric($value['id'] ?? null)) {
            $value = $value['id'];
        }

        $value = tr_cast($value, 'int');

        $this->removeAttribute( 'name' );

        if ( ! $this->getSetting( 'button' )) {
            $this->setSetting( 'button', __('Insert Image', 'typerocket-domain') );
        }

        if ( ! $this->getSetting( 'clear' )) {
            $this->setSetting( 'clear', __('Clear', 'typerocket-domain') );
        }

        if ($value != "") {
            $image = wp_get_attachment_image( (int) $value, $this->getSetting('size', 'thumbnail') );
        } else {
            $image = '';
        }

        if (empty( $image )) {
            $value = '';
        }

        $classes = class_names('tr-image-picker-placeholder', [
            'tr-dark-image-background' => $this->getSetting('background', 'light') == 'dark'
        ]);

        $html = (string) Html::input( 'hidden', $name, $value, $this->getAttributes() );
        $html .= '<div class="button-group">';
        $html .= Html::el( 'input', [
            'type'  => 'button',
            'class' => 'tr-image-picker-button button',
            'data-size' => $this->getSetting('size', 'thumbnail'),
            'value' => $this->getSetting( 'button' )
        ]);
        $html .= Html::el( 'input', [
            'type'  => 'button',
            'class' => 'tr-image-picker-clear button',
            'value' => $this->getSetting( 'clear' )
        ]);
        $html .= '</div>';
        $html .= Html::div([
            'class' => $classes
        ], $image );

        return $html;
    }

}