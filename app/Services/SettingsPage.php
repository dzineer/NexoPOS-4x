<?php
namespace App\Services;

use App\Events\SettingsSavedEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SettingsPage
{
    protected $form     =   [];

    /**
     * returns the defined form
     * @return array
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Validate a form using a provided
     * request. Based on the actual settings page rules
     * @param Request $request
     * @return array
     */
    public function validateForm( Request $request )
    {
        $service            =   new CrudService;
        $arrayRules         =   $service->extractCrudValidation( $this, null );

        /**
         * As rules might contains complex array (with Rule class),
         * we don't want that array to be transformed using the dot key form.
         */
        $isolatedRules  =   $service->isolateArrayRules( $arrayRules );

        /**
         * Let's properly flat everything.
         */
        $flatRules      =   collect( $isolatedRules )->mapWithKeys( function( $rule ) {
            return [ $rule[0] => $rule[1] ];
        })->toArray();

        return $flatRules;
    }

    /**
     * Get form plain data, by excaping the tabs
     * identifiers
     * @param Request $request
     * @return array
     */
    public function getPlainData( Request $request )
    {
        $service        =   new CrudService;
        return $service->getPlainData( $this, $request );
    }

    /**
     * Proceed to a saving using te provided
     * request along with the plain data
     * @param Request $request
     * @return array
     */
    public function saveForm( Request $request )
    {
        $service        =   new CrudService;
        $options        =   app()->make( Options::class );

        foreach( $service->getPlainData( $this, $request ) as $key => $value ) {
            if ( empty( $value ) ) {
                $options->delete( $key );
            } else {
                $options->set( $key, $value );
            }
        }

        event( new SettingsSavedEvent( $options->get() ) );

        return [
            'status'    =>  'success',
            'message'   =>  __( 'The form has been successfully saved.' )
        ];
    }
}