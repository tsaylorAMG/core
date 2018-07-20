<?php

namespace TypeRocket\Elements\Fields;

use TypeRocket\Core\Config;
use TypeRocket\Elements\Fields\Field;
use TypeRocket\Html\Generator;
use TypeRocket\Html\Tag;

class Location extends Field
{

    protected $useGoogle = false;

    /**
     * Init is normally used to setup initial configuration like a
     * constructor does.
     *
     * @return mixed
     */
    protected function init()
    {
        $this->setType( 'location' );
        $api = Config::locate('app.keys.google_maps');
    }

    protected function beforeEcho()
    {
        $api = Config::locate('app.api_keys.google_maps');
        if($this->useGoogle && $api) {
            $this->paths = Config::locate('paths');
            $assetVersion = Config::locate('app.assets');
            $assets = $this->paths['urls']['assets'];

            wp_enqueue_script('tr_field_location_google_script',
                'https://maps.googleapis.com/maps/api/js?libraries=geometry&key=' . $api, [], $assetVersion, true);
            wp_enqueue_script('tr_field_location_script', $assets . '/typerocket/js/location.field.js',
                ['jquery', 'tr_field_location_google_script'], $assetVersion, true);
        }
    }

    /**
     * Configure in all concrete Field classes
     *
     * @return string
     */
    public function getString()
    {
        $class = $this->getAttribute('class');
        $values = $this->getValue();
        $name = $this->getNameAttributeString();
        $html = '<div class="tr_field_location_fields">';
        $field_groups = [
            ['address1' => __('Address')],
            ['address2' => __('Address Line 2')],
            ['city' => __('City'), 'state' => __('State'), 'zip' => __('Zip'), 'country' => __('Country')]
        ];

        if($this->useGoogle) {
            $field_groups[] = ['lat' => __('Lat'), 'lng' => __('Lng')];
        }

        foreach ($field_groups as $group) {
            $html .= '<div class="tr-flex-list tr-mt-10">';
            foreach($group as $field => $title ) {
                $attrs = [
                    'type' => 'text',
                    'value' => esc_attr( $values[$field] ?? '' ),
                    'name' => $name . '['. $field .']',
                    'class' => 'tr_field_location_' . $field
                ];
                echo '<div>';
                $html .= Tag::make('label',['class' => 'label-thin'], $title)->prependInnerTag(Tag::make('input', $attrs));
                echo '</div>';
            }
            $html .= '</div>';
        }

        if($this->useGoogle) {
            $html .= '<div class="tr_field_location_load_lat_lng_section button-group">
                <a class="button tr_field_location_load_lat_lng" type="button">Generate Lat/Lng From Address</a>
                <a class="button tr_field_location_clear_lat_lng" type="button">Clear Lat/Lng</a>
                </div>
                <div class="tr_field_location_google_map"></div>';
        }

        $html .= '</div>';

        return $html;
    }


    /**
     * Use Google API
     *
     * @return $this
     */
    public function useGoogle()
    {
        $this->useGoogle = true;
        return $this;
    }

}